{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/components/layouts/app.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.base :title="$title ?? 'Sirraty'">
    <style>
        .app-shell {
            position: relative;
            background:
                linear-gradient(117deg, color-mix(in srgb, var(--bg) 91%, transparent), color-mix(in srgb, var(--panel) 73%, transparent)),
                radial-gradient(ellipse at 13% 11%, rgba(36, 117, 83, .23), transparent 29rem),
                radial-gradient(ellipse at 87% 17%, rgba(179, 139, 49, .19), transparent 31rem),
                radial-gradient(ellipse at 53% 93%, rgba(24, 34, 28, .13), transparent 37rem),
                repeating-linear-gradient(137deg, rgba(255, 255, 255, .057) 0 1px, transparent 1px 19px);
        }

        .app-shell::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background:
                linear-gradient(90deg, color-mix(in srgb, var(--line) 37%, transparent) 1px, transparent 1px),
                linear-gradient(0deg, color-mix(in srgb, var(--line) 27%, transparent) 1px, transparent 1px);
            background-size: 73px 73px;
            mask-image: linear-gradient(to bottom, transparent, #000 19%, #000 81%, transparent);
            opacity: .27;
        }

        .app-shell .panel,
        .app-shell .topbar,
        .app-shell .nav a,
        .app-shell .nav button,
        .app-shell .theme-toggle {
            box-shadow: 0 11px 37px rgba(0, 0, 0, .07);
        }
    </style>
    <div class="shell app-shell">
        <nav class="topbar">
            <a class="brand" href="{{ route('app.interest') }}"><span class="brand-mark"><i class="fa-solid fa-compass"></i></span> Sirraty</a>
            <div class="nav">
                <a href="{{ route('app.interest') }}">Interest</a>
                <a href="{{ route('app.recap') }}">Recap</a>
                <a href="{{ route('app.module', 'pages') }}">Pages</a>
                <a href="{{ route('app.module', 'groups') }}">Groups</a>
                <a href="{{ route('app.module', 'market') }}">Market</a>
                <a href="{{ route('app.module', 'messages') }}">Messages</a>
                <a href="{{ route('app.privacy') }}">Privacy</a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}">Admin Zone</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">@csrf <button type="submit">Sign out</button></form>
                @endauth
                <label class="theme-toggle"><input type="radio" name="theme" value="light"><span><i class="fa-regular fa-sun"></i></span></label>
                <label class="theme-toggle"><input type="radio" name="theme" value="dark"><span><i class="fa-regular fa-moon"></i></span></label>
            </div>
        </nav>
        <main class="wrap" style="padding: 27px 0 73px;">
            @if(session('status'))<div class="panel" style="margin-bottom:15px;color:var(--brand)">{{ session('status') }}</div>@endif
            {{ $slot }}
        </main>
    </div>
</x-layouts.base>
