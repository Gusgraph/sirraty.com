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
    @php
        $emojis = ['🙂', '🤲', '✨', '🌙', '⭐', '🕌', '📖', '🕊', '💚', '🤍', '☕', '🌿', '🎁', '📍', '📷'];
        $icons = [
            'fas fa-address-book', 'fas fa-address-card', 'fas fa-adjust', 'fas fa-align-center', 'fas fa-align-left', 'fas fa-archive', 'fas fa-archway', 'fas fa-arrow-circle-right', 'fas fa-at', 'fas fa-award',
            'fas fa-baby', 'fas fa-balance-scale', 'fas fa-ban', 'fas fa-bars', 'fas fa-bell', 'far fa-bell', 'fas fa-bicycle', 'fas fa-bolt', 'fas fa-book', 'fas fa-book-open',
            'fas fa-bookmark', 'far fa-bookmark', 'fas fa-briefcase', 'fas fa-bullhorn', 'fas fa-calendar', 'far fa-calendar', 'fas fa-camera', 'fas fa-car', 'fas fa-chart-line', 'fas fa-check',
            'fas fa-check-circle', 'far fa-check-circle', 'fas fa-child', 'fas fa-city', 'fas fa-clock', 'far fa-clock', 'fas fa-cloud', 'fas fa-code', 'fas fa-coffee', 'fas fa-cog',
            'fas fa-comment', 'far fa-comment', 'fas fa-comments', 'far fa-comments', 'fas fa-compass', 'far fa-compass', 'fas fa-copy', 'far fa-copy', 'fas fa-crown', 'fas fa-dove',
            'fas fa-edit', 'far fa-edit', 'fas fa-envelope', 'far fa-envelope', 'fas fa-exclamation-circle', 'fas fa-eye', 'far fa-eye', 'fas fa-feather', 'fas fa-feather-alt', 'fas fa-file',
            'far fa-file', 'fas fa-filter', 'fas fa-fire', 'fas fa-flag', 'far fa-flag', 'fas fa-gem', 'far fa-gem', 'fas fa-gift', 'fas fa-globe', 'fas fa-hand-holding-heart',
            'fas fa-hands-helping', 'fas fa-hands-praying', 'fas fa-hashtag', 'fas fa-heart', 'far fa-heart', 'fas fa-home', 'fas fa-image', 'far fa-image', 'fas fa-info-circle', 'fas fa-key',
            'fas fa-landmark', 'fas fa-leaf', 'fas fa-life-ring', 'far fa-life-ring', 'fas fa-lightbulb', 'far fa-lightbulb', 'fas fa-link', 'fas fa-list', 'fas fa-location-arrow', 'fas fa-lock',
            'fas fa-map', 'far fa-map', 'fas fa-map-marker-alt', 'fas fa-medal', 'fas fa-microphone', 'fas fa-moon', 'far fa-moon', 'fas fa-mosque', 'fas fa-music', 'fas fa-paper-plane',
            'far fa-paper-plane', 'fas fa-pen', 'fas fa-pencil-alt', 'fas fa-phone', 'fas fa-photo-video', 'fas fa-pray', 'fas fa-quran', 'fas fa-recycle', 'fas fa-reply', 'fas fa-rocket',
            'fas fa-route', 'fas fa-save', 'far fa-save', 'fas fa-search', 'fas fa-seedling', 'fas fa-share', 'fas fa-shield-alt', 'fas fa-shopping-bag', 'fas fa-smile', 'far fa-smile',
            'fas fa-star', 'far fa-star', 'fas fa-store', 'fas fa-sun', 'far fa-sun', 'fas fa-tag', 'fas fa-thumbs-up', 'far fa-thumbs-up', 'fas fa-times', 'fas fa-trash',
            'fas fa-tree', 'fas fa-trophy', 'fas fa-unlock', 'fas fa-upload', 'fas fa-user', 'far fa-user', 'fas fa-user-check', 'fas fa-user-friends', 'fas fa-users', 'fas fa-video',
            'fab fa-facebook', 'fab fa-instagram', 'fab fa-linkedin', 'fab fa-telegram', 'fab fa-tiktok', 'fab fa-whatsapp', 'fab fa-x-twitter', 'fab fa-youtube',
        ];
    @endphp
    <div class="grid two">
        <section class="grid">
            <form class="panel composer-panel" method="POST" action="{{ route('app.posts.store') }}" enctype="multipart/form-data">
                @csrf
                <h1 class="section-title composer-icon" aria-label="Interest">
                    <svg class="quill-icon" viewBox="0 0 64 64" aria-hidden="true">
                        <path d="M51 7c-13 3-23 11-31 23-5 7-7 15-7 23 8 0 16-2 23-7 12-8 20-18 23-31" />
                        <path d="M51 7c2 7 1 13-3 19-5 9-14 17-27 24" />
                        <path d="M17 47c9-11 17-19 31-31" />
                        <path d="M13 53l13-5" />
                    </svg>
                </h1>
                @if($errors->any())
                    <div class="empty" style="margin-bottom:15px">{{ $errors->first() }}</div>
                @endif
                <input type="hidden" name="icon_class" value="{{ old('icon_class') }}" data-icon-value>
                <label class="field"><textarea name="body" rows="5" maxlength="5000" required aria-label="Post body" data-post-body>{{ old('body') }}</textarea></label>
                <div class="row composer-actions">
                    <label class="media-button"><i class="fas fa-image"></i> Image<input type="file" name="media[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple data-media-input></label>
                    <details class="composer-tools">
                        <summary class="btn" aria-label="Choose icon"><i class="fas fa-icons"></i></summary>
                        <div class="picker-panel">
                            <div class="emoji-row">
                                @foreach($emojis as $emoji)
                                    <button class="emoji-button" type="button" data-insert-emoji="{{ $emoji }}">{{ $emoji }}</button>
                                @endforeach
                            </div>
                            <label class="field icon-search"><input type="search" placeholder="Search" aria-label="Search icons" data-icon-search></label>
                            <div class="icon-grid">
                                @foreach($icons as $icon)
                                    <button class="icon-button {{ old('icon_class') === $icon ? 'is-selected' : '' }}" type="button" data-icon-class="{{ $icon }}" title="{{ str_replace(['fas fa-', 'far fa-', 'fab fa-'], '', $icon) }}"><i class="{{ $icon }}"></i></button>
                                @endforeach
                            </div>
                        </div>
                    </details>
                    <span class="btn selected-icon" data-selected-icon aria-label="Selected icon">
                        @if(old('icon_class'))<i class="{{ old('icon_class') }}"></i>@else<i class="far fa-star"></i>@endif
                    </span>
                    <select name="visibility" aria-label="Visibility">
                        <option value="public">Public</option>
                        <option value="followers">Followers</option>
                        <option value="only_me">Only me</option>
                        <option value="group_only">Group only</option>
                        <option value="page_admin_only">Page admin only</option>
                    </select>
                    <button class="btn primary" type="submit"><i class="fa-solid fa-paper-plane"></i> Post</button>
                </div>
                <div class="media-preview" data-media-preview></div>
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
    @push('scripts')
        <script>
            document.querySelectorAll('[data-insert-emoji]').forEach((button) => {
                button.addEventListener('click', () => {
                    const body = document.querySelector('[data-post-body]');
                    if (! body) return;
                    const start = body.selectionStart;
                    const end = body.selectionEnd;
                    const text = button.dataset.insertEmoji;
                    body.value = `${body.value.slice(0, start)}${text}${body.value.slice(end)}`;
                    body.focus();
                    body.selectionStart = body.selectionEnd = start + text.length;
                });
            });

            document.querySelectorAll('[data-icon-class]').forEach((button) => {
                button.addEventListener('click', () => {
                    const value = document.querySelector('[data-icon-value]');
                    const selected = document.querySelector('[data-selected-icon]');
                    if (! value || ! selected) return;
                    value.value = button.dataset.iconClass;
                    selected.innerHTML = `<i class="${button.dataset.iconClass}"></i>`;
                    document.querySelectorAll('[data-icon-class]').forEach((item) => item.classList.remove('is-selected'));
                    button.classList.add('is-selected');
                });
            });

            document.querySelectorAll('[data-icon-search]').forEach((input) => {
                input.addEventListener('input', () => {
                    const term = input.value.toLowerCase().trim();
                    input.closest('.picker-panel').querySelectorAll('[data-icon-class]').forEach((button) => {
                        button.hidden = ! button.dataset.iconClass.toLowerCase().includes(term);
                    });
                });
            });

            document.querySelectorAll('[data-media-input]').forEach((input) => {
                input.addEventListener('change', () => {
                    const preview = document.querySelector('[data-media-preview]');
                    if (! preview) return;
                    const names = Array.from(input.files).slice(0, 4).map((file) => file.name);
                    preview.textContent = names.length ? names.join(' | ') : '';
                });
            });
        </script>
    @endpush
</x-layouts.app>
