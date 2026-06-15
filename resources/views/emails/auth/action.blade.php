{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/emails/auth/action.blade.php --}}
{{-- ===================================================== --}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | Sirraty</title>
    <style>
        body { margin: 0; background: #f7f4ef; color: #17221c; font-family: Arial, Helvetica, sans-serif; }
        .page { width: 100%; padding: 37px 0; background: radial-gradient(circle at 17% 13%, rgba(36, 117, 83, .17), transparent 271px), radial-gradient(circle at 83% 19%, rgba(179, 139, 49, .19), transparent 319px), #f7f4ef; }
        .wrap { width: 100%; max-width: 573px; margin: 0 auto; }
        .brand { padding: 0 19px 19px; }
        .brand img { display: block; width: auto; height: 51px; max-width: 213px; border: 0; filter: drop-shadow(0 7px 17px rgba(6,38,27,.19)); }
        .panel { margin: 0 19px; background: #fffdf7; border: 1px solid #d9d1c3; border-radius: 7px; overflow: hidden; box-shadow: 0 11px 37px rgba(23, 34, 28, .09); }
        .art { height: 73px; background: linear-gradient(117deg, rgba(36, 117, 83, .93), rgba(179, 139, 49, .73)), repeating-linear-gradient(137deg, rgba(255,255,255,.23) 0 1px, transparent 1px 19px); }
        .content { padding: 31px 27px; }
        h1 { margin: 0 0 15px; font-size: 27px; line-height: 1.17; color: #17221c; }
        p { margin: 0 0 19px; font-size: 15px; line-height: 1.57; color: #647067; }
        .button { display: inline-block; padding: 13px 19px; background: #247553; color: #ffffff !important; border-radius: 7px; font-weight: 700; text-decoration: none; }
        .link { word-break: break-all; color: #247553; font-size: 13px; }
        .note { padding-top: 19px; border-top: 1px solid #d9d1c3; font-size: 13px; }
        .footer { padding: 19px; color: #647067; font-size: 13px; text-align: center; }
        .footer a { color: #247553; text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
    <div style="display:none;max-height:0;overflow:hidden">{{ $preheader }}</div>
    <div class="page">
        <div class="wrap">
            <div class="brand"><img src="https://res.cloudinary.com/duja2smra/image/upload/logo_ca006s.png" width="213" height="51" alt="Sirraty"></div>
            <div class="panel">
                <div class="art" aria-hidden="true"></div>
                <div class="content">
                    <h1>{{ $title }}</h1>
                    <p>{{ $intro }}</p>
                    <p><a class="button" href="{{ $actionUrl }}">{{ $actionLabel }}</a></p>
                    <p class="note">{{ $note }}</p>
                    <p class="link">{{ $actionUrl }}</p>
                </div>
            </div>
            <div class="footer">Sirraty · Halal Social<br><a href="{{ route('public.privacy') }}">Privacy</a> · <a href="{{ route('public.terms') }}">Terms</a> · <a href="{{ route('public.business') }}">Business</a></div>
        </div>
    </div>
</body>
</html>
