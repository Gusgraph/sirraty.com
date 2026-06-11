{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/auth/first-admin.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="First admin | Sirraty">
    <form class="panel" method="POST" action="{{ route('setup.admin.store') }}" style="max-width:573px;margin:auto">
        @csrf
        <h1 class="section-title">First admin</h1>
        <label class="field">Setup password <span class="password-control"><input id="setup-password" type="password" name="setup_password" required><button type="button" data-password-toggle="setup-password" aria-label="Show password"><i class="fa-regular fa-eye"></i></button></span></label>
        <label class="field">Name <input name="name" value="{{ old('name') }}" required maxlength="73"></label>
        <label class="field">Username <input name="username" value="{{ old('username') }}" required maxlength="73"></label>
        <label class="field">Email <input type="email" name="email" value="{{ old('email') }}" required></label>
        <label class="field">Password <span class="password-control"><input id="first-admin-password" type="password" name="password" required><button type="button" data-password-toggle="first-admin-password" aria-label="Show password"><i class="fa-regular fa-eye"></i></button></span></label>
        <label class="field">Confirm password <span class="password-control"><input id="first-admin-password-confirmation" type="password" name="password_confirmation" required><button type="button" data-password-toggle="first-admin-password-confirmation" aria-label="Show password"><i class="fa-regular fa-eye"></i></button></span></label>
        @if($errors->any())<p class="muted">{{ $errors->first() }}</p>@endif
        <button class="btn primary" type="submit">Create admin</button>
    </form>
</x-layouts.app>
