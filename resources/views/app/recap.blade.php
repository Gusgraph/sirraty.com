{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/app/recap.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Recap | Sirraty">
    <h1 class="section-title">Recap</h1>
    <div class="grid two">
        <section class="grid">
            <div class="panel"><strong>{{ $recentPosts->count() }}</strong><p class="muted">Recent profile activity</p></div>
            <div class="panel"><strong>{{ $followCount }}</strong><p class="muted">Followed activity sources</p></div>
            <div class="panel"><strong>{{ $reports->count() }}</strong><p class="muted">Report status updates</p></div>
        </section>
        <aside class="panel"><p class="muted">Group, page, market, and moderation recap streams will populate as records are created.</p></aside>
    </div>
</x-layouts.app>
