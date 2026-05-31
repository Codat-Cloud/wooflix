<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\ShiprocketService;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    protected $shiprocket;

    public function __construct(ShiprocketService $shiprocket)
    {
        $this->shiprocket = $shiprocket;
    }

    public function index(Request $request)
    {
        $awb = trim($request->query('awb'));
        $trackingData = null;
        $associatedOrder = null;
        $error = null;

        if (!empty($awb)) {
            // 1. Fetch tracking timeline from Shiprocket Service
            $result = $this->shiprocket->trackByAwb($awb);

            if ($result['success'] && isset($result['data']['shipment_track_activities'])) {
                $trackingData = $result['data'];

                // 2. Locate the order safely in your database to pull structural parameters
                $associatedOrder = Order::where('awb_code', $awb)->first();
            } else {
                $error = "We couldn't find any tracking updates for AWB: " . htmlspecialchars($awb) . ". Please cross-check the number.";
            }
        }

        return view('front.track-order', compact('awb', 'trackingData', 'associatedOrder', 'error'));
    }
}
