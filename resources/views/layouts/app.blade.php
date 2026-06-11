{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/layouts/app.blade.php --}}
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
        .app-shell .app-cabinet,
        .app-shell .cabinet-link,
        .app-shell .cabinet-action,
        .app-shell .theme-button {
            box-shadow: 0 11px 37px rgba(0, 0, 0, .07);
        }

        .app-shell .panel {
            background: rgba(255, 253, 247, .03);
            border-color: #39ff88;
            backdrop-filter: blur(11px);
        }

        .app-shell .app-cabinet,
        .app-shell .cabinet-link,
        .app-shell .cabinet-action,
        .app-shell .btn,
        .app-shell .theme-button,
        .app-shell .field input,
        .app-shell .field textarea,
        .app-shell .field select,
        .app-shell .empty {
            border-color: #39ff88;
        }

        .app-shell .field input,
        .app-shell .field textarea,
        .app-shell .field select {
            background: rgba(255, 253, 247, .03);
        }

        .app-shell .field input:focus,
        .app-shell .field textarea:focus,
        .app-shell .field select:focus {
            background: rgba(57, 255, 136, .07);
            border-color: #39ff88;
            box-shadow: 0 0 0 3px rgba(57, 255, 136, .19);
            outline: 0;
        }

        .app-shell .wrap {
            padding: 27px 73px 73px 0;
        }

        .app-shell .app-cabinet {
            position: fixed;
            top: 19px;
            right: 0;
            bottom: 19px;
            z-index: 29;
            display: flex;
            flex-direction: column;
            gap: 7px;
            width: 57px;
            padding: 11px 7px;
            overflow: hidden;
            border: 1px solid #39ff88;
            border-right: 0;
            border-radius: 15px 0 0 15px;
            background: rgba(255, 253, 247, .03);
            backdrop-filter: blur(19px);
            transition: width .19s ease, background .19s ease;
        }

        .app-shell .app-cabinet:hover,
        .app-shell .app-cabinet:focus-within {
            width: 271px;
            background: rgba(255, 253, 247, .07);
        }

        .app-shell .cabinet-stack {
            display: grid;
            gap: 7px;
        }

        .app-shell .cabinet-spacer {
            flex: 1;
        }

        .app-shell .cabinet-form {
            margin: 0;
        }

        .app-shell .cabinet-link,
        .app-shell .cabinet-action,
        .app-shell .theme-button {
            display: grid;
            grid-template-columns: 37px 1fr;
            align-items: center;
            gap: 11px;
            width: 100%;
            min-height: 39px;
            padding: 0;
            border: 1px solid #39ff88;
            border-radius: 7px;
            background: rgba(255, 253, 247, .03);
            color: var(--text);
            cursor: pointer;
            white-space: nowrap;
        }

        .app-shell .cabinet-link i,
        .app-shell .cabinet-link svg,
        .app-shell .cabinet-action i,
        .app-shell .theme-button i {
            display: grid;
            place-items: center;
            width: 37px;
            min-height: 37px;
            color: var(--brand);
        }

        .app-shell .cabinet-link svg {
            width: 23px;
            height: 23px;
            margin: 7px;
            fill: none;
            stroke: var(--brand);
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-width: 1.9;
        }

        .app-shell .cabinet-label {
            opacity: 0;
            transform: translateX(7px);
            transition: opacity .17s ease, transform .17s ease;
        }

        .app-shell .app-cabinet:hover .cabinet-label,
        .app-shell .app-cabinet:focus-within .cabinet-label {
            opacity: 1;
            transform: translateX(0);
        }

        .app-shell .cabinet-link:hover,
        .app-shell .cabinet-link:focus-visible,
        .app-shell .cabinet-action:hover,
        .app-shell .cabinet-action:focus-visible,
        .app-shell .theme-button:hover,
        .app-shell .theme-button:focus-visible {
            background: rgba(57, 255, 136, .07);
            outline: 0;
        }

        .app-shell .composer-panel {
            border-color: transparent;
            box-shadow: none;
        }

        .app-shell .composer-icon {
            display: inline-grid;
            place-items: center;
            width: 43px;
            height: 43px;
            margin-bottom: 15px;
            border-radius: 999px;
            color: var(--brand);
            background: rgba(57, 255, 136, .07);
        }

        .app-shell .quill-icon {
            width: 29px;
            height: 29px;
            fill: none;
            stroke: currentColor;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-width: 1.7;
        }

        .app-shell .feed-post {
            padding: 19px 0 0;
            border: 0;
            border-top: 1px solid rgba(57, 255, 136, .73);
            border-radius: 0;
            background: transparent;
            box-shadow: none;
            backdrop-filter: none;
        }

        .app-shell .side-card {
            border-color: rgba(57, 255, 136, .27);
        }

        [data-theme="dark"] .app-shell {
            background:
                linear-gradient(117deg, rgba(17, 23, 18, .51), rgba(23, 32, 25, .43)),
                url("https://res.cloudinary.com/duja2smra/image/upload/2BG-_Jun_11_2026_05_44_19_PM_ojailg.webp") center / cover fixed no-repeat;
        }

        [data-theme="dark"] .app-shell::after {
            background:
                radial-gradient(circle at 19% 29%, rgba(255, 253, 247, .13), transparent 17rem),
                radial-gradient(circle at 79% 71%, rgba(57, 245, 255, .11), transparent 27rem),
                linear-gradient(180deg, rgba(255, 253, 247, .03), rgba(23, 34, 28, .03));
            opacity: .57;
        }

        [data-theme="dark"] .app-shell .panel,
        [data-theme="dark"] .app-shell .app-cabinet,
        [data-theme="dark"] .app-shell .cabinet-link,
        [data-theme="dark"] .app-shell .cabinet-action,
        [data-theme="dark"] .app-shell .theme-button,
        [data-theme="dark"] .app-shell .btn,
        [data-theme="dark"] .app-shell .empty,
        [data-theme="dark"] .app-shell .field input,
        [data-theme="dark"] .app-shell .field textarea,
        [data-theme="dark"] .app-shell .field select {
            background: rgba(17, 23, 18, .03);
            border-color: #39f5ff;
        }

        [data-theme="dark"] .app-shell .field input:focus,
        [data-theme="dark"] .app-shell .field textarea:focus,
        [data-theme="dark"] .app-shell .field select:focus,
        [data-theme="dark"] .app-shell .cabinet-link:hover,
        [data-theme="dark"] .app-shell .cabinet-link:focus-visible,
        [data-theme="dark"] .app-shell .cabinet-action:hover,
        [data-theme="dark"] .app-shell .cabinet-action:focus-visible,
        [data-theme="dark"] .app-shell .theme-button:hover,
        [data-theme="dark"] .app-shell .theme-button:focus-visible {
            border-color: #39f5ff;
            background: rgba(57, 245, 255, .07);
            box-shadow: 0 0 0 3px rgba(57, 245, 255, .19);
        }

        [data-theme="dark"] .app-shell .composer-panel {
            border-color: transparent;
        }

        [data-theme="dark"] .app-shell .feed-post {
            border: 0;
            border-top: 1px solid rgba(57, 245, 255, .73);
            background: transparent;
        }

        [data-theme="dark"] .app-shell .side-card {
            border-color: rgba(57, 245, 255, .27);
        }

        @media (max-width: 830px) {
            .app-shell .wrap {
                padding-right: 57px;
            }

            .app-shell .app-cabinet {
                top: 11px;
                bottom: 11px;
            }
        }
    </style>
    <div class="shell app-shell">
        <nav class="app-cabinet" aria-label="App navigation">
            <div class="cabinet-stack">
                <a class="cabinet-link" href="{{ route('app.interest') }}">
                    <svg viewBox="0 0 64 64" aria-hidden="true"><path d="M51 7c-13 3-23 11-31 23-5 7-7 15-7 23 8 0 16-2 23-7 12-8 20-18 23-31" /><path d="M51 7c2 7 1 13-3 19-5 9-14 17-27 24" /><path d="M17 47c9-11 17-19 31-31" /><path d="M13 53l13-5" /></svg>
                    <span class="cabinet-label">Interest</span>
                </a>
                <a class="cabinet-link" href="{{ route('app.recap') }}"><i class="fa-solid fa-rotate"></i><span class="cabinet-label">Recap</span></a>
                <a class="cabinet-link" href="{{ route('app.module', 'pages') }}"><i class="fa-regular fa-flag"></i><span class="cabinet-label">Pages</span></a>
                <a class="cabinet-link" href="{{ route('app.module', 'groups') }}"><i class="fa-solid fa-people-group"></i><span class="cabinet-label">Groups</span></a>
                <a class="cabinet-link" href="{{ route('app.module', 'market') }}"><i class="fa-solid fa-store"></i><span class="cabinet-label">Market</span></a>
                <a class="cabinet-link" href="{{ route('app.module', 'messages') }}"><i class="fa-regular fa-message"></i><span class="cabinet-label">Messages</span></a>
                <a class="cabinet-link" href="{{ route('app.privacy') }}"><i class="fa-solid fa-shield-halved"></i><span class="cabinet-label">Privacy</span></a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a class="cabinet-link" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-user-shield"></i><span class="cabinet-label">Admin Zone</span></a>
                    @endif
                @endauth
            </div>
            <div class="cabinet-spacer"></div>
            <button class="theme-button" type="button" data-theme-cycle aria-label="Toggle dark mode"><i class="fa-regular fa-moon"></i><span class="cabinet-label">Mode</span></button>
            @auth
                <form class="cabinet-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="cabinet-action" type="submit"><i class="fa-solid fa-arrow-right-from-bracket"></i><span class="cabinet-label">Sign out</span></button>
                </form>
            @endauth
        </nav>
        <main class="wrap">
            @if(session('status'))<div class="panel" style="margin-bottom:15px;color:var(--brand)">{{ session('status') }}</div>@endif
            {{ $slot }}
        </main>
    </div>
</x-layouts.base>
