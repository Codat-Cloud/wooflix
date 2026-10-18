<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlaced;
use App\Models\AbandonedCheckout; // 🟢 Imported AbandonedCheckout
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function verify(Request $request)
    {
        // 1. Initialize API with your keys
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        // Log incoming data for debugging
        Log::info('Razorpay Verification Payload:', $request->all());

        try {
            // 2. Cryptographic Signature Verification
            $attributes = [
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ];

            // Throws an exception if the signature is invalid or tampered with
            $api->utility->verifyPaymentSignature($attributes);

            // 3. Fetch the order from DB (Ensuring user owns the pending order)
            $order = Order::with('items.product', 'items.variant')
                ->where('id', $request->order_id)
                ->where('user_id', auth()->id())
                ->where('payment_status', 'pending')
                ->firstOrFail();

            $sessionId = session()->getId();
            $userId = auth()->id();

            // 4. Update DB within a Transaction for data integrity
            DB::transaction(function () use ($order, $request, $sessionId, $userId) {
                // Confirm Order & Record Gateway Payment ID
                $order->update([
                    'payment_status'      => 'paid',
                    'status'              => 'confirmed',
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'paid_at'             => now(),
                ]);

                // 🟢 A. PURGE ABANDONED CHECKOUT RECORD IMMEDIATELY
                AbandonedCheckout::query()
                    ->where('session_id', $sessionId)
                    ->orWhere('user_id', $userId)
                    ->delete();

                // 🟢 B. PURGE SHOPPING CART (Both Session & User ID)
                CartItem::query()
                    ->where(function ($query) use ($sessionId, $userId) {
                        $query->where('user_id', $userId)
                            ->orWhere('session_id', $sessionId);
                    })
                    ->delete();

                // C. Mark newly created guest user account verified
                $user = $order->user;
                if ($user && is_null($user->email_verified_at)) {
                    $user->update([
                        'email_verified_at' => now(),
                    ]);
                }
            });

            // 5. Send Confirmation Email (Executed outside transaction so SMTP timeouts don't revert payment records)
            try {
                if ($order->user?->email) {
                    Mail::to($order->user->email)->send(new OrderPlaced($order));
                }
            } catch (\Exception $mailEx) {
                Log::error('Order Confirmation Email Failed: ' . $mailEx->getMessage());
            }

            // 6. Redirect to success page
            return redirect()->route('front.payments.success', ['order' => $order->id]);
        } catch (\Exception $e) {
            Log::error('Razorpay Verification Error: ' . $e->getMessage());

            return redirect()
                ->route('front.checkout')
                ->with('error', 'Payment verification failed. If your account was debited, please contact support with order reference #' . ($request->order_id ?? 'N/A'));
        }
    }

    public function success(Order $order)
    {
        // Security check: only the order owner can view their invoice/confirmation
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('front.payments.success', compact('order'));
    }
}
