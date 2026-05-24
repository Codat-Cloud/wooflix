<?php

namespace App\Livewire\Front;

use App\Mail\OrderStatusMail;
use Livewire\Component;
use App\Models\CartItem;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\MailConfigService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class Checkout extends Component
{
    public $addresses = [];
    public $selectedAddress = null;

    public $deliveryType = 'standard';
    public $shippingCost = 0;

    public $items = [];
    public $subtotal = 0;
    public $total = 0;

    public $couponCode = '';
    public $appliedCoupon = null;
    public $discount = 0;
    public $couponError = '';

    public $defaultAddress;

    // Define the listener
    protected $listeners = ['cartUpdated' => 'refreshCheckout'];

    // Razorpay
    public $razorpay_order_id;

    public function refreshCheckout()
    {
        $this->loadCart(); // This recalculates subtotal, shipping, and total
    }

    public function mount()
    {
        $this->loadCart();
        $this->loadAddresses();
    }

    // ================= CART =================
    public function loadCart()
    {
        $sessionId = session()->getId();

        $query = CartItem::with('product', 'variant');

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('session_id', $sessionId);
        }

        $this->items = $query->get()->values();

        $this->subtotal = $this->items->sum(fn($i) => $i->price * $i->quantity);

        $this->calculateTotal();
    }

    // ================= ADDRESS =================
    public function loadAddresses()
    {
        if (!auth()->check()) {
            $this->addresses = collect();
            $this->defaultAddress = null;
            $this->selectedAddress = null;
            return;
        }

        $this->addresses = auth()->user()->addresses;

        $this->defaultAddress = $this->addresses->firstWhere('is_default', true)
            ?? $this->addresses->first();

        $this->selectedAddress = $this->defaultAddress?->id;
    }

    // ================= DELIVERY =================
    public function updatedDeliveryType()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        // Define constants to avoid "Magic Numbers"
        $THRESHOLD = 699;
        $STANDARD_FEE = 85;
        $EXPRESS_FEE = 180;

        // Ensure subtotal is treated as a float/int
        $currentSubtotal = (float) $this->subtotal;

        if ($this->deliveryType === 'express') {
            $this->shippingCost = $EXPRESS_FEE;
        } else {
            // Standard logic
            $this->shippingCost = ($currentSubtotal >= $THRESHOLD) ? 0 : $STANDARD_FEE;
        }

        $this->total = max(0, ($currentSubtotal + $this->shippingCost) - (float) $this->discount);
    }


    public function applyCoupon()
    {
        // dd('clicked');

        $this->couponError = '';
        $this->discount = 0;
        $this->appliedCoupon = null;

        if (!$this->couponCode) {
            $this->couponError = 'Enter a coupon code';
            return;
        }

        $coupon = Coupon::where('code', strtoupper(trim($this->couponCode)))
            ->available()
            ->first();

        if (!$coupon) {
            $this->couponError = 'Invalid or expired coupon';
            return;
        }

        // MIN SPEND CHECK
        if ($coupon->min_spend && $this->subtotal < $coupon->min_spend) {
            $this->couponError = "Minimum order ₹{$coupon->min_spend} required";
            return;
        }

        $discount = 0;

        // TYPE HANDLING
        if ($coupon->type === 'percentage') {
            $discount = ($this->subtotal * $coupon->value) / 100;

            if ($coupon->max_discount) {
                $discount = min($discount, $coupon->max_discount);
            }
        }

        if ($coupon->type === 'fixed') {
            $discount = $coupon->value;
        }

        if ($coupon->type === 'free_shipping') {
            $this->shippingCost = 0;
        }

        $this->discount = round($discount, 2);
        $this->appliedCoupon = $coupon;

        $this->calculateTotal();
    }

    public function removeCoupon()
    {
        $this->couponCode = '';
        $this->appliedCoupon = null;
        $this->discount = 0;
        $this->couponError = '';

        $this->calculateTotal();
    }

    public function render()
    {
        return view('livewire.front.checkout');
    }

    // ================= PLACE ORDER =================

    public function placeOrder()
    {
        // 1. Structural Access Checks
        if (!Auth::check()) {
            $this->dispatch('open-login-modal');
            return;
        }

        if (!$this->defaultAddress || $this->items->isEmpty()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please verify your delivery address and cart items.'
            ]);
            return;
        }

        try {
            $amountInPaisa = (int) round($this->total * 100);

            // 2. Initialize Razorpay Server Connection and Create Order Token
            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            $razorpayOrder = $api->order->create([
                'receipt'         => 'rcpt_' . time(),
                'amount'          => $amountInPaisa, // Amount passed in Paisa currency standard
                'currency'        => 'INR',
                'payment_capture' => 1 // Automatically capture the payment instantly on authorization
            ]);

            if (empty($razorpayOrder['id'])) {
                throw new \Exception('Failed to generate secure transaction order token from Razorpay API.');
            }

            // 3. Commit Order Shell into PostgreSQL Database
            $order = Order::create([
                'user_id'                => Auth::id(),
                'total_amount'           => $this->total,
                'shipping_amount'        => $this->shippingCost,
                'status'                 => 'pending',
                'payment_status'         => 'pending',
                'payment_method'         => 'online',
                'razorpay_order_id'      => $razorpayOrder['id'], // Airtight transaction mapping link
                'shipping_name'          => $this->defaultAddress->name,
                'shipping_phone'         => $this->defaultAddress->phone,
                'shipping_address_line1' => $this->defaultAddress->address_line1,
                'shipping_city'          => $this->defaultAddress->city,
                'shipping_state'         => $this->defaultAddress->state,
                'shipping_postal_code'   => $this->defaultAddress->postal_code,
                'shipping_country'       => $this->defaultAddress->country ?? 'India',
            ]);

            // 4. Map and Save Cart Line Items
            foreach ($this->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'name'       => $item->product->name,
                    'price'      => $item->price,
                    'quantity'   => $item->quantity,
                ]);
            }

            // 5. Package Payload Matrix for Frontend Event Handler Interceptor
            $paymentPayload = [
                'order_id'          => $order->id,
                'order_number'      => $order->order_number,
                'razorpay_order_id' => $order->razorpay_order_id,
                'amount'            => $amountInPaisa,
                'customer_name'     => Auth::user()->name,
                'customer_email'    => Auth::user()->email,
                'customer_phone'    => Auth::user()->phone ?? $this->defaultAddress->phone ?? '0000000000'
            ];

            // 6. Broadcast event straight down to your window script listener
            $this->dispatch('initiate-razorpay-payment', $paymentPayload);
        } catch (\Exception $e) {
            Log::error('Wooflix Order Initialization Aborted: ' . $e->getMessage());

            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => 'Payment gateway connection error. Please try again.'
            ]);
        }
    }
}
