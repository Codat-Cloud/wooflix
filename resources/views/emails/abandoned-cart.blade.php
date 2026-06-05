<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; background-color: #f8f9fa; margin:0; padding:20px; }
        .card { background: #ffffff; max-width: 550px; margin: 0 auto; border-radius: 12px; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.05); }
        .btn { background-color: #f26522; color: #ffffff !important; text-decoration: none; padding: 12px 25px; display: inline-block; border-radius: 6px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <div style="background-color: #f26522; padding: 20px; text-align: center; color: white;">
            <h2>Wooflix 🐾</h2>
        </div>
        <div style="padding: 30px;">
            <h3>Hi {{ $user->name }},</h3>
            <p>We noticed you left some premium pet goodies sitting in your shopping cart. Items sell out fast, so we've saved your selection for you!</p>
            
            <table width="100%" style="border-collapse: collapse; margin: 20px 0;">
                @foreach($cartItems as $item)
                    <tr style="border-bottom: 1px solid #eeeeee;">
                        <td style="padding: 10px 0;">
                            <strong>{{ $item->product?->name }}</strong>
                            @if($item->variant_name)<br><small style="color: #666;">{{ $item->variant_name }}</small>@endif
                        </td>
                        <td align="center">Qty: {{ $item->quantity }}</td>
                        <td align="right">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </table>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/cart') }}" class="btn">Proceed to Checkout</a>
            </div>
        </div>
    </div>
</body>
</html>