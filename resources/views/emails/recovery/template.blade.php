<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subjectLine }}</title>
</head>
<body style="margin:0;background:#f7f4ef;color:#17221c;font-family:Arial,Helvetica,sans-serif">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f7f4ef;padding:32px 0">
        <tr><td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:573px;background:#fffdf7;border:1px solid #d9d1c3;border-radius:7px;overflow:hidden">
                <tr><td style="padding:24px 28px;text-align:center"><img src="https://res.cloudinary.com/duja2smra/image/upload/logo_ca006s.png" width="213" alt="Sirraty" style="max-width:100%;height:auto"></td></tr>
                <tr><td style="height:7px;background:#247553"></td></tr>
                <tr><td style="padding:28px;font-size:15px;line-height:1.6;color:#435047">{!! $body !!}</td></tr>
                <tr><td style="padding:18px 28px;border-top:1px solid #e7dfd1;font-size:12px;line-height:1.5;color:#647067;text-align:center">
                    You are receiving this because an old Sirraty account used this email. If you do not want account recovery messages, <a href="{{ $unsubscribeUrl }}">unsubscribe</a>.
                </td></tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
