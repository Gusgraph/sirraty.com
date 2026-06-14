{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/public/pages.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.base :title="$title.' | Sirraty'">
    <main class="wrap" style="padding:73px 0">
        <div class="row" style="justify-content:space-between;margin-bottom:27px">
            <a href="{{ route('home') }}" aria-label="Sirraty home"><x-brand-logo style="font-size:3.7rem" /></a>
            <a class="btn" href="{{ route('login') }}">Sign in</a>
        </div>
        <section class="panel">
            <h1 class="section-title">{{ $title }}</h1>
            <p class="muted">{{ $intro }}</p>
        </section>
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(273px,1fr));margin-top:19px">
            @forelse($pages as $page)
                <article class="panel">
                    <h2 class="section-title" style="margin-bottom:7px">{{ $page->name }}</h2>
                    <p class="muted" style="margin:0 0 11px">{{ $page->category?->name ?? 'Page' }} · {{ number_format($page->followers_count) }} followers</p>
                    <p>{{ Str::limit($page->description, 173) }}</p>
                    <a class="btn" href="{{ route('login') }}">Sign in to continue</a>
                </article>
            @empty
                <div class="empty">No public business pages yet.</div>
            @endforelse
        </div>
        {{ $pages->links() }}
    </main>
</x-layouts.base>
