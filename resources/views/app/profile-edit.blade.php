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
    @php
        $profile = $user->profile;
        $links = implode("\n", $profile->links ?? []);
        $interests = implode(', ', $profile->interests ?? []);
    @endphp

    <form class="panel" method="POST" action="{{ route('app.profile.update') }}">
        @csrf @method('PATCH')
        <h1 class="section-title">Profile</h1>
        @if($errors->any())<div class="empty" style="margin-bottom:15px">{{ $errors->first() }}</div>@endif
        <label class="field">Display name <input name="display_name" value="{{ old('display_name', $profile->display_name ?? $user->name) }}" maxlength="73" required></label>
        <label class="field">Avatar URL <input name="avatar_url" value="{{ old('avatar_url', $profile->avatar_url ?? '') }}"></label>
        <label class="field">Cover URL <input name="cover_url" value="{{ old('cover_url', $profile->cover_url ?? '') }}"></label>
        <label class="field">Bio <textarea name="bio" rows="5" maxlength="1000">{{ old('bio', $profile->bio ?? '') }}</textarea></label>
        <label class="field">Location <input name="location_name" value="{{ old('location_name', $profile->location_name ?? '') }}" maxlength="73"></label>
        <label class="field">Links <textarea name="links" rows="5" maxlength="1000">{{ old('links', $links) }}</textarea></label>
        <label class="field">Interests <input name="interests" value="{{ old('interests', $interests) }}" maxlength="500"></label>
        <label class="field">Visibility
            <select name="visibility">
                @foreach(['public' => 'Public', 'followers' => 'Followers', 'private' => 'Private', 'hidden' => 'Hidden'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('visibility', $profile->visibility ?? 'public') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <button class="btn primary" type="submit"><i class="far fa-save"></i> Save</button>
    </form>
</x-layouts.app>
