{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/public/legal.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.base :title="$title.' | Sirraty'">
    <main class="wrap" style="padding:73px 0">
        <a href="{{ route('home') }}" aria-label="Sirraty home"><x-brand-logo style="font-size:3.7rem" /></a>
        <section class="panel" style="margin-top:27px">
            <h1 class="section-title">{{ $title }}</h1>
            <p class="muted">{{ $intro }}</p>
            <div class="grid" style="margin-top:19px">
                @foreach($items as $item)
                    <p style="margin:0;padding-top:11px;border-top:1px solid rgba(36,117,83,.19)">{{ $item }}</p>
                @endforeach
            </div>
        </section>
    </main>
</x-layouts.base>
