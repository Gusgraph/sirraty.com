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
    <div class="shell">
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
