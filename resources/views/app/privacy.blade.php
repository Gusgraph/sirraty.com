{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/app/privacy.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Privacy | Sirraty">
    <form class="panel" method="POST" action="{{ route('app.privacy.update') }}">
        @csrf @method('PATCH')
        <h1 class="section-title">Privacy</h1>
        @php($visibility = ['public' => 'Public', 'followers' => 'Followers', 'private' => 'Private', 'hidden' => 'Hidden'])
        @foreach(['profile_visibility','post_default_visibility','followers_visibility','following_visibility','location_visibility','tagging_permission','mention_permission','comment_permission','page_visibility','group_visibility'] as $field)
            <label class="field">{{ ucwords(str_replace('_', ' ', $field)) }} <select name="{{ $field }}">@foreach($visibility as $value => $label)<option value="{{ $value }}" @selected($settings->$field === $value)>{{ $label }}</option>@endforeach</select></label>
        @endforeach
        @foreach(['messaging_permission','market_contact_permission'] as $field)
            <label class="field">{{ ucwords(str_replace('_', ' ', $field)) }} <select name="{{ $field }}"><option value="everyone">Everyone</option><option value="followers" @selected($settings->$field === 'followers')>Followers only</option><option value="following" @selected($settings->$field === 'following')>People I follow</option><option value="no_one" @selected($settings->$field === 'no_one')>No one</option></select></label>
        @endforeach
        <label class="field">Search visibility <select name="search_visibility"><option value="1" @selected($settings->search_visibility)>Allow</option><option value="0" @selected(! $settings->search_visibility)>Do not allow</option></select></label>
        <button class="btn primary" type="submit">Save privacy</button>
    </form>
</x-layouts.app>
