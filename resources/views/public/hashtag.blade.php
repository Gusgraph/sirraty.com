{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/public/hashtag.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.base :title="'#'.$hashtag->name.' | Sirraty'">
    @php($hashtagText = app(\App\Services\HashtagService::class))
    <style>
        .public-tag { padding: 47px 0; }
        .public-tag .hashtag-link { color: var(--brand); font-weight: 700; }
        .public-tag .public-post-grid { display: grid; grid-template-columns: 43px minmax(0, 1fr); gap: 11px; align-items: start; }
        .public-tag .post-avatar { display: grid; place-items: center; width: 43px; height: 43px; overflow: hidden; border-radius: 999px; background: rgba(22, 199, 101, .07); color: var(--brand); font-weight: 800; }
        .public-tag .post-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .public-tag .post-meta-row { display: flex; align-items: baseline; gap: 7px; min-width: 0; white-space: nowrap; }
        .public-tag .post-meta-row > * { min-width: 0; overflow: hidden; text-overflow: ellipsis; }
        .public-tag .post-author { color: var(--text); font-weight: 800; }
        .public-tag .post-copy { margin-top: 7px; }
        .public-tag .post-copy p { margin: 0; }
        .public-tag .tag-rank { display: grid; gap: 7px; counter-reset: tag-rank; }
        .public-tag .tag-rank a { counter-increment: tag-rank; display: flex; align-items: center; justify-content: space-between; gap: 11px; padding: 9px 0; border-top: 1px solid var(--line); }
        .public-tag .tag-rank a::before { content: counter(tag-rank); display: grid; place-items: center; width: 27px; height: 27px; border-radius: 999px; background: var(--soft); color: var(--brand); font-size: .79rem; font-weight: 800; }
    </style>
    <main class="wrap public-tag">
        <div class="grid two">
            <section class="grid">
                <div class="panel">
                    <h1 class="section-title" style="margin:0">#{{ $hashtag->name }}</h1>
                    <p class="muted" style="margin:11px 0 0">{{ number_format($hashtag->usage_count) }} public conversations on Sirraty</p>
                    <div class="row" style="margin-top:15px">
                        <a class="btn primary" href="{{ route('login') }}">Sign in</a>
                        <a class="btn" href="{{ route('register') }}">Create account</a>
                    </div>
                </div>

                @forelse($posts as $post)
                    <article class="panel">
                        <div class="public-post-grid">
                            <span class="post-avatar">
                                @if($post->user->profile?->avatar_url)
                                    <img src="{{ $post->user->profile->avatar_url }}" alt="">
                                @else
                                    <span>{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                                @endif
                            </span>
                            <div>
                                <div class="post-meta-row">
                                    <strong class="post-author">{{ $post->user->profile->display_name ?? $post->user->name }}</strong>
                                    <span class="muted">{{ '@'.$post->user->username }}</span>
                                    <span class="muted">{{ optional($post->published_at)->diffForHumans() }}</span>
                                </div>
                                <div class="post-copy">
                                    <p style="white-space:pre-wrap">{!! $hashtagText->render($post->body, 'tags.show') !!}</p>
                                </div>
                            </div>
                        </div>
                        @if($post->media->isNotEmpty())
                            <div class="row">
                                @foreach($post->media->take(3) as $media)
                                    @if($media->media_type === 'image')<img src="{{ $media->secure_url }}" alt="" style="width:min(100%, 317px);aspect-ratio:1.31;object-fit:cover;border-radius:7px">@endif
                                @endforeach
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="empty">No public posts for this tag yet.</div>
                @endforelse
            </section>

            <aside class="grid">
                <div class="panel">
                    <h2 class="section-title">Top Tags</h2>
                    <div class="tag-rank">
                        @forelse($rankedTags as $rankedTag)
                            <a href="{{ route('tags.show', $rankedTag) }}"><span>#{{ $rankedTag->name }}</span><span class="muted">{{ number_format($rankedTag->usage_count) }}</span></a>
                        @empty
                            <p class="muted">No ranked tags yet.</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </main>
</x-layouts.base>
