<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Wooflix</title>
    <style>
        /* General resetting styling overrides */
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        table {
            border-collapse: collapse;
        }
        img {
            border: 0;
            outline: none;
            text-decoration: none;
            display: block;
        }
        
        /* Mobile-responsive grid layout utilities */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                padding: 10px !important;
            }
            .content-padding {
                padding: 20px !important;
            }
            .mobile-stack {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box;
                padding: 5px 0 !important;
            }
        }
    </style>
</head>
<body style="background-color: #f8f9fa; margin: 0; padding: 0;">

    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8f9fa; padding: 30px 0;">
        <tr>
            <td align="center">
                
                <table class="email-container" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    
                <tr>
                    <td align="center" style="background-color: #ffffff; padding: 25px 20px; border-bottom: 1px solid #f1f1f4;">
                        @if(!empty($settings['logo_desktop']))
                            <img src="{{ url('storage/' . $settings['logo_desktop']) }}" 
                                alt="Wooflix Logo" 
                                style="height: 52px; width: auto; max-width: 220px; display: block;" 
                                border="0" />
                        @else
                            <h1 style="margin: 0; padding: 0; font-size: 26px; font-style: italic; font-family: 'Arial Black', sans-serif; line-height: 1;">
                                <span style="color: #000000; font-weight: 900;">WOOF</span><span style="color: #f26522; font-weight: 900;">LIX</span>
                            </h1>
                        @endif
                    </td>
                </tr>

                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #f7d6d7 0%, #f5b0b4 100%); padding: 40px 35px; text-align: center; position: relative;">
                            <h2 style="color: #000000; font-size: 28px; font-weight: 800; margin: 0 0 10px 0; font-family: 'Helvetica Neue', Arial, sans-serif;">
                                Toys to Hug & Tug! 🎉
                            </h2>
                            <p style="color: #414143; font-size: 15px; margin: 0 0 20px 0; font-weight: 500;">
                                From Plush Toys to Rope Toys & Premium Treats.
                            </p>
                            <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                                <tr>
                                    <td align="center" bgcolor="#000000" style="border-radius: 6px;">
                                        <a href="{{ url('/') }}" target="_blank" style="font-size: 14px; font-weight: bold; color: #ffffff; text-decoration: none; padding: 12px 28px; display: inline-block;">
                                            Shop Now
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="content-padding" style="padding: 40px 35px; background-color: #ffffff;">
                            <h3 style="color: #000000; font-size: 20px; font-weight: 700; margin-top: 0; margin-bottom: 15px;">
                                Welcome to the Pack, {{ $name }}! 🐾
                            </h3>
                            <p style="color: #58595b; font-size: 15px; line-height: 1.6; margin-bottom: 25px;">
                                We are thrilled to welcome you and your furry best friend to the **Wooflix** family! Whether you are hunting for premium dry food, delicious rewards, toys, or healthcare supplies, we've curated the best items to keep tails wagging.
                            </p>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8f9fa; border-radius: 8px; padding: 15px; margin-bottom: 30px;">
                                <tr>
                                    <td class="mobile-stack" align="center" width="33.33%" style="font-size: 13px; color: #000000; font-weight: bold; font-family: sans-serif;">
                                        ⚡ Fast Shipping
                                    </td>
                                    <td class="mobile-stack" align="center" width="33.33%" style="font-size: 13px; color: #000000; font-weight: bold; font-family: sans-serif; border-left: 1px solid #e1e1e4; border-right: 1px solid #e1e1e4;">
                                        ⭐ Best Brands
                                    </td>
                                    <td class="mobile-stack" align="center" width="33.33%" style="font-size: 13px; color: #000000; font-weight: bold; font-family: sans-serif;">
                                        🛠️ 24/7 Support
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #58595b; font-size: 14px; line-height: 1.6; margin-bottom: 5px;">
                                Happy shopping,
                            </p>
                            <strong style="color: #f26522; font-size: 15px; display: block;">
                                Team Wooflix
                            </strong>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="background-color: #111111; padding: 30px 20px; text-align: center;">
                            <h4 style="color: #ffffff; font-size: 16px; font-weight: bold; margin: 0 0 15px 0;">
                                Explore the World of Wooflix
                            </h4>
                            
                            <p style="color: #b3b3b3; font-size: 12px; margin: 0 0 20px 0; line-height: 1.5;">
                                ✉ info@wooflix.in &nbsp;|&nbsp; 📞 +91 9953119048 <br>
                                D-15, Paryavaran Complex, IGNOU Road, New Delhi 110030
                            </p>

                            <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto 20px auto;">
                                <tr>
                                    <td style="color: #666666; font-size: 12px; font-family: sans-serif;">
                                        @php $hasLinks = false; @endphp

                                        @if(!empty($settings['facebook_url']))
                                            <a href="{{ $settings['facebook_url'] }}" target="_blank" style="color: #ffffff; text-decoration: none; font-weight: bold; margin: 0 8px;">Facebook</a>
                                            @php $hasLinks = true; @endphp
                                        @endif

                                        @if(!empty($settings['instagram_url']))
                                            {!! $hasLinks ? '<span style="color: #414143;">•</span>' : '' !!}
                                            <a href="{{ $settings['instagram_url'] }}" target="_blank" style="color: #ffffff; text-decoration: none; font-weight: bold; margin: 0 8px;">Instagram</a>
                                            @php $hasLinks = true; @endphp
                                        @endif

                                        @if(!empty($settings['twitter_url']))
                                            {!! $hasLinks ? '<span style="color: #414143;">•</span>' : '' !!}
                                            <a href="{{ $settings['twitter_url'] }}" target="_blank" style="color: #ffffff; text-decoration: none; font-weight: bold; margin: 0 8px;">Twitter</a>
                                            @php $hasLinks = true; @endphp
                                        @endif

                                        @if(!empty($settings['youtube_url']))
                                            {!! $hasLinks ? '<span style="color: #414143;">•</span>' : '' !!}
                                            <a href="{{ $settings['youtube_url'] }}" target="_blank" style="color: #ffffff; text-decoration: none; font-weight: bold; margin: 0 8px;">YouTube</a>
                                            @php $hasLinks = true; @endphp
                                        @endif

                                        @if(!empty($settings['pinterest_url']))
                                            {!! $hasLinks ? '<span style="color: #414143;">•</span>' : '' !!}
                                            <a href="{{ $settings['pinterest_url'] }}" target="_blank" style="color: #ffffff; text-decoration: none; font-weight: bold; margin: 0 8px;">Pinterest</a>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #666666; font-size: 11px; margin: 0; line-height: 1.4;">
                                You are receiving this email because you registered an account on wooflix.in.<br>
                                <a href="#" style="color: #f26522; text-decoration: underline;">Unsubscribe</a> from this list at any time.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>