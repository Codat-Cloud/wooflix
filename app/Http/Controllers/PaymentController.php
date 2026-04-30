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
        // Initialize API with keys from config
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        try {
            // 1. Cryptographic Signature Verification
            // This ensures the data actually came from Razorpay
            $attributes = [
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature
            ];

            $api->utility->verifyPaymentSignature($attributes);

            // 2. Fetch the order and ensure it belongs to the logged-in user
            $order = Order::where('id', $request->order_id)
                ->where('user_id', auth()->id())
                ->where('payment_status', 'pending')
                ->firstOrFail();

            // 3. Database Updates in a Transaction
            DB::transaction(function () use ($order, $request) {
                $order->update([
                    'payment_status' => 'paid',
                    'status'         => 'confirmed',
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'paid_at'        => now(),
                ]);

                // Clear the specific user's cart
                CartItem::where('user_id', auth()->id())->delete();
            });

            return redirect()->route('front.success', ['order' => $order->id]);
        } catch (\Exception $e) {
            // If signature verification fails or order not found
            Log::error("Razorpay Error: " . $e->getMessage());
            return redirect()->route('front.checkout')->with('error', 'Payment verification failed.');
        }
    }

    public function success(Order $order)
    {
        return view('front.success', compact('order'));
    }
}
