<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Did you forget something for your pet?</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">

    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f6f8; padding: 40px 10px;">
        <tr>
            <td align="center">
                
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 25px rgba(0,0,0,0.06); border-collapse: separate;">
                    
                    <!-- 1. HEADER LOGO -->
                    <tr>
                        <td align="center" style="padding: 30px 20px; border-bottom: 1px solid #f0f2f5; background-color: #ffffff;">
                            @if(!empty($settings['logo_desktop']))
                                <img src="{{ url('storage/' . $settings['logo_desktop']) }}" alt="Wooflix Logo" style="height: 45px; width: auto; display: block;" border="0" />
                            @else
                                <h1 style="margin: 0; padding: 0; font-size: 28px; font-family: 'Arial Black', Gadget, sans-serif; letter-spacing: -1px; line-height: 1;">
                                    <span style="color: #1a1a1a; font-weight: 900;">WOOF</span><span style="color: #ff6b00; font-weight: 900;">LIX</span>🐾
                                </h1>
                            @endif
                        </td>
                    </tr>

                    <!-- 2. GREETING & MESSAGE -->
                    <tr>
                        <td style="padding: 35px 40px 15px 40px;">
                            @php
                                $customerName = $checkout->name ?? $user->name ?? 'Pet Parent';
                                $recoveryLink = $url ?? ($checkout->recovery_token ? route('front.checkout', ['recover' => $checkout->recovery_token]) : url('/checkout'));
                                $itemsList = $checkout->cart_snapshot ?? $cartItems ?? [];
                            @endphp

                            <h2 style="margin: 0 0 14px 0; font-size: 22px; color: #1a1a1a; font-weight: 700; line-height: 1.3;">
                                Hi {{ $customerName }},
                            </h2>
                            <p style="margin: 0; font-size: 15px; color: #515b66; line-height: 1.6;">
                                We noticed you left some pet essentials in your cart. Don't let your best friend miss out! Items can sell out quickly, so we've saved your selection for you.
                            </p>
                        </td>
                    </tr>

                    <!-- 3. CART ITEMS LIST -->
                    <tr>
                        <td style="padding: 10px 40px 25px 40px;">
                            <table width="100%" style="border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 2px solid #f0f2f5;">
                                        <th align="left" style="padding-bottom: 12px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #9aa6b2;">Product</th>
                                        <th align="center" style="padding-bottom: 12px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #9aa6b2; width: 60px;">Qty</th>
                                        <th align="right" style="padding-bottom: 12px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #9aa6b2; width: 100px;">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $calculatedTotal = 0; @endphp
                                    @foreach($itemsList as $item)
                                        @php
                                            $isArr = is_array($item);
                                            $pName = $isArr ? ($item['product_name'] ?? 'Product') : ($item->product?->name ?? 'Product');
                                            $vName = $isArr ? ($item['variant_name'] ?? null) : ($item->variant?->name ?? null);
                                            $qty   = $isArr ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1);
                                            $price = $isArr ? ($item['price'] ?? 0) : ($item->price ?? 0);
                                            $calculatedTotal += ($price * $qty);
                                        @endphp
                                        <tr style="border-bottom: 1px solid #f0f2f5;">
                                            <td style="padding: 14px 0; vertical-align: middle;">
                                                <div style="font-size: 14px; font-weight: 600; color: #1a1a1a;">
                                                    {{ $pName }}
                                                </div>
                                                @if($vName)
                                                    <span style="font-size: 12px; color: #7f8c99; background-color: #f4f6f8; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px;">
                                                        {{ $vName }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td align="center" style="padding: 14px 0; font-size: 14px; font-weight: 600; color: #515b66; vertical-align: middle;">
                                                {{ $qty }}
                                            </td>
                                            <td align="right" style="padding: 14px 0; font-size: 14px; font-weight: 700; color: #1a1a1a; vertical-align: middle;">
                                                ₹{{ number_format($price * $qty, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    <tr>
                                        <td colspan="2" align="right" style="padding: 18px 0 0 0; font-size: 14px; font-weight: 600; color: #515b66;">
                                            Estimated Total:
                                        </td>
                                        <td align="right" style="padding: 18px 0 0 0; font-size: 18px; font-weight: 800; color: #ff6b00;">
                                            ₹{{ number_format($checkout->total_value ?? $calculatedTotal, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <!-- 4. CALL TO ACTION BUTTON -->
                    <tr>
                        <td align="center" style="padding: 10px 40px 40px 40px; border-bottom: 1px solid #f0f2f5;">
                            <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                                <tr>
                                    <td align="center" bgcolor="#ff6b00" style="border-radius: 8px;">
                                        <a href="{{ $recoveryLink }}" target="_blank" style="font-size: 16px; font-weight: 700; color: #ffffff; text-decoration: none; padding: 14px 35px; display: inline-block; letter-spacing: 0.5px;">
                                            Complete Your Order 🛒
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin: 15px 0 0 0; font-size: 13px; color: #9aa6b2;">
                                Click above to load your pre-filled cart and checkout in seconds.
                            </p>
                        </td>
                    </tr>

                    <!-- 5. FOOTER -->
                    <tr>
                        <td align="center" style="padding: 25px 40px; background-color: #fafbfc; text-align: center;">
                            <p style="margin: 0 0 6px 0; font-size: 12px; color: #9aa6b2; line-height: 1.5;">
                                &copy; {{ date('Y') }} Wooflix. All rights reserved.
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #cbd5e1;">
                                You received this reminder because your cart had uncompleted items. If you've already completed your purchase, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>