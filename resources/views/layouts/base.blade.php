{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/layouts/base.blade.php --}}
{{-- ===================================================== --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sirraty' }}</title>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root { --bg: #f7f4ef; --panel: #fffdf7; --text: #17221c; --muted: #647067; --line: #d9d1c3; --brand: #247553; --gold: #b38b31; --soft: rgba(36,117,83,.11); }
        [data-theme="dark"] { --bg: #111712; --panel: #172019; --text: #edf4ed; --muted: #aeb9ae; --line: #303a31; --brand: #73c79f; --gold: #d1ad57; --soft: rgba(115,199,159,.15); }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: var(--bg); color: var(--text); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; letter-spacing: 0; }
        a { color: inherit; text-decoration: none; }
        button, input, textarea, select { font: inherit; }
        .shell { min-height: 100vh; }
        .topbar { position: sticky; top: 0; z-index: 19; display: flex; align-items: center; justify-content: space-between; gap: 19px; padding: 15px 27px; background: color-mix(in srgb, var(--bg) 91%, transparent); border-bottom: 1px solid var(--line); backdrop-filter: blur(15px); }
        .brand { display: inline-flex; align-items: center; gap: 11px; font-weight: 800; font-size: 1.15rem; }
        .brand-mark { width: 37px; height: 37px; display: grid; place-items: center; border-radius: 999px; background: var(--soft); color: var(--brand); }
        .nav { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; }
        .nav a, .nav button, .btn { border: 1px solid var(--line); background: var(--panel); color: var(--text); min-height: 39px; padding: 9px 15px; border-radius: 7px; cursor: pointer; }
        .btn.primary { background: var(--brand); border-color: var(--brand); color: #fff; }
        .btn.link { border-color: transparent; background: transparent; }
        .theme-toggle { display: inline-grid; grid-auto-flow: column; gap: 3px; padding: 3px; border: 1px solid var(--line); border-radius: 999px; background: var(--panel); }
        .theme-toggle input { position: absolute; opacity: 0; }
        .theme-toggle span { display: inline-grid; place-items: center; min-width: 37px; min-height: 31px; border-radius: 999px; color: var(--muted); }
        .theme-toggle input:checked + span { background: var(--soft); color: var(--brand); }
        .wrap { width: min(1187px, calc(100% - 31px)); margin: 0 auto; }
        .grid { display: grid; gap: 19px; }
        .grid.two { grid-template-columns: minmax(0, 1fr) 319px; }
        .panel { background: var(--panel); border: 1px solid var(--line); border-radius: 7px; padding: 19px; }
        .muted { color: var(--muted); }
        .field { display: grid; gap: 7px; margin: 0 0 15px; }
        .field input, .field textarea, .field select { width: 100%; border: 1px solid var(--line); border-radius: 7px; padding: 11px 13px; background: var(--bg); color: var(--text); }
        .row { display: flex; gap: 11px; align-items: center; flex-wrap: wrap; }
        .section-title { margin: 0 0 15px; font-size: 1.23rem; }
        .empty { padding: 27px; text-align: center; color: var(--muted); border: 1px dashed var(--line); border-radius: 7px; }
        .symbols { pointer-events: none; user-select: none; position: fixed; inset: 0; overflow: hidden; z-index: 0; color: color-mix(in srgb, var(--text) 7%, transparent); }
        .symbols span { position: absolute; font-size: clamp(2.3rem, 9vw, 7.3rem); }
        main, header, .topbar { position: relative; z-index: 1; }
        @media (max-width: 830px) { .topbar { padding: 11px 15px; } .grid.two { grid-template-columns: 1fr; } .nav { width: 100%; } }
    </style>
    @stack('styles')
</head>
<body>
    <div class="symbols" aria-hidden="true">
        <span style="left:7%;top:13%">﷽</span><span style="right:11%;top:19%">ﷺ</span><span style="left:17%;bottom:11%">ﷴ</span><span style="right:19%;bottom:13%">ﷻ</span><span style="left:51%;top:47%">ﷲ</span>
    </div>
    {{ $slot }}
    <script>
        const storedTheme = localStorage.getItem('sirraty-theme') || 'light';
        document.documentElement.dataset.theme = storedTheme;
        document.querySelectorAll('input[name="theme"]').forEach((input) => {
            input.checked = input.value === storedTheme;
            input.addEventListener('change', () => {
                document.documentElement.dataset.theme = input.value;
                localStorage.setItem('sirraty-theme', input.value);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
