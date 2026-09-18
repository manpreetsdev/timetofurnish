@php
    $type = $type ?? 'customer';
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type === 'admin' ? 'New Contact Us Inquiry' : 'Thank You for Contacting Us' }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f8f9fa; font-family: Arial, Helvetica, sans-serif; color:#333333; line-height:1.6;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8f9fa; padding:30px 10px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:600px; background-color:#ffffff; border-radius:8px; border:1px solid #e9ecef; padding:30px;">
                    
                    <!-- LOGO -->
                    <tr>
                        <td align="center" style="padding-bottom:20px; border-bottom:1px solid #eeeeee;">
                            <img src="https://timetofurnish.com/public/uploads/all/226xIlqxsV0wxtSTFRdXMVlTf2dmfBNkCOlYUnqO.jpg"
                                 alt="Time To Furnish"
                                 width="180"
                                 style="max-width:180px; height:auto; display:block;">
                        </td>
                    </tr>

                    @if($type === 'admin')
                        <!-- ADMIN EMAIL CONTENT -->
                        <tr>
                            <td style="padding-top:25px;">
                                <h2 style="margin:0 0 15px 0; color:#671919; font-size:20px;">
                                    New Contact Form Inquiry
                                </h2>
                                <p style="margin:0 0 20px 0; color:#555555; font-size:14px;">
                                    A new customer inquiry has been submitted through your website:
                                </p>

                                <table width="100%" cellpadding="10" cellspacing="0" border="0" style="border:1px solid #e0e0e0; border-radius:6px; font-size:14px; margin-bottom:20px;">
                                    <tr style="background-color:#f9f9f9;">
                                        <td width="30%" style="font-weight:bold; color:#555; border-bottom:1px solid #eee;">First Name:</td>
                                        <td style="border-bottom:1px solid #eee; color:#222;">{{ $first_name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold; color:#555; border-bottom:1px solid #eee;">Last Name:</td>
                                        <td style="border-bottom:1px solid #eee; color:#222;">{{ $last_name ?? '' }}</td>
                                    </tr>
                                    <tr style="background-color:#f9f9f9;">
                                        <td style="font-weight:bold; color:#555; border-bottom:1px solid #eee;">Email:</td>
                                        <td style="border-bottom:1px solid #eee;">
                                            <a href="mailto:{{ $email ?? '' }}" style="color:#671919; text-decoration:none; font-weight:bold;">
                                                {{ $email ?? '' }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:bold; color:#555; border-bottom:1px solid #eee;">Phone:</td>
                                        <td style="border-bottom:1px solid #eee;">
                                            <a href="tel:{{ $phone ?? '' }}" style="color:#671919; text-decoration:none; font-weight:bold;">
                                                {{ $phone ?? '' }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr style="background-color:#f9f9f9;">
                                        <td style="font-weight:bold; color:#555; vertical-align:top;">Message:</td>
                                        <td style="white-space:pre-wrap; color:#222;">{{ $message1 ?? '' }}</td>
                                    </tr>
                                </table>

                                <p style="margin:0; font-size:13px; color:#666; background-color:#fff8f0; border-left:4px solid #c78c43; padding:10px 15px; border-radius:4px;">
                                    <strong style="color:#671919;">Action Required:</strong> Please reply directly to the customer using the email address provided above.
                                </p>
                            </td>
                        </tr>

                    @else
                        <!-- CUSTOMER EMAIL CONTENT -->
                        <tr>
                            <td style="padding-top:25px;">
                                <h2 style="margin:0 0 15px 0; color:#671919; font-size:20px;">
                                    Thank You for Contacting Us
                                </h2>
                                <p style="margin:0 0 15px 0; font-size:14px; color:#444444;">
                                    Hi <strong>{{ $first_name ?? 'Customer' }}</strong>,
                                </p>
                                <p style="margin:0 0 20px 0; font-size:14px; color:#444444;">
                                    Thank you for contacting <strong>Time To Furnish</strong>. We have received your message and our team will get back to you as soon as possible.
                                </p>

                                <div style="background-color:#f9f6f0; border:1px solid #ebdccb; padding:15px; border-radius:6px; margin-bottom:20px;">
                                    <strong style="color:#671919; font-size:14px; display:block; margin-bottom:4px;">
                                        What happens next?
                                    </strong>
                                    <span style="font-size:13px; color:#666666;">
                                        Our team will review your inquiry and contact you shortly.
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endif

                    <!-- FOOTER -->
                    <tr>
                        <td align="center" style="padding-top:20px; border-top:1px solid #eeeeee; font-size:12px; color:#888888;">
                            <strong style="color:#671919;">Time To Furnish</strong> — Furniture &amp; Interior Solutions<br>
                            &copy; {{ date('Y') }} Time To Furnish. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>