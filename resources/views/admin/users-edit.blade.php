{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/admin/users-edit.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app :title="$userRecord->name.' | Admin Zone'">
    @php
        $profile = $userRecord->profile;
        $profileLinks = implode("\n", $profile->links ?? []);
        $profileInterests = implode(', ', $profile->interests ?? []);
    @endphp

    <div class="row" style="justify-content:space-between;margin-bottom:19px">
        <div>
            <h1 class="section-title" style="margin:0">Edit User</h1>
            <p class="muted" style="margin:7px 0 0">{{ $userRecord->email }}</p>
        </div>
        <span class="row">
            <a class="btn" href="{{ route('profile.show', $userRecord) }}"><i class="fa-regular fa-user"></i> Profile</a>
            <a class="btn" href="{{ route('admin.section', 'users') }}"><i class="fa-solid fa-arrow-left"></i> Users</a>
        </span>
    </div>

    <form class="panel" method="POST" action="{{ route('admin.users.update', $userRecord) }}">
        @csrf
        @method('PATCH')
        <h2 class="section-title">Account</h2>
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(273px,1fr));gap:19px">
            <label class="field">Name
                <input name="name" value="{{ old('name', $userRecord->name) }}" maxlength="73" required>
            </label>
            <label class="field">Username
                <input name="username" value="{{ old('username', $userRecord->username) }}" maxlength="73" required>
            </label>
            <label class="field">Email
                <input name="email" type="email" value="{{ old('email', $userRecord->email) }}" maxlength="191" required>
            </label>
            <label class="field">Phone
                <input name="phone" value="{{ old('phone', $userRecord->phone) }}" maxlength="27">
            </label>
            <label class="field">Role
                <select name="role" required>
                    @foreach(['member' => 'Member', 'moderator' => 'Moderator', 'admin' => 'Admin', 'owner' => 'Owner'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('role', $userRecord->role) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field">Status
                <select name="status" required>
                    @foreach(['active' => 'Active', 'limited' => 'Limited', 'suspended' => 'Suspended', 'banned' => 'Banned'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $userRecord->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field">Email Verification
                <select name="email_verified" required>
                    <option value="1" @selected((string) old('email_verified', $userRecord->email_verified_at ? '1' : '0') === '1')>Verified</option>
                    <option value="0" @selected((string) old('email_verified', $userRecord->email_verified_at ? '1' : '0') === '0')>Not verified</option>
                </select>
            </label>
            <label class="field">New Password
                <input name="password" type="password" autocomplete="new-password" minlength="11">
            </label>
            <label class="field">Confirm Password
                <input name="password_confirmation" type="password" autocomplete="new-password" minlength="11">
            </label>
        </div>

        <h2 class="section-title" style="margin-top:27px">Profile</h2>
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(273px,1fr));gap:19px">
            <label class="field">Display Name
                <input name="profile_display_name" value="{{ old('profile_display_name', $profile->display_name ?? $userRecord->name) }}" maxlength="73" required>
            </label>
            <label class="field">Avatar URL
                <input name="profile_avatar_url" type="url" value="{{ old('profile_avatar_url', $profile->avatar_url ?? '') }}" maxlength="255">
            </label>
            <label class="field">Cover URL
                <input name="profile_cover_url" type="url" value="{{ old('profile_cover_url', $profile->cover_url ?? '') }}" maxlength="255">
            </label>
            <label class="field">Location
                <input name="profile_location_name" value="{{ old('profile_location_name', $profile->location_name ?? '') }}" maxlength="73">
            </label>
            <label class="field">Country
                <select name="profile_country_id">
                    <option value="">Select country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" @selected((string) old('profile_country_id', $profile->country_id ?? '') === (string) $country->id)>{{ $country->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field">Visibility
                <select name="profile_visibility" required>
                    @foreach(['public' => 'Public', 'followers' => 'Followers', 'private' => 'Private', 'hidden' => 'Hidden'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('profile_visibility', $profile->visibility ?? 'public') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field">Interests
                <input name="profile_interests" value="{{ old('profile_interests', $profileInterests) }}" maxlength="500">
            </label>
            <label class="field">Bio
                <textarea name="profile_bio" rows="5" maxlength="1000">{{ old('profile_bio', $profile->bio ?? '') }}</textarea>
            </label>
            <label class="field">Links
                <textarea name="profile_links" rows="5" maxlength="1000">{{ old('profile_links', $profileLinks) }}</textarea>
            </label>
        </div>
        @if($errors->any())
            <div class="flash-message error">
                <span>{{ $errors->first() }}</span>
            </div>
        @endif
        <div class="row" style="justify-content:flex-end;margin-top:19px">
            <button class="btn primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save</button>
        </div>
    </form>
</x-layouts.app>
