{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/auth/login.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Sign in | Sirraty">
    <div class="grid two">
        <section>
            <h1 style="font-size:clamp(3rem,9vw,7.3rem);margin:0;line-height:.91">Sign in</h1>
            <p class="muted" style="font-size:1.3rem">Return to Interest, Recap, messages, and privacy controls.</p>
        </section>
        <form class="panel" method="POST" action="{{ route('login.store') }}">
            @csrf
            <label class="field">Email <input type="email" name="email" value="{{ old('email') }}" required></label>
            <label class="field">Password <input type="password" name="password" required></label>
            <label class="row"><input type="checkbox" name="remember" value="1"> Remember me</label>
            @if($errors->any())<p class="muted">{{ $errors->first() }}</p>@endif
            <div class="row"><button class="btn primary" type="submit">Sign in</button><a class="btn link" href="{{ route('password.request') }}">Password help</a></div>
        </form>
    </div>
</x-layouts.app>
