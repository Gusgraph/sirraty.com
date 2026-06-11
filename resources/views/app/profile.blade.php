{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/app/profile.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app :title="$user->name.' | Sirraty'">
    <section class="panel">
        <div class="row" style="justify-content:space-between">
            <div class="brand"><span class="brand-mark">{{ strtoupper(substr($user->name, 0, 1)) }}</span><h1 class="section-title" style="margin:0">{{ $user->profile->display_name ?? $user->name }}</h1></div>
            @auth
                @if(auth()->id() !== $user->id)
                    <form method="POST" action="{{ route('app.follow', $user) }}">@csrf <button class="btn primary" type="submit">Follow</button></form>
                @else
                    <a class="btn" href="{{ route('app.profile.edit') }}">Edit profile</a>
                @endif
            @endauth
        </div>
        <p class="muted">{{ $user->profile->bio ?? 'No bio added.' }}</p>
        <div class="row muted"><span>{{ $user->followers_count }} followers</span><span>{{ $user->following_count }} following</span><span>{{ ucfirst($user->profile->visibility ?? 'public') }}</span></div>
    </section>
    <section class="grid" style="margin-top:19px">
        @forelse($posts as $post)<article class="panel"><p>{{ $post->body }}</p></article>@empty<div class="empty">No visible posts yet.</div>@endforelse
    </section>
</x-layouts.app>
