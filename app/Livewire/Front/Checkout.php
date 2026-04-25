<?php

namespace App\Livewire\Front;

use Livewire\Component;
use App\Models\CartItem;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

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
        $this->shippingCost = $this->deliveryType === 'express' ? 49 : 0;
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = max(
            0,
            $this->subtotal + $this->shippingCost - $this->discount
        );
    }

    // ================= PLACE ORDER =================
    public function placeOrder()
    {
        if (!Auth::check()) {
            $this->dispatch('open-login-modal');
            return;
        }

        if (!$this->selectedAddress) {
            session()->flash('error', 'Please select address');
            return;
        }

        if ($this->items->isEmpty()) {
            session()->flash('error', 'Cart is empty');
            return;
        }

        // CREATE ORDER
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $this->total,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => 'cod',
            // ✅ COUPON DATA
            'coupon_id' => $this->appliedCoupon?->id,
            'discount' => $this->discount,

        ]);

        // CREATE ITEMS
        foreach ($this->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'name' => $item->display_name,
                'price' => $item->price,
                'quantity' => $item->quantity,
            ]);
        }

        // CLEAR CART
        CartItem::where('session_id', session()->getId())->delete();

        return redirect('/thank-you');
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

    public function saveAddress()
    {
        $this->validate([
            'form.name' => 'required',
            'form.phone' => 'required',
            'form.address_line1' => 'required',
            'form.city' => 'required',
            'form.state' => 'required',
            'form.postal_code' => 'required',
        ]);

        $address = Address::create([
            ...$this->form,
            'user_id' => auth()->id(),
            'country' => 'India',
        ]);

        // 🔥 reload address list
        $this->loadAddresses();

        // 🔥 auto-select new address
        $this->selectedAddress = $address->id;

        // 🔥 reset form
        $this->reset('form');

        $this->dispatch('close-address-form');
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
}
