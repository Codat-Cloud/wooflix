<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // public function verify(Request $request)
    // {
    //     $order = Order::findOrFail($request->order_id);

    //     // In production, call Razorpay API here to verify the signature!

    //     $order->update([
    //         'payment_status' => 'paid',
    //         'status' => 'confirmed'
    //     ]);

    //     // Clear Cart
    //     CartItem::where('user_id', auth()->id())->delete();

    //     return redirect('/thank-you')->with('order_number', $order->order_number);
    // }

    public function verify(Request $request)
    {
        // Validate that we actually got the order_id from the JS redirect
        $order = Order::where('id', $request->order_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Verify the payment (In production, use Razorpay Signature Verification)
        $order->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'razorpay_payment_id' => $request->razorpay_payment_id
        ]);

        // Clear Cart for the specific user
        CartItem::where('user_id', auth()->id())->delete();

        // PASS the order ID to the success route
        return redirect()->route('front.success', ['order' => $order->id]);
    }

    public function success(Order $order) {
        return view('front.success', compact('order'));
    }
}
