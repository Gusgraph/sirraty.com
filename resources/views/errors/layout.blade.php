{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/errors/layout.blade.php --}}
{{-- ===================================================== --}}
@php
    $homeUrl = auth()->check() ? route('app.home') : url('/');
    $jokes = [
        'Juha packed a map for the internet, then remembered the best shortcut is still the back button.',
        'Juha asked the page where it went. The page said, “I am making wudu for a clean reload.”',
        'Juha brought a lantern to a broken link and said, “At least now the path is honest.”',
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sirraty' }}</title>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root { --bg: #071112; --text: #f3fff9; --muted: rgba(243,255,249,.73); --line: rgba(30,255,201,.39); --green: #25d891; --cyan: #22d3ee; --panel: rgba(4,17,19,.73); }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; color: var(--text); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background: radial-gradient(circle at 19% 13%, rgba(37,216,145,.19), transparent 31%), radial-gradient(circle at 83% 17%, rgba(34,211,238,.15), transparent 29%), linear-gradient(139deg, #06110d 0%, #0b1518 47%, #07120e 100%); letter-spacing: 0; }
        body::before { content: ""; position: fixed; inset: 0; pointer-events: none; background-image: linear-gradient(rgba(37,216,145,.07) 1px, transparent 1px), linear-gradient(90deg, rgba(34,211,238,.05) 1px, transparent 1px); background-size: 73px 73px; mask-image: radial-gradient(circle at center, #000 0%, transparent 73%); }
        a { color: inherit; text-decoration: none; }
        .symbols { pointer-events: none; user-select: none; position: fixed; inset: 0; overflow: hidden; color: rgba(243,255,249,.05); z-index: 0; }
        .symbols span { position: absolute; font-size: clamp(2.7rem, 11vw, 8.7rem); }
        .error-shell { position: relative; z-index: 1; min-height: 100vh; display: grid; place-items: center; padding: 27px; }
        .error-panel { width: min(719px, 100%); border: 1px solid var(--line); border-radius: 15px; background: var(--panel); box-shadow: 0 0 57px rgba(37,216,145,.13); padding: 37px; backdrop-filter: blur(19px); }
        .status { display: inline-flex; align-items: center; gap: 11px; color: var(--cyan); font-weight: 800; letter-spacing: 0; }
        h1 { margin: 19px 0 11px; font-size: clamp(2.1rem, 7vw, 4.7rem); line-height: .97; letter-spacing: 0; }
        p { margin: 0; color: var(--muted); font-size: 1.03rem; line-height: 1.7; }
        .jokes { display: grid; gap: 11px; margin: 27px 0; }
        .joke { border-left: 3px solid var(--green); padding: 9px 0 9px 15px; color: rgba(243,255,249,.86); }
        .actions { display: flex; flex-wrap: wrap; gap: 11px; margin-top: 27px; }
        .btn { display: inline-flex; align-items: center; gap: 9px; min-height: 39px; padding: 9px 15px; border-radius: 7px; border: 1px solid var(--line); background: rgba(37,216,145,.09); color: var(--text); }
        .btn.primary { border-color: rgba(37,216,145,.73); background: rgba(37,216,145,.19); color: #eafff7; }
        @media (max-width: 640px) { .error-panel { padding: 27px 19px; } .actions { display: grid; } }
    </style>
</head>
<body>
    <div class="symbols" aria-hidden="true">
        <span style="left:7%;top:11%">﷽</span><span style="right:11%;top:17%">ﷺ</span><span style="left:17%;bottom:13%">ﷴ</span><span style="right:19%;bottom:11%">ﷻ</span><span style="left:51%;top:47%">ﷲ</span>
    </div>
    <main class="error-shell">
        <section class="error-panel" aria-labelledby="error-title">
            <span class="status"><i class="{{ $icon ?? 'fa-solid fa-triangle-exclamation' }}"></i> {{ $code ?? 'Error' }}</span>
            <h1 id="error-title">{{ $headline ?? 'Something needs attention' }}</h1>
            <p>{{ $message ?? 'The page did not load cleanly. Try again or return home.' }}</p>
            <div class="jokes" aria-label="A little Juha humor">
                @foreach($jokes as $joke)
                    <p class="joke">{{ $joke }}</p>
                @endforeach
            </div>
            <div class="actions">
                <a class="btn primary" href="{{ $homeUrl }}"><i class="fa-solid fa-house"></i> Home</a>
                <a class="btn" href="javascript:history.back()"><i class="fa-solid fa-arrow-left"></i> Back</a>
            </div>
        </section>
    </main>
</body>
</html>
