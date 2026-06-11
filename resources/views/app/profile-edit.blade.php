{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/app/profile-edit.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Profile settings | Sirraty">
    <form class="panel" method="POST" action="{{ route('app.profile.update') }}">
        @csrf @method('PATCH')
        <h1 class="section-title">Profile</h1>
        <label class="field">Display name <input name="display_name" value="{{ old('display_name', $user->profile->display_name ?? $user->name) }}" maxlength="73" required></label>
        <label class="field">Bio <textarea name="bio" rows="5">{{ old('bio', $user->profile->bio ?? '') }}</textarea></label>
        <label class="field">Location <input name="location_name" value="{{ old('location_name', $user->profile->location_name ?? '') }}" maxlength="73"></label>
        <label class="field">Visibility <select name="visibility"><option value="public">Public</option><option value="followers">Followers</option><option value="private">Private</option><option value="hidden">Hidden</option></select></label>
        <button class="btn primary" type="submit">Save</button>
    </form>
</x-layouts.app>
