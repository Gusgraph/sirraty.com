{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/auth/forgot-password.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Password help | Sirraty">
    <form class="panel" method="POST" action="{{ route('password.email') }}" style="max-width:473px;margin:auto">
        @csrf
        <h1 class="section-title">Password help</h1>
        @if(session('status'))<div class="flash-message info" role="status">{{ session('status') }}</div>@endif
        <label class="field">Email <input type="email" name="email" value="{{ old('email') }}" required></label>
        @if($errors->any())<p class="muted">{{ $errors->first() }}</p>@endif
        <button class="btn primary" type="submit">Send help</button>
    </form>
</x-layouts.app>
