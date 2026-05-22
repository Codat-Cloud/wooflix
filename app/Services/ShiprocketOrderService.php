<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class ShiprocketOrderService
{
    protected ShiprocketService $shiprocket;

    public function __construct(
        ShiprocketService $shiprocket
    ) {
        $this->shiprocket = $shiprocket;
    }

    public function createShipment(
        Order $order,
        array $package
    ) {

        $response = $this->shiprocket->client()
            ->post(
            'https://apiv2.shiprocket.in/v1/external/orders/create/adhoc',
            [

                'order_id' => $order->order_number,

                'order_date' =>
                    now()->format('Y-m-d H:i'),

                'pickup_location' => 'Home',

                'billing_customer_name' =>
                    $order->shipping_name,

                'billing_last_name' => '',

                'billing_address' =>
                    $order->shipping_address_line1,

                'billing_city' =>
                    $order->shipping_city,

                'billing_pincode' =>
                    $order->shipping_postal_code,

                'billing_state' =>
                    $order->shipping_state,

                'billing_country' =>
                    $order->shipping_country ?? 'India',

                'billing_email' =>
                    $order->user?->email
                    ?? 'customer@example.com',

                'billing_phone' =>
                    $order->shipping_phone,

                'shipping_is_billing' => true,

                'order_items' => $order->items
                    ->map(function ($item) {

                        return [

                            'name' => $item->name,

                            'sku' =>
                                $item->variant?->sku
                                ?? 'SKU',

                            'units' =>
                                $item->quantity,

                            'selling_price' =>
                                $item->price,

                        ];
                    })
                    ->values()
                    ->toArray(),

                'payment_method' =>
                    $order->payment_method === 'cod'
                    ? 'COD'
                    : 'Prepaid',

                'sub_total' =>
                    $order->total_amount,

                'length' =>
                    $package['length'],

                'breadth' =>
                    $package['width'],

                'height' =>
                    $package['height'],

                'weight' =>
                    $package['weight'],

            ]
        )->json();

        return $response;
    }
}