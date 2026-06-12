{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/app/hashtag.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app :title="'#'.$hashtag->name.' | Sirraty'">
    @php($hashtagText = app(\App\Services\HashtagService::class))
    <div class="grid two interest-layout">
        <section class="grid">
            <div class="panel side-card">
                <h1 class="section-title" style="margin:0">#{{ $hashtag->name }}</h1>
                <div class="metric-row" style="margin-top:11px">
                    <span class="metric">{{ number_format($hashtag->usage_count) }} posts</span>
                    @if($hashtag->geo_city)<span class="metric"><i class="fas fa-map-marker-alt"></i> {{ $hashtag->geo_city }}</span>@endif
                    @if($hashtag->last_used_at)<span class="metric">Active {{ $hashtag->last_used_at->diffForHumans() }}</span>@endif
                </div>
            </div>

            @forelse($posts as $post)
                <article class="panel feed-post">
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
                            </div>
                            <div class="post-copy-line">
                                <div class="post-copy">
                                    <p style="white-space:pre-wrap;margin:0">{!! $hashtagText->render($post->body) !!}</p>
                                </div>
                            </div>
                            @if($post->media->isNotEmpty())
                                <div class="post-media-grid">
                                    @foreach($post->media as $media)
                                        @if($media->media_type === 'image')<img src="{{ $media->secure_url }}" alt="">@endif
                                    @endforeach
                                </div>
                            @endif
                            <div class="post-actions">
                                <form method="POST" action="{{ route('app.posts.react', $post) }}" data-post-ajax="react">@csrf <input type="hidden" name="type" value="like"><button class="{{ $post->liked_by_viewer ? 'is-active' : '' }}" type="submit" data-like-button><i class="{{ $post->liked_by_viewer ? 'fas' : 'far' }} fa-heart"></i> <span data-like-count>{{ $post->likes_count }}</span></button></form>
                                <form method="POST" action="{{ route('app.posts.react', $post) }}" data-post-ajax="react">@csrf <input type="hidden" name="type" value="dislike"><button class="{{ $post->disliked_by_viewer ? 'is-active' : '' }}" type="submit" data-dislike-button><i class="{{ $post->disliked_by_viewer ? 'fas' : 'far' }} fa-thumbs-down"></i> <span data-dislike-count>{{ $post->dislikes_count }}</span></button></form>
                                <form method="POST" action="{{ route('app.posts.save', $post) }}" data-post-ajax="save">@csrf <button class="{{ $post->saved_by_viewer ? 'is-active' : '' }}" type="submit" data-save-button><i class="{{ $post->saved_by_viewer ? 'fas' : 'far' }} fa-bookmark"></i> <span>{{ $post->saved_by_viewer ? 'Saved' : 'Save' }}</span></button></form>
                                <x-report-action type="post" :id="$post->id" />
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty">No visible posts for this tag yet.</div>
            @endforelse
            {{ $posts->links() }}
        </section>

        <aside class="grid interest-sidebar">
            <div class="panel side-card">
                <h2 class="section-title">Top Tags</h2>
                <div class="tag-rank">
                    @forelse($rankedTags as $rankedTag)
                        <a href="{{ route('app.tags.show', $rankedTag) }}"><span>#{{ $rankedTag->name }}</span><span class="muted">{{ number_format($rankedTag->usage_count) }}</span></a>
                    @empty
                        <p class="muted">No ranked tags yet.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
</x-layouts.app>
