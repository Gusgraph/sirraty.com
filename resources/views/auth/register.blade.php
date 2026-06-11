{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/auth/register.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Signup | Sirraty">
    <div class="grid two">
        <section>
            <h1 style="font-size:clamp(3rem,9vw,7.3rem);margin:0;line-height:.91">Signup</h1>
            <p class="muted" style="font-size:1.3rem">Start with profile and privacy controls ready.</p>
        </section>
        <form class="panel" method="POST" action="{{ route('register.store') }}">
            @csrf
            <label class="field">Name <input name="name" value="{{ old('name') }}" required maxlength="73"></label>
            <label class="field">Username <input name="username" value="{{ old('username') }}" required maxlength="73"></label>
            <label class="field">Email <input type="email" name="email" value="{{ old('email') }}" required></label>
            <label class="field">Password <input type="password" name="password" required></label>
            <label class="field">Confirm password <input type="password" name="password_confirmation" required></label>
            @if($errors->any())<p class="muted">{{ $errors->first() }}</p>@endif
            <button class="btn primary" type="submit">Create account</button>
        </form>
    </div>
</x-layouts.app>
