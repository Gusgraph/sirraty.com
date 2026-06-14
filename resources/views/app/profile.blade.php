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
        $hashtagText = app(\App\Services\HashtagService::class);
        $moderationText = app(\App\Services\ModerationWordService::class);
        $hashtagRoute = auth()->check() ? 'app.tags.show' : 'tags.show';
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
                            <span class="row"><form method="POST" action="{{ route('app.unfollow', $user) }}">@csrf @method('DELETE') <button class="btn" type="submit"><i class="fas fa-user-minus"></i> Unfollow</button></form><x-report-action type="profile" :id="$user->id" /></span>
                        @else
                            <span class="row"><form method="POST" action="{{ route('app.follow', $user) }}">@csrf <button class="btn primary" type="submit"><i class="fas fa-user-plus"></i> Follow</button></form><x-report-action type="profile" :id="$user->id" /></span>
                        @endif
                    @endauth
                </div>
                <div class="metric-row">
                    <span class="metric"><strong>{{ $user->followers_count }}</strong> followers</span>
                    <span class="metric"><strong>{{ $user->following_count }}</strong> following</span>
                    <span class="metric">{{ ucfirst($profile->visibility ?? 'public') }}</span>
                    @if($profile?->location_name)<span class="metric"><i class="fas fa-map-marker-alt"></i> {{ $profile->location_name }}</span>@endif
                    @if($profile?->country)<span class="metric">{{ $profile->country->name }}</span>@endif
                </div>
            </div>
        </div>
    </section>

    <div class="grid two" style="margin-top:19px">
        <section class="grid">
            <div class="panel side-card">
                <h2 class="section-title">About</h2>
                @if($profile?->bio)
                    <p style="white-space:pre-wrap">{{ $moderationText->censor($profile->bio) }}</p>
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
                    <div class="feed-post-grid">
                        <a class="post-avatar" href="{{ route('profile.show', $post->user) }}">
                            @if($post->user->profile?->avatar_url)
                                <img src="{{ $post->user->profile->avatar_url }}" alt="">
                            @else
                                <span>{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                            @endif
                        </a>
                        <div class="post-main">
                            <div class="post-meta-row">
                                <div class="post-meta-copy">
                                    <a class="post-author" href="{{ route('profile.show', $post->user) }}">{{ $post->user->profile->display_name ?? $post->user->name }}</a>
                                    <a class="muted" href="{{ route('profile.show', $post->user) }}">{{ '@'.$post->user->username }}</a>
                                    <span class="muted">{{ optional($post->published_at)->diffForHumans() }}</span>
                                </div>
                                @auth
                                    <details class="post-menu">
                                        <summary aria-label="Post actions"><i class="fas fa-ellipsis"></i></summary>
                                        <div class="post-menu-panel">
                                            <form method="POST" action="{{ route('app.posts.hide', $post) }}">
                                                @csrf
                                                <button type="submit"><i class="far fa-eye-slash"></i> Hide</button>
                                            </form>
                                            <x-report-action type="post" :id="$post->id" />
                                            @if($post->user_id === auth()->id())
                                                <details class="post-edit-cabinet">
                                                    <summary><i class="far fa-edit"></i> Edit</summary>
                                                    <form method="POST" action="{{ route('app.posts.update', $post) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <label class="field">Post <textarea name="body" rows="5" maxlength="5000">{{ old('body', $post->body) }}</textarea></label>
                                                        <label class="field">Visibility
                                                            <select name="visibility">
                                                                @foreach(['public' => 'Public', 'followers' => 'Followers', 'only_me' => 'Only me', 'group_only' => 'Group only', 'page_admin_only' => 'Page admin only'] as $value => $label)
                                                                    <option value="{{ $value }}" @selected($post->visibility === $value)>{{ $label }}</option>
                                                                @endforeach
                                                            </select>
                                                        </label>
                                                        <button type="submit"><i class="far fa-save"></i> Save</button>
                                                    </form>
                                                </details>
                                            @endif
                                            @if($post->user_id === auth()->id() || auth()->user()->isModerator())
                                                <form method="POST" action="{{ route('app.posts.destroy', $post) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"><i class="far fa-trash-alt"></i> Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </details>
                                @endauth
                            </div>
                            <div class="post-copy-line">
                                <div class="post-copy">
                                    @php($postIcons = collect($post->icon_classes ?? array_filter([$post->icon_class])))
                                    @if($postIcons->isNotEmpty())
                                        <span class="post-icon-group">
                                            @foreach($postIcons as $postIcon)
                                                <span class="post-icon"><i class="{{ $postIcon }}"></i></span>
                                            @endforeach
                                        </span>
                                    @endif
                                    <p style="white-space:pre-wrap;margin:0">{!! $hashtagText->render($post->body, $hashtagRoute) !!}</p>
                                </div>
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
                        </div>
                    </div>
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
