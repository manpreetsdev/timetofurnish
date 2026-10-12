<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject }}</title>
</head>

<body style="margin:0;padding:0;background:#f2f2f0;font-family:Arial,Helvetica,sans-serif;color:#222222;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#f2f2f0;padding:18px 0;">
        <tr>
            <td align="center">
                <table width="640" cellpadding="0" cellspacing="0" role="presentation"
                    style="width:100%;max-width:640px;background:#ffffff;border:1px solid #ded8d0;">
                    <tr>
                        <td style="padding:22px 28px;background:#fbfaf8;border-bottom:1px solid #e3ddd5;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td align="left" valign="middle">
                                        <img src="{{ static_asset('assets/img/TTF.jpg') }}" width="140" alt="Time To Furnish"
                                            style="display:block;width:140px;height:auto;">
                                    </td>
                                    <td align="right" valign="middle"
                                        style="font-size:12px;line-height:1.5;color:#666666;text-align:right;white-space:nowrap;">
                                        <span style="display:block;font-size:10px;font-weight:bold;letter-spacing:0.5px;text-transform:uppercase;color:#8a6f4d;">Date</span>
                                        <strong style="color:#222222;">{{ \Carbon\Carbon::parse($date ?? now())->format('j M, Y g:i A') }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:26px 28px;font-size:14px;line-height:1.65;">
                            {!! $body !!}
                        </td>
                    </tr>
                    <tr>
                        <td align="center"
                            style="padding:16px 28px;border-top:1px solid #e3ddd5;background:#fbfaf8;font-size:12px;color:#666666;line-height:1.6;">
                            <strong style="color:#222222;">Time To Furnish</strong><br>
                            sales@timetofurnish.com &nbsp;|&nbsp; www.timetofurnish.com &nbsp;|&nbsp; +44 7751510365
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
