{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/auth/verify-email.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Confirm email | Sirraty">
    <section class="panel" style="max-width:473px;margin:auto">
        <h1 class="section-title">Confirm email</h1>
        <p class="muted">Use the confirmation link sent to your email.</p>
        <form method="POST" action="{{ route('verification.send') }}" class="row">
            @csrf
            <button class="btn primary" type="submit">Send again</button>
        </form>
    </section>
</x-layouts.app>
