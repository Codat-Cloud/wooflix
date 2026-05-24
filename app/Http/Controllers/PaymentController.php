<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function verify(Request $request)
    {
        // 1. Initialize API with your keys
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        // Log incoming data for debugging
        Log::info('Verification Payload:', $request->all());

        try {
            // 2. Cryptographic Signature Verification
            // This ensures the data actually came from Razorpay and matches the Order ID
            $attributes = [
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature
            ];

            // This throws an exception if the signature is invalid
            $api->utility->verifyPaymentSignature($attributes);

            // 3. Fetch the order from your DB
            // We use your DB 'id' (from order_id) and ensure ownership
            $order = Order::where('id', $request->order_id)
                ->where('user_id', auth()->id())
                ->where('payment_status', 'pending')
                ->firstOrFail();

            // 4. Update DB within a Transaction for data integrity
            DB::transaction(function () use ($order, $request) {
                $order->update([
                    'payment_status'      => 'paid',
                    'status'              => 'confirmed',
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'paid_at'             => now(),
                ]);

                // Clear the cart for the logged-in user
                CartItem::where('user_id', auth()->id())->delete();
            });

            // 5. Redirect to your success page
            return redirect()->route('front.payments.success', ['order' => $order->id]);
        } catch (\Exception $e) {
            // Log the specific error (e.g., Signature mismatch or Order not found)
            Log::error("Razorpay Error: " . $e->getMessage());

            // Redirect back to checkout with a user-friendly message
            return redirect()->route('front.checkout')->with('error', 'Payment verification failed. Please contact support if your money was deducted.');
        }
    }

    public function success(Order $order)
    {
        // Basic security check: only the owner can see their success page
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('front.payments.success', compact('order'));
    }
}
