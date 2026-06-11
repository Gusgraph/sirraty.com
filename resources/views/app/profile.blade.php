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
<x-layouts.app :title="($user->profile->display_name ?? $user->name).' | Sirraty'">
    @php
        $profile = $user->profile;
        $display = $profile->display_name ?? $user->name;
        $links = collect($profile->links ?? []);
        $interests = collect($profile->interests ?? []);
    @endphp

    <section class="panel">
        <div class="profile-cover" @if($profile?->cover_url) style="background-image:linear-gradient(117deg, rgba(23,34,28,.17), rgba(23,34,28,.07)), url('{{ $profile->cover_url }}')" @endif></div>
        <div class="profile-head">
            <div class="profile-avatar">
                @if($profile?->avatar_url)
                    <img src="{{ $profile->avatar_url }}" alt="">
                @else
                    <span>{{ strtoupper(substr($display, 0, 1)) }}</span>
                @endif
            </div>
            <div class="profile-title">
                <div class="row" style="justify-content:space-between">
                    <div>
                        <h1 class="section-title" style="margin:0">{{ $display }}</h1>
                        <div class="muted">{{ '@'.$user->username }}</div>
                    </div>
                    @auth
                        @if(auth()->id() === $user->id)
                            <a class="btn" href="{{ route('app.profile.edit') }}"><i class="far fa-edit"></i> Edit</a>
                        @elseif($isFollowing)
                            <form method="POST" action="{{ route('app.unfollow', $user) }}">@csrf @method('DELETE') <button class="btn" type="submit"><i class="fas fa-user-minus"></i> Unfollow</button></form>
                        @else
                            <form method="POST" action="{{ route('app.follow', $user) }}">@csrf <button class="btn primary" type="submit"><i class="fas fa-user-plus"></i> Follow</button></form>
                        @endif
                    @endauth
                </div>
                <div class="metric-row">
                    <span class="metric"><strong>{{ $user->followers_count }}</strong> followers</span>
                    <span class="metric"><strong>{{ $user->following_count }}</strong> following</span>
                    <span class="metric">{{ ucfirst($profile->visibility ?? 'public') }}</span>
                    @if($profile?->location_name)<span class="metric"><i class="fas fa-map-marker-alt"></i> {{ $profile->location_name }}</span>@endif
                </div>
            </div>
        </div>
    </section>

    <div class="grid two" style="margin-top:19px">
        <section class="grid">
            <div class="panel side-card">
                <h2 class="section-title">About</h2>
                @if($profile?->bio)
                    <p style="white-space:pre-wrap">{{ $profile->bio }}</p>
                @else
                    <p class="muted">No bio added.</p>
                @endif
                @if($interests->isNotEmpty())
                    <div class="chip-row">
                        @foreach($interests as $interest)
                            <span class="chip">{{ $interest }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            @forelse($posts as $post)
                <article class="panel profile-post">
                    <div class="row" style="justify-content:space-between">
                        <span class="muted">{{ optional($post->published_at)->diffForHumans() }}</span>
                        <span class="muted">{{ ucfirst(str_replace('_', ' ', $post->visibility)) }}</span>
                    </div>
                    <div class="row" style="align-items:flex-start">
                        @if($post->icon_class)
                            <span class="post-icon"><i class="{{ $post->icon_class }}"></i></span>
                        @endif
                        <p style="white-space:pre-wrap;margin:0">{{ $post->body }}</p>
                    </div>
                    @if($post->media->isNotEmpty())
                        <div class="post-media-grid">
                            @foreach($post->media as $media)
                                @if($media->media_type === 'image')
                                    <img src="{{ $media->secure_url }}" alt="">
                                @endif
                            @endforeach
                        </div>
                    @endif
                </article>
            @empty
                <div class="empty">No visible posts yet.</div>
            @endforelse
            {{ $posts->links() }}
        </section>

        <aside class="grid">
            <div class="panel side-card">
                <h2 class="section-title">Links</h2>
                @forelse($links as $link)
                    <p><a class="muted" href="{{ $link }}" rel="nofollow noopener" target="_blank">{{ parse_url($link, PHP_URL_HOST) ?? $link }}</a></p>
                @empty
                    <p class="muted">No links added.</p>
                @endforelse
            </div>
            <div class="panel side-card">
                <h2 class="section-title">Profile</h2>
                <div class="grid">
                    <span class="muted"><i class="fas fa-calendar"></i> Joined {{ $user->created_at->format('M Y') }}</span>
                    <span class="muted"><i class="fas fa-shield-alt"></i> {{ ucfirst($user->status) }}</span>
                    @if($user->email_verified_at)<span class="muted"><i class="fas fa-check-circle"></i> Email confirmed</span>@endif
                </div>
            </div>
        </aside>
    </div>
</x-layouts.app>
