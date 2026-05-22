<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="
    font-family: Arial, sans-serif;
    background:#f5f5f5;
    padding:30px;
">

<div style="
    max-width:700px;
    margin:auto;
    background:#fff;
    padding:30px;
    border-radius:10px;
">

    {{-- HEADER --}}
    <div style="margin-bottom:30px;">

        <img
            src="{{ asset('storage/' . ($settings['logo_desktop'] ?? '')) }}"
            style="height:50px;"
        >

    </div>

    {{-- TITLE --}}
    <h2 style="margin-bottom:10px;">

        Order {{ ucfirst($order->status) }}

    </h2>

    <p style="color:#555;">

        {{ $statusMessage }}

    </p>

    {{-- ORDER META --}}
    <table width="100%" cellpadding="8" style="
        margin-top:20px;
        margin-bottom:30px;
        border-collapse: collapse;
    ">

        <tr>
            <td><strong>Order Number</strong></td>
            <td>{{ $order->order_number }}</td>
        </tr>

        <tr>
            <td><strong>Payment Status</strong></td>
            <td>{{ ucfirst($order->payment_status) }}</td>
        </tr>

        <tr>
            <td><strong>Total</strong></td>
            <td>₹{{ number_format($order->total_amount) }}</td>
        </tr>

    </table>

    {{-- ITEMS --}}
    <h3 style="margin-bottom:15px;">
        Order Items
    </h3>

    <table width="100%" cellpadding="12" style="
        border-collapse: collapse;
        border:1px solid #ddd;
    ">

        <thead>

            <tr style="background:#f5f5f5;">

                <th align="left">
                    Product
                </th>

                <th align="center">
                    Qty
                </th>

                <th align="right">
                    Price
                </th>

            </tr>

        </thead>

        <tbody>

            @foreach($order->items as $item)

                <tr>

                    <td style="border-top:1px solid #eee;">

                        {{ $item->name }}

                        @if($item->variant)

                            <br>

                            <small>
                                {{ $item->variant->name }}
                            </small>

                        @endif

                    </td>

                    <td
                        align="center"
                        style="border-top:1px solid #eee;"
                    >

                        {{ $item->quantity }}

                    </td>

                    <td
                        align="right"
                        style="border-top:1px solid #eee;"
                    >

                        ₹{{ number_format($item->price) }}

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

    {{-- FOOTER --}}
    <div style="
        margin-top:40px;
        color:#666;
        font-size:14px;
    ">

        Thank you for shopping with us.

    </div>

</div>

</body>
</html>