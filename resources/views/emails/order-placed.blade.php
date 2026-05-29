<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            margin: 0; padding: 0; width: 100% !important; background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        table { border-collapse: collapse; }
        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; padding: 10px !important; }
            .col-stack { display: block !important; width: 100% !important; box-sizing: border-box; padding-bottom: 15px !important; }
        }
    </style>
</head>
<body style="background-color: #f8f9fa; margin: 0; padding: 0;">

    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8f9fa; padding: 30px 0;">
        <tr>
            <td align="center">
                
                <table class="container" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    
                    <tr>
                        <td align="center" style="padding: 25px 20px; border-bottom: 1px solid #f1f1f4; background-color: #ffffff;">
                            @if(!empty($settings['logo_desktop']))
                                <img src="{{ url('storage/' . $settings['logo_desktop']) }}" alt="Wooflix Logo" style="height: 50px; width: auto;" border="0" />
                            @else
                                <h1 style="margin: 0; padding: 0; font-size: 26px; font-style: italic; font-family: 'Arial Black', sans-serif; line-height: 1;">
                                    <span style="color: #000000; font-weight: 900;">WOOF</span><span style="color: #f26522; font-weight: 900;">LIX</span>
                               </h1>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td align="center" bgcolor="#f26522" style="padding: 35px 20px; text-align: center; background-color: #f26522;">
                            <h2 style="color: #ffffff; font-size: 24px; font-weight: 800; margin: 0 0 10px 0;">Order Confirmed! 🐾</h2>
                            <p style="color: #ffffff; font-size: 14px; margin: 0; opacity: 0.95;">
                                Thank you for shopping with us! Your pet's goodies are being processed.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px 25px; background-color: #ffffff;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 25px;">
                                <tr>
                                    <td class="col-stack" width="50%" style="vertical-align: top;">
                                        <span style="color: #888888; font-size: 11px; text-transform: uppercase; font-weight: bold; display: block; margin-bottom: 4px;">Order Details</span>
                                        <strong style="color: #000000; font-size: 15px; display: block;">Order: #{{ $order->order_number }}</strong>
                                        <span style="color: #58595b; font-size: 13px;">Date: {{ $order->created_at->format('d M Y, h:i A') }}</span>
                                        <span style="color: #58595b; font-size: 13px; display: block; margin-top: 4px;">
                                            Status: <b style="text-transform: uppercase; color: #f26522;">{{ $order->status }}</b>
                                        </span>
                                    </td>
                                    <td class="col-stack" width="50%" style="vertical-align: top;">
                                        <span style="color: #888888; font-size: 11px; text-transform: uppercase; font-weight: bold; display: block; margin-bottom: 4px;">Shipping Address</span>
                                        <strong style="color: #000000; font-size: 14px; display: block;">{{ $order->shipping_name }}</strong>
                                        <p style="color: #58595b; font-size: 13px; margin: 4px 0 0 0; line-height: 1.4;">
                                            {{ $order->shipping_address_line1 }},<br>
                                            {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_postal_code }}<br>
                                            📞 {{ $order->shipping_phone }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <h4 style="color: #000000; font-size: 15px; font-weight: bold; margin: 0 0 12px 0; border-bottom: 2px solid #000000; padding-bottom: 6px;">Items Ordered</h4>
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px;">
                                <thead>
                                    <tr style="border-bottom: 1px solid #e1e1e4;">
                                        <th align="left" style="padding: 10px 0; font-size: 13px; color: #888888;">Item</th>
                                        <th align="center" style="padding: 10px 0; font-size: 13px; color: #888888;" width="15%">Qty</th>
                                        <th align="right" style="padding: 10px 0; font-size: 13px; color: #888888;" width="25%">Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $calculatedSubtotal = 0; @endphp
                                    @foreach($order->items as $item)
                                        @php $itemTotal = $item->price * $item->quantity; $calculatedSubtotal += $itemTotal; @endphp
                                        <tr style="border-bottom: 1px solid #f1f1f4;">
                                            <td style="padding: 12px 0; font-size: 14px; color: #000000; font-weight: bold;">
                                                {{ $item->name }}
                                            </td>
                                            <td align="center" style="padding: 12px 0; font-size: 14px; color: #58595b;">
                                                {{ $item->quantity }}
                                            </td>
                                            <td align="right" style="padding: 12px 0; font-size: 14px; color: #000000; font-weight: bold;">
                                                ₹{{ number_format($itemTotal, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-top: 1px solid #e1e1e4; padding-top: 15px; margin-top: 10px;">
                                <tr>
                                    <td align="right" style="padding: 4px 0; font-size: 14px; color: #58595b;">Subtotal:</td>
                                    <td align="right" style="padding: 4px 0; font-size: 14px; color: #000000; font-weight: 500;" width="30%">₹{{ number_format($calculatedSubtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td align="right" style="padding: 4px 0; font-size: 14px; color: #58595b;">Shipping:</td>
                                    <td align="right" style="padding: 4px 0; font-size: 14px; color: #000000; font-weight: 500;">
                                        {{ $order->shipping_amount > 0 ? '₹' . number_format($order->shipping_amount, 2) : 'FREE' }}
                                    </td>
                                </tr>
                                @if($order->discount > 0)
                                    <tr>
                                        <td align="right" style="padding: 4px 0; font-size: 14px; color: #28a745;">Discount:</td>
                                        <td align="right" style="padding: 4px 0; font-size: 14px; color: #28a745; font-weight: 500;">- ₹{{ number_format($order->discount, 2) }}</td>
                                    </tr>
                                @endif
                                <tr style="border-top: 1px solid #f1f1f4;">
                                    <td align="right" style="padding: 10px 0 0 0; font-size: 16px; color: #000000; font-weight: bold;">Grand Total:</td>
                                    <td align="right" style="padding: 10px 0 0 0; font-size: 16px; color: #f26522; font-weight: bold;">₹{{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="background-color: #111111; padding: 25px 20px; text-align: center;">
                            <p style="color: #b3b3b3; font-size: 12px; margin: 0 0 12px 0; line-height: 1.5;">
                                Questions about this order? Reach out to us at:<br>
                                ✉ {{ $settings['contact_email'] ?? 'info@wooflix.in' }} | 📞 {{ $settings['contact_phone'] ?? '+91 9953119048' }}
                            </p>
                            <p style="color: #555555; font-size: 10px; margin: 0;">
                                &copy; {{ date('Y') }} Wooflix E-Commerce. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
                
            </td>
        </tr>
    </table>

</body>
</html>