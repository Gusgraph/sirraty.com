{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/app/interest.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Interest | Sirraty">
    <div class="grid two">
        <section class="grid">
            <form class="panel composer-panel" method="POST" action="{{ route('app.posts.store') }}">
                @csrf
                <h1 class="section-title composer-icon" aria-label="Interest">
                    <svg class="quill-icon" viewBox="0 0 64 64" aria-hidden="true">
                        <path d="M51 7c-13 3-23 11-31 23-5 7-7 15-7 23 8 0 16-2 23-7 12-8 20-18 23-31" />
                        <path d="M51 7c2 7 1 13-3 19-5 9-14 17-27 24" />
                        <path d="M17 47c9-11 17-19 31-31" />
                        <path d="M13 53l13-5" />
                    </svg>
                </h1>
                <label class="field"><textarea name="body" rows="5" maxlength="5000" required aria-label="Post body"></textarea></label>
                <div class="row">
                    <select name="visibility" aria-label="Visibility">
                        <option value="public">Public</option>
                        <option value="followers">Followers</option>
                        <option value="only_me">Only me</option>
                        <option value="group_only">Group only</option>
                        <option value="page_admin_only">Page admin only</option>
                    </select>
                    <button class="btn primary" type="submit"><i class="fa-solid fa-paper-plane"></i> Post</button>
                </div>
            </form>
            <div class="row">
                <a class="btn {{ $scope === 'all' ? 'primary' : '' }}" href="{{ route('app.interest') }}">All</a>
                <a class="btn {{ $scope === 'following' ? 'primary' : '' }}" href="{{ route('app.interest', ['scope' => 'following']) }}">Following</a>
            </div>
            @forelse($posts as $post)
                <article class="panel feed-post">
                    <div class="row" style="justify-content:space-between">
                        <a class="brand" href="{{ route('profile.show', $post->user) }}"><span class="brand-mark">{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>{{ $post->user->name }}</a>
                        <span class="muted">{{ ucfirst(str_replace('_', ' ', $post->visibility)) }}</span>
                    </div>
                    <p style="white-space:pre-wrap">{{ $post->body }}</p>
                    <div class="row muted"><span><i class="fa-regular fa-comment"></i> {{ $post->comments_count ?? $post->comments()->count() }}</span><span><i class="fa-regular fa-heart"></i> 0</span><span><i class="fa-regular fa-bookmark"></i> Save</span></div>
                </article>
            @empty
                <div class="empty">No posts are available for this feed yet.</div>
            @endforelse
            {{ $posts->links() }}
        </section>
        <aside class="grid">
            <div class="panel side-card"><h2 class="section-title">Privacy</h2><p class="muted">Post visibility is checked before items appear in Interest.</p><a class="btn" href="{{ route('app.privacy') }}">Manage</a></div>
            <div class="panel side-card"><h2 class="section-title">Recap</h2><p class="muted">Recent activity across your network.</p><a class="btn" href="{{ route('app.recap') }}">Open</a></div>
        </aside>
    </div>
</x-layouts.app>
