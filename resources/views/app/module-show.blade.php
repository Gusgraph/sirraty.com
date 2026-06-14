{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/app/module-show.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app :title="$title.' | Sirraty'">
    @php
        $groupTypeLabels = ['public' => 'Public', 'approval' => 'By Approval', 'private' => 'Private', 'hidden' => 'Hidden'];
        $isGroupOwner = $module === 'groups' && $record->owner_id === auth()->id();
        $isGroupMember = $module === 'groups' && $record->members->isNotEmpty();
        $viewerJoinRequest = $module === 'groups' ? $record->joinRequests->firstWhere('user_id', auth()->id()) : null;
        $canPostInGroup = $module === 'groups' && ($isGroupOwner || $isGroupMember);
        $isPageOwner = $module === 'pages' && $record->owner_id === auth()->id();
        $canPostInPage = $module === 'pages' && ($isPageOwner || $record->visibility === 'public');
        $canPost = $canPostInGroup || $canPostInPage;
        $postStoreRoute = $module === 'pages' ? route('app.pages.posts.store', $record) : route('app.groups.posts.store', $record);
        $approvalStatus = $module === 'pages' ? 'page_review' : 'group_review';
        $postContextLabel = $module === 'pages' ? 'Page post' : 'Group post';
        $emojis = config('sirraty_icons.emojis');
        $iconCategories = config('sirraty_icons.categories');
        $oldIcons = collect(old('icon_classes', []))->filter()->values();
        $hashtagText = app(\App\Services\HashtagService::class);
        $moderationText = app(\App\Services\ModerationWordService::class);
        $viewerFollowingIds = auth()->user()->following()->pluck('followed_id')->all();
    @endphp

    <div class="row module-topbar">
        <a class="btn" href="{{ route('app.module', $module) }}"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>

    <section class="panel">
        <div class="profile-cover" @if($record->cover_url) style="background-image:linear-gradient(117deg, rgba(23,34,28,.17), rgba(23,34,28,.07)), url('{{ $record->cover_url }}')" @endif></div>
        <div class="profile-head">
            <div class="profile-avatar">
                @if($record->avatar_url)
                    <img src="{{ $record->avatar_url }}" alt="">
                @else
                    <span>{{ strtoupper(substr($record->name, 0, 1)) }}</span>
                @endif
            </div>
            <div class="profile-title">
                <div class="row" style="justify-content:space-between">
                    <div>
                        <h1 class="section-title" style="margin:0">{{ $record->name }}</h1>
                        <div class="muted">{{ $module === 'pages' ? 'Page' : 'Group' }} · {{ $record->owner?->profile?->display_name ?? $record->owner?->name ?? 'Owner' }}</div>
                    </div>
                    @if($module === 'pages' && $record->owner_id === auth()->id())
                        <a class="btn" href="{{ route('app.pages.edit', $record) }}"><i class="far fa-edit"></i> Edit</a>
                    @elseif($module === 'pages')
                        <x-report-action type="page" :id="$record->id" />
                    @elseif($module === 'groups')
                        @if($isGroupOwner)
                            <span class="row">
                                <a class="btn" href="{{ route('app.groups.edit', $record) }}"><i class="far fa-edit"></i> Edit</a>
                                <span class="btn">Owner</span>
                            </span>
                        @elseif($isGroupMember)
                            <span class="row"><span class="btn">Joined</span><x-report-action type="group" :id="$record->id" /></span>
                        @elseif($viewerJoinRequest)
                            <span class="row"><span class="btn">Request sent</span><x-report-action type="group" :id="$record->id" /></span>
                        @else
                            <span class="row">
                                <form method="POST" action="{{ route('app.groups.join-requests.store', $record) }}">
                                    @csrf
                                    <button class="btn primary" type="submit"><i class="fa-solid fa-user-plus"></i> {{ $record->type === 'public' ? 'Join' : 'Request joining' }}</button>
                                </form>
                                <x-report-action type="group" :id="$record->id" />
                            </span>
                        @endif
                    @endif
                </div>
                <div class="metric-row">
                    @if($record->category)<span class="metric">{{ $record->category->name }}</span>@endif
                    @if($record->address_city)<span class="metric"><i class="fas fa-map-marker-alt"></i> {{ $record->address_city }}</span>@endif
                    @if($record->address_country)<span class="metric">{{ Locale::getDisplayRegion('-'.$record->address_country, 'en') ?: $record->address_country }}</span>@endif
                    <span class="metric">{{ $module === 'pages' ? ucfirst($record->visibility) : ($groupTypeLabels[$record->type] ?? ucfirst($record->type)) }}</span>
                    <span class="metric">{{ $module === 'pages' ? $record->followers_count.' followers' : $record->members_count.' members' }}</span>
                </div>
            </div>
        </div>
    </section>

    <div class="grid two" style="margin-top:19px">
        <section class="grid">
            @if($errors->any())<div class="empty">{{ $errors->first() }}</div>@endif
            <div class="panel side-card">
                <h2 class="section-title">About</h2>
                @if($record->description)
                    <p style="white-space:pre-wrap">{{ $moderationText->censor($record->description) }}</p>
                @else
                    <p class="muted">No description added.</p>
                @endif
            </div>
            @if($canPost)
                <form class="panel composer-panel" method="POST" action="{{ $postStoreRoute }}" enctype="multipart/form-data">
                    @csrf
                    <h2 class="section-title composer-icon" aria-label="Post">
                        <svg class="quill-icon" viewBox="0 0 64 64" aria-hidden="true">
                            <path d="M51 7c-13 3-23 11-31 23-5 7-7 15-7 23 8 0 16-2 23-7 12-8 20-18 23-31" />
                            <path d="M51 7c2 7 1 13-3 19-5 9-14 17-27 24" />
                            <path d="M17 47c9-11 17-19 31-31" />
                            <path d="M13 53l13-5" />
                        </svg>
                    </h2>
                    <div data-icon-values>
                        @foreach($oldIcons as $oldIcon)
                            <input type="hidden" name="icon_classes[]" value="{{ $oldIcon }}">
                        @endforeach
                    </div>
                    <label class="field field-with-icons">
                        <span class="field-icon-preview" data-selected-icon aria-hidden="true">
                            @foreach($oldIcons as $oldIcon)<i class="{{ $oldIcon }}"></i>@endforeach
                        </span>
                        <textarea name="body" rows="5" maxlength="5000" aria-label="{{ $module === 'pages' ? 'Page' : 'Group' }} post body" data-post-body>{{ old('body') }}</textarea>
                    </label>
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
                                <div class="icon-category-list">
                                    @foreach($iconCategories as $category => $icons)
                                        <section class="icon-category" data-icon-category>
                                            <h3>{{ $category }}</h3>
                                            <div class="icon-grid">
                                                @foreach($icons as $icon)
                                                    @php($iconName = str_replace(['fas fa-', 'far fa-', 'fab fa-', 'fa-solid fa-', 'fa-regular fa-', 'fa-brands fa-'], '', $icon))
                                                    <button class="icon-button {{ $oldIcons->contains($icon) ? 'is-selected' : '' }}" type="button" data-icon-class="{{ $icon }}" data-icon-name="{{ $iconName }}" data-icon-category-name="{{ strtolower($category) }}" title="{{ $category }}: {{ $iconName }}"><i class="{{ $icon }}"></i></button>
                                                @endforeach
                                            </div>
                                        </section>
                                    @endforeach
                                </div>
                            </div>
                        </details>
                        <button class="btn primary" type="submit"><i class="fa-solid fa-paper-plane"></i> Post</button>
                    </div>
                    <div class="media-preview" data-media-preview></div>
                </form>
            @endif
            @if($module === 'groups' && $isGroupOwner && $record->joinRequests->count())
                <div class="panel side-card">
                    <h2 class="section-title">Requests</h2>
                    <div class="comment-panel-static">
                        @foreach($record->joinRequests as $joinRequest)
                            <div class="row" style="justify-content:space-between;gap:11px">
                                <span>{{ $joinRequest->user?->profile?->display_name ?? $joinRequest->user?->name ?? 'Member' }}</span>
                                <span class="row">
                                    <form method="POST" action="{{ route('app.groups.join-requests.approve', [$record, $joinRequest]) }}">@csrf <button class="btn" type="submit">Approve</button></form>
                                    <form method="POST" action="{{ route('app.groups.join-requests.dismiss', [$record, $joinRequest]) }}">@csrf <button class="btn" type="submit">Dismiss</button></form>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            @if(in_array($module, ['pages', 'groups'], true) && ($isPageOwner || $isGroupOwner) && $pendingPosts->count())
                <div class="panel side-card">
                    <h2 class="section-title">Post Approvals</h2>
                    <div class="comment-panel-static">
                        @foreach($pendingPosts as $post)
                            <div class="grid">
                                <p><strong>{{ $post->user?->profile?->display_name ?? $post->user?->name }}</strong> {{ $moderationText->censor($post->body) }}</p>
                                @if($post->media->isNotEmpty())
                                    <div class="post-media-grid">
                                        @foreach($post->media as $media)
                                            @if($media->media_type === 'image')<img src="{{ $media->secure_url }}" alt="">@endif
                                        @endforeach
                                    </div>
                                @endif
                                <span class="row">
                                    <form method="POST" action="{{ $module === 'pages' ? route('app.pages.posts.approve', [$record, $post]) : route('app.groups.posts.approve', [$record, $post]) }}">@csrf <button class="btn" type="submit">Approve</button></form>
                                    <form method="POST" action="{{ $module === 'pages' ? route('app.pages.posts.dismiss', [$record, $post]) : route('app.groups.posts.dismiss', [$record, $post]) }}">@csrf <button class="btn" type="submit">Dismiss</button></form>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            @if(in_array($module, ['pages', 'groups'], true))
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
                                                        <input type="hidden" name="visibility" value="{{ $post->visibility }}">
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
                                        @if($post->body !== '')<p style="white-space:pre-wrap;margin:0">{!! $hashtagText->render($post->body) !!}</p>@endif
                                    </div>
                                </div>
                                @if($post->media->isNotEmpty())
                                    <div class="post-media-grid">
                                        @foreach($post->media as $media)
                                            @if($media->media_type === 'image')<img src="{{ $media->secure_url }}" alt="">@endif
                                        @endforeach
                                    </div>
                                @endif
                                <div class="post-actions" data-post-actions>
                                    <span class="comment-count" data-comment-count><i class="fa-regular fa-comment"></i> {{ $post->comments_count }}</span>
                                    <form method="POST" action="{{ route('app.posts.react', $post) }}" data-post-ajax="react">@csrf <input type="hidden" name="type" value="like"><button class="{{ $post->liked_by_viewer ? 'is-active' : '' }}" type="submit" data-like-button><i class="{{ $post->liked_by_viewer ? 'fas' : 'far' }} fa-heart"></i> <span data-like-count>{{ $post->likes_count }}</span></button></form>
                                    <form method="POST" action="{{ route('app.posts.react', $post) }}" data-post-ajax="react">@csrf <input type="hidden" name="type" value="dislike"><button class="{{ $post->disliked_by_viewer ? 'is-active' : '' }}" type="submit" data-dislike-button><i class="{{ $post->disliked_by_viewer ? 'fas' : 'far' }} fa-thumbs-down"></i> <span data-dislike-count>{{ $post->dislikes_count }}</span></button></form>
                                    <form method="POST" action="{{ route('app.posts.save', $post) }}" data-post-ajax="save">@csrf <button class="{{ $post->saved_by_viewer ? 'is-active' : '' }}" type="submit" data-save-button><i class="{{ $post->saved_by_viewer ? 'fas' : 'far' }} fa-bookmark"></i> <span>{{ $post->saved_by_viewer ? 'Saved' : 'Save' }}</span></button></form>
                                </div>
                                <div class="comment-thread" data-comments-list>
                                    @foreach($post->comments->where('status', 'published')->sortBy('created_at') as $comment)
                                        <div class="comment-item" data-comment-item>
                                            <a class="comment-avatar" href="{{ route('profile.show', ['user' => $comment->user->username]) }}">
                                                @if($comment->user->profile?->avatar_url)
                                                    <img src="{{ $comment->user->profile->avatar_url }}" alt="">
                                                @else
                                                    <span>{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                                                @endif
                                            </a>
                                            <div class="comment-main">
                                                <div class="comment-meta-row">
                                                    <div class="comment-identity">
                                                        <a class="comment-author" href="{{ route('profile.show', ['user' => $comment->user->username]) }}">{{ $comment->user->profile->display_name ?? $comment->user->name }}</a>
                                                        <a class="muted" href="{{ route('profile.show', ['user' => $comment->user->username]) }}">{{ '@'.$comment->user->username }}</a>
                                                        <span class="muted">{{ $comment->created_at?->diffForHumans() }}</span>
                                                    </div>
                                                    <span class="comment-meta-actions">
                                                        @if($comment->user_id !== auth()->id())
                                                            @php($isFollowingCommenter = in_array($comment->user_id, $viewerFollowingIds, true))
                                                            <form class="comment-follow-form" method="POST" action="{{ $isFollowingCommenter ? route('app.unfollow', $comment->user) : route('app.follow', $comment->user) }}" data-follow-ajax>
                                                                @csrf
                                                                @if($isFollowingCommenter) @method('DELETE') @endif
                                                                <button type="submit" class="{{ $isFollowingCommenter ? 'is-active' : '' }}" data-follow-button>{{ $isFollowingCommenter ? 'Following' : 'Follow' }}</button>
                                                            </form>
                                                        @endif
                                                        @if($comment->user_id === auth()->id())
                                                            <details class="comment-owner-tools">
                                                                <summary aria-label="Edit comment" title="Edit"><i class="fa-regular fa-pen-to-square"></i></summary>
                                                                <form method="POST" action="{{ route('app.comments.update', $comment) }}" data-comment-ajax="edit">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input name="body" value="{{ $comment->body }}" maxlength="1000" required>
                                                                    <button type="submit" aria-label="Save comment"><i class="fa-solid fa-check"></i></button>
                                                                </form>
                                                            </details>
                                                            <form class="comment-owner-action" method="POST" action="{{ route('app.comments.destroy', $comment) }}" data-comment-ajax="remove">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" aria-label="Delete comment" title="Delete"><i class="fa-regular fa-trash-can"></i></button>
                                                            </form>
                                                        @elseif($post->user_id === auth()->id())
                                                            <form class="comment-owner-action" method="POST" action="{{ route('app.comments.destroy', $comment) }}" data-comment-ajax="remove">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" aria-label="Hide comment" title="Hide"><i class="fa-regular fa-eye-slash"></i></button>
                                                            </form>
                                                        @endif
                                                        <x-report-action type="comment" :id="$comment->id" />
                                                    </span>
                                                </div>
                                                <p data-comment-body-display>{{ $moderationText->censor($comment->body) }}</p>
                                                @php($commentIcons = collect($comment->icon_classes ?? array_filter([$comment->icon_class])))
                                                @if($commentIcons->isNotEmpty())
                                                    <div class="comment-icon-strip">
                                                        @foreach($commentIcons as $commentIcon)
                                                            <i class="{{ $commentIcon }}"></i>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                @if($comment->media->isNotEmpty())
                                                    <div class="comment-media-grid">
                                                        @foreach($comment->media as $media)
                                                            @if($media->media_type === 'image')
                                                                <img src="{{ $media->secure_url }}" alt="">
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <form class="comment-form inline-comment-form" method="POST" action="{{ route('app.posts.comments.store', $post) }}" enctype="multipart/form-data" data-post-ajax="comment" data-comment-composer>
                                    @csrf
                                    <span data-comment-icon-values></span>
                                    <span class="comment-field-wrap">
                                        <span class="field-icon-preview comment-field-icons" data-comment-selected-icons aria-hidden="true"></span>
                                        <input name="body" maxlength="1000" aria-label="Comment" data-comment-body>
                                    </span>
                                    <label class="comment-tool-button" aria-label="Add image"><i class="fas fa-image"></i><input type="file" name="media[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple data-comment-media-input></label>
                                    <details class="comment-tool-picker">
                                        <summary aria-label="Add emoji or icon"><i class="fas fa-icons"></i></summary>
                                        <div class="comment-picker-panel">
                                            <div class="emoji-row">
                                                @foreach($emojis as $emoji)
                                                    <button class="emoji-button" type="button" data-comment-insert-emoji="{{ $emoji }}">{{ $emoji }}</button>
                                                @endforeach
                                            </div>
                                            <label class="field icon-search"><input type="search" placeholder="Search" aria-label="Search icons" data-comment-icon-search autocomplete="off"></label>
                                            <div class="icon-category-list">
                                                @foreach($iconCategories as $category => $icons)
                                                    <section class="icon-category" data-comment-icon-category>
                                                        <h3>{{ $category }}</h3>
                                                        <div class="icon-grid">
                                                            @foreach($icons as $icon)
                                                                @php($iconName = str_replace(['fas fa-', 'far fa-', 'fab fa-', 'fa-solid fa-', 'fa-regular fa-', 'fa-brands fa-'], '', $icon))
                                                                <button class="icon-button" type="button" data-comment-icon-class="{{ $icon }}" data-icon-name="{{ $iconName }}" data-icon-category-name="{{ strtolower($category) }}" title="{{ $category }}: {{ $iconName }}"><i class="{{ $icon }}"></i></button>
                                                            @endforeach
                                                        </div>
                                                    </section>
                                                @endforeach
                                            </div>
                                        </div>
                                    </details>
                                    <button type="submit"><i class="fas fa-paper-plane"></i></button>
                                    <span class="comment-media-preview" data-comment-media-preview></span>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="empty">No {{ $module === 'pages' ? 'page' : 'group' }} posts yet.</div>
                @endforelse
                {{ $posts->links() }}
            @endif
        </section>

        <aside class="grid">
            <div class="panel side-card">
                <h2 class="section-title">Details</h2>
                <div class="grid">
                    @if($record->address_line)<span class="muted">{{ $record->address_line }}</span>@endif
                    @if($record->address_region)<span class="muted">{{ $record->address_region }}</span>@endif
                    @if($record->address_postal_code)<span class="muted">{{ $record->address_postal_code }}</span>@endif
                    <span class="muted"><i class="fas fa-calendar"></i> Created {{ $record->created_at->format('M Y') }}</span>
                </div>
            </div>
        </aside>
    </div>
    @if($module === 'groups')
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

                const renderSelectedIcons = () => {
                    const selected = document.querySelector('[data-selected-icon]');
                    const holder = document.querySelector('[data-icon-values]');
                    if (! selected || ! holder) return;
                    const values = Array.from(holder.querySelectorAll('input')).map((input) => input.value);
                    selected.innerHTML = values.map((icon) => `<i class="${icon}"></i>`).join('');
                };

                document.querySelectorAll('[data-icon-class]').forEach((button) => {
                    button.addEventListener('click', () => {
                        const holder = document.querySelector('[data-icon-values]');
                        if (! holder) return;
                        const current = Array.from(holder.querySelectorAll('input')).map((input) => input.value);
                        const icon = button.dataset.iconClass;
                        const exists = current.includes(icon);
                        holder.innerHTML = current
                            .filter((value) => value !== icon)
                            .concat(exists || current.length >= 11 ? [] : [icon])
                            .map((value) => `<input type="hidden" name="icon_classes[]" value="${value}">`)
                            .join('');
                        button.classList.toggle('is-selected', ! exists && current.length < 11);
                        renderSelectedIcons();
                    });
                });

                document.querySelectorAll('[data-icon-search]').forEach((input) => {
                    input.addEventListener('input', () => {
                        const term = input.value.toLowerCase().trim();
                        input.closest('.picker-panel').querySelectorAll('[data-icon-category]').forEach((category) => {
                            let visible = false;
                            category.querySelectorAll('[data-icon-class]').forEach((button) => {
                                const haystack = `${button.dataset.iconClass} ${button.dataset.iconName} ${button.dataset.iconCategoryName}`.toLowerCase();
                                button.hidden = term !== '' && ! haystack.includes(term);
                                visible = visible || ! button.hidden;
                            });
                            category.hidden = ! visible;
                        });
                    });
                });

                renderSelectedIcons();

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
    @endif
    @push('scripts')
        <script>
            document.querySelectorAll('[data-comment-composer]').forEach((composer) => {
                const body = composer.querySelector('[data-comment-body]');
                const holder = composer.querySelector('[data-comment-icon-values]');
                const selected = composer.querySelector('[data-comment-selected-icons]');
                const renderIcons = () => {
                    const values = Array.from(holder?.querySelectorAll('input') || []).map((input) => input.value);
                    if (selected) selected.innerHTML = values.map((icon) => `<i class="${icon}"></i>`).join('');
                };

                composer.querySelectorAll('[data-comment-insert-emoji]').forEach((button) => {
                    button.addEventListener('click', () => {
                        if (! body) return;
                        const start = body.selectionStart;
                        const end = body.selectionEnd;
                        const text = button.dataset.commentInsertEmoji;
                        body.value = `${body.value.slice(0, start)}${text}${body.value.slice(end)}`;
                        body.focus();
                        body.selectionStart = body.selectionEnd = start + text.length;
                    });
                });

                composer.querySelectorAll('[data-comment-icon-class]').forEach((button) => {
                    button.addEventListener('click', () => {
                        if (! holder) return;
                        const current = Array.from(holder.querySelectorAll('input')).map((input) => input.value);
                        const icon = button.dataset.commentIconClass;
                        const exists = current.includes(icon);
                        holder.innerHTML = current
                            .filter((value) => value !== icon)
                            .concat(exists || current.length >= 11 ? [] : [icon])
                            .map((value) => `<input type="hidden" name="icon_classes[]" value="${value}">`)
                            .join('');
                        button.classList.toggle('is-selected', ! exists && current.length < 11);
                        renderIcons();
                    });
                });

                composer.querySelectorAll('[data-comment-icon-search]').forEach((input) => {
                    input.addEventListener('input', () => {
                        const term = input.value.toLowerCase().trim();
                        composer.querySelectorAll('[data-comment-icon-category]').forEach((category) => {
                            let visible = false;
                            category.querySelectorAll('[data-comment-icon-class]').forEach((button) => {
                                const haystack = `${button.dataset.commentIconClass} ${button.dataset.iconName} ${button.dataset.iconCategoryName}`.toLowerCase();
                                button.hidden = term !== '' && ! haystack.includes(term);
                                visible = visible || ! button.hidden;
                            });
                            category.hidden = ! visible;
                        });
                    });
                });

                composer.querySelectorAll('[data-comment-media-input]').forEach((input) => {
                    input.addEventListener('change', () => {
                        const preview = composer.querySelector('[data-comment-media-preview]');
                        if (! preview) return;
                        const names = Array.from(input.files).slice(0, 3).map((file) => file.name);
                        preview.textContent = names.length ? names.join(' | ') : '';
                    });
                });
            });
        </script>
    @endpush
</x-layouts.app>
