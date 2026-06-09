<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Did you forget something?</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">

    <!-- Background Canvas Container Wrapper -->
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f6f8; padding: 40px 10px;">
        <tr>
            <td align="center">
                
                <!-- Core Structured Mailer Sheet (Max 600px width standard for emails) -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 25px rgba(0,0,0,0.06); border-collapse: separate;">
                    
                    <!-- 1. BRAND BRANDING HEADER REGION -->
                    <tr>
                        <td align="center" style="padding: 30px 20px; border-bottom: 1px solid #f0f2f5; background-color: #ffffff;">
                            @if(!empty($settings['logo_desktop']))
                                <img src="{{ url('storage/' . $settings['logo_desktop']) }}" alt="Wooflix Logo" style="height: 45px; width: auto; display: block;" border="0" />
                            @else
                                <h1 style="margin: 0; padding: 0; font-size: 28px; font-family: 'Arial Black', Gadget, sans-serif; letter-spacing: -1px; line-height: 1;">
                                    <span style="color: #1a1a1a; font-weight: 900;">WOOF</span><span style="color: #f26522; font-weight: 900;">LIX</span>🐾
                                </h1>
                            @endif
                        </td>
                    </tr>

                    <!-- 2. MAIN BODY COPY HERO BLOCK -->
                    <tr>
                        <td style="padding: 40px 40px 20px 40px;">
                            <h2 style="margin: 0 0 16px 0; font-size: 22px; color: #1a1a1a; font-weight: 700; line-height: 1.3;">
                                Hi {{ $user->name }},
                            </h2>
                            <p style="margin: 0; font-size: 15px; color: #515b66; line-height: 1.6;">
                                We noticed you left some premium pet goodies sitting in your shopping cart. Don't let your best friend miss out! Items sell out fast, so we've saved your selection for you.
                            </p>
                        </td>
                    </tr>

                    <!-- 3. CART LIST ARCHITECTURE CONTAINER -->
                    <tr>
                        <td style="padding: 10px 40px 30px 40px;">
                            <table width="100%" style="border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 2px solid #f0f2f5;">
                                        <th align="left" style="padding-bottom: 12px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #9aa6b2;">Product Item</th>
                                        <th align="center" style="padding-bottom: 12px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #9aa6b2; width: 60px;">Qty</th>
                                        <th align="right" style="padding-bottom: 12px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #9aa6b2; width: 100px;">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $subtotal = 0; @endphp
                                    @foreach($cartItems as $item)
                                        @php $subtotal += ($item->price * $item->quantity); @endphp
                                        <tr style="border-bottom: 1px solid #f0f2f5;">
                                            <!-- Product Info & Image Placement Matrix Group -->
                                            <td style="padding: 16px 0; vertical-align: middle;">
                                                <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        @if(!empty($item->product?->main_image))
                                                            <td style="padding-right: 14px;">
                                                                <img src="{{ url('storage/' . $item->product->main_image) }}" alt="Product Image" style="width: 56px; height: 56px; object-fit: cover; border-radius: 8px; border: 1px solid #f0f2f5; display: block;" />
                                                            </td>
                                                        @endif
                                                        <td style="vertical-align: middle;">
                                                            <div style="font-size: 15px; font-weight: 600; color: #1a1a1a; margin-bottom: 2px;">
                                                                {{ $item->product?->name }}
                                                            </div>
                                                            @if($item->variant_name)
                                                                <span style="font-size: 13px; color: #7f8c99; background-color: #f4f6f8; padding: 2px 6px; border-radius: 4px; display: inline-block;">
                                                                    {{ $item->variant_name }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <!-- Item Quantity Counter Segment -->
                                            <td align="center" style="padding: 16px 0; font-size: 15px; font-weight: 600; color: #515b66; vertical-align: middle;">
                                                {{ $item->quantity }}
                                            </td>
                                            <!-- Extended Absolute Item Financial Cost -->
                                            <td align="right" style="padding: 16px 0; font-size: 15px; font-weight: 700; color: #1a1a1a; vertical-align: middle;">
                                                ₹{{ number_format($item->price * $item->quantity, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    <!-- Cart Aggregate Summary Calculation Breakdown Row -->
                                    <tr>
                                        <td colspan="2" align="right" style="padding: 20px 0 0 0; font-size: 15px; font-weight: 600; color: #515b66;">
                                            Estimated Total:
                                        </td>
                                        <td align="right" style="padding: 20px 0 0 0; font-size: 18px; font-weight: 800; color: #f26522;">
                                            ₹{{ number_format($subtotal, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <!-- 4. CALL TO ACTION INTERACTION BUTTON -->
                    <tr>
                        <td align="center" style="padding: 10px 40px 50px 40px; border-bottom: 1px solid #f0f2f5;">
                            <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                                <tr>
                                    <td align="center" bgcolor="#f26522" style="border-radius: 8px;">
                                        <a href="{{ url('/checkout') }}" target="_blank" style="font-size: 16px; font-weight: 700; color: #ffffff; text-decoration: none; padding: 14px 35px; display: inline-block; letter-spacing: 0.5px;">
                                            Proceed to Checkout 🛒
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin: 20px 0 0 0; font-size: 13px; color: #9aa6b2;">
                                No configuration required. Your cart will be loaded instantly.
                            </p>
                        </td>
                    </tr>

                    <!-- 5. MARKETING TRUST FOOTER SIGNATURE -->
                    <tr>
                        <td align="center" style="padding: 30px 40px; background-color: #fafbfc; text-align: center;">
                            <p style="margin: 0 0 8px 0; font-size: 12px; color: #9aa6b2; line-height: 1.5;">
                                &copy; {{ date('Y') }} Wooflix. All Rights Reserved.
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #cbd5e1;">
                                You received this automated email reminder because item objects were appended to your store account cart profile index.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>