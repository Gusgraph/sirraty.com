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
            isolation: isolate;
            overflow: hidden;
            background:
                linear-gradient(117deg, rgba(247, 244, 239, .77), rgba(255, 253, 247, .69)),
                url("https://res.cloudinary.com/duja2smra/image/upload/2BG-_Jun_11_2026_05_44_19_PM_ojailg.webp") center / cover fixed no-repeat;
        }

        .app-shell::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -2;
            pointer-events: none;
            background:
                linear-gradient(90deg, rgba(36, 117, 83, .057) 1px, transparent 1px),
                linear-gradient(0deg, rgba(179, 139, 49, .057) 1px, transparent 1px),
                repeating-linear-gradient(137deg, rgba(23, 34, 28, .027) 0 1px, transparent 1px 19px);
            background-size: 73px 73px;
            mask-image: linear-gradient(to bottom, transparent, #000 19%, #000 81%, transparent);
            opacity: .37;
        }

        .app-shell::after {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background:
                radial-gradient(circle at 19% 29%, rgba(255, 253, 247, .73), transparent 19rem),
                radial-gradient(circle at 79% 71%, rgba(36, 117, 83, .17), transparent 27rem),
                linear-gradient(180deg, rgba(255, 253, 247, .11), rgba(23, 34, 28, .07));
            opacity: .91;
        }

        .app-shell .panel,
        .app-shell .topbar,
        .app-shell .nav a,
        .app-shell .nav button,
        .app-shell .theme-toggle {
            box-shadow: 0 11px 37px rgba(0, 0, 0, .07);
        }

        [data-theme="dark"] .app-shell {
            background:
                linear-gradient(117deg, rgba(17, 23, 18, .81), rgba(23, 32, 25, .73)),
                url("https://res.cloudinary.com/duja2smra/image/upload/2BG-_Jun_11_2026_05_44_19_PM_ojailg.webp") center / cover fixed no-repeat;
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
