{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/public/seo-profile.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.base :title="$title.' | Sirraty'">
    @push('meta')
        <meta name="description" content="{{ $description }}">
        <meta name="robots" content="index,follow,max-image-preview:large">
        <meta property="og:title" content="{{ $title }} | Sirraty">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:type" content="profile">
        <meta property="og:url" content="{{ $canonical }}">
        @if($image)<meta property="og:image" content="{{ $image }}">@endif
        <meta name="twitter:card" content="{{ $image ? 'summary_large_image' : 'summary' }}">
        <link rel="canonical" href="{{ $canonical }}">
        @if($location)
            <meta name="geo.placename" content="{{ $location }}">
        @endif
        <meta name="keywords" content="{{ collect(['Sirraty', $title, $category, $location, $type === 'page' ? 'Muslim page' : 'Muslim group', 'Halal Social'])->filter()->implode(', ') }}">
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endpush

    <main class="wrap" style="padding:73px 0">
        <div class="row" style="justify-content:space-between;margin-bottom:27px">
            <a href="{{ route('home') }}" aria-label="Sirraty home"><x-brand-logo style="font-size:3.7rem" /></a>
            <a class="btn primary" href="{{ route('login') }}">Sign in</a>
        </div>
        <section class="panel">
            @if($record->cover_url)
                <div style="height:173px;margin:-19px -19px 19px;border-radius:7px 7px 0 0;background:linear-gradient(117deg,rgba(23,34,28,.19),rgba(23,34,28,.07)),url('{{ $record->cover_url }}') center/cover"></div>
            @endif
            <div class="row" style="align-items:flex-start;gap:19px">
                @if($record->avatar_url)
                    <img src="{{ $record->avatar_url }}" alt="{{ $title }}" style="width:97px;height:97px;border-radius:7px;object-fit:cover">
                @endif
                <div style="min-width:0">
                    <h1 class="section-title" style="margin-bottom:7px">{{ $title }}</h1>
                    <p class="muted" style="margin:0 0 11px">{{ ucfirst($type) }} · {{ $category }}@if($location) · {{ $location }}@endif · {{ number_format($count) }} {{ $countLabel }}</p>
                    <p style="white-space:pre-wrap">{{ $description }}</p>
                    <div class="row" style="margin-top:19px">
                        <a class="btn primary" href="{{ route('login') }}">Sign in to continue</a>
                        <a class="btn" href="{{ route('register') }}">Create account</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts.base>
