<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        body {
            margin: 0; padding: 0; width: 100% !important; background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        table { border-collapse: collapse; }
        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; padding: 10px !important; }
            .otp-box { font-size: 28px !important; letter-spacing: 4px !important; padding: 15px !important; }
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
                            <h2 style="color: #ffffff; font-size: 24px; font-weight: 800; margin: 0 0 10px 0;">Verify Your Email 🔐</h2>
                            <p style="color: #ffffff; font-size: 14px; margin: 0; opacity: 0.95;">
                                Use the secure code parameters below to access your account dashboard space.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 35px; background-color: #ffffff; text-align: center;">
                            <p style="color: #1a1a1a; font-size: 16px; line-height: 1.5; margin: 0 0 25px 0;">
                                Hello Paw-parent! Someone requested a lookup login validation entry block for this account. Enter this one-time password on the store login prompt window interface:
                            </p>

                            <div style="margin: 30px auto; max-width: 320px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td align="center" bgcolor="#f8f9fa" class="otp-box" style="background-color: #f8f9fa; border: 2px dashed #f26522; border-radius: 8px; padding: 18px; font-family: 'Courier New', Courier, monospace; font-size: 36px; font-weight: bold; color: #1a1a1a; letter-spacing: 6px; text-align: center;">
                                            {{ $otp }}
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <p style="color: #888888; font-size: 12px; margin: 25px 0 0 0; line-height: 1.4;">
                                💡 This code parameters bundle is strictly confidential and expires automatically in <b>10 minutes</b>. If you did not execute this authentication entry lookup sequence, please ignore this digital notice safely.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="background-color: #111111; padding: 25px 20px; text-align: center;">
                            <p style="color: #b3b3b3; font-size: 12px; margin: 0 0 12px 0; line-height: 1.5;">
                                Questions about this notice? Reach out to us at:<br>
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