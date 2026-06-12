{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/app/module.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app :title="$config['title'].' | Sirraty'">
    @php
        $filterQuery = collect(request()->only(['q', 'country_id', 'state_id', 'city_id', 'parent_category_id', 'category_id']))
            ->filter(fn ($value) => filled($value))
            ->all();
    @endphp
    <div class="row module-topbar">
        <div>
            <h1 class="section-title" style="margin:0">{{ $config['title'] }}</h1>
            <p class="muted" style="margin:7px 0 0">{{ method_exists($records, 'total') ? $records->total() : 0 }} listed</p>
        </div>
        @if(in_array($module, ['pages', 'groups', 'market'], true))
            <span class="module-actions">
                @if(request()->boolean('mine'))
                    <a class="btn module-filter-toggle" href="{{ route('app.module', array_merge([$module], $filterQuery)) }}"><i class="fa-solid fa-layer-group"></i> All Items</a>
                @else
                    <a class="btn module-filter-toggle" href="{{ route('app.module', array_merge([$module], $filterQuery, ['mine' => 1])) }}"><i class="fa-regular fa-user"></i> My Items</a>
                @endif
                <a class="btn primary" href="{{ route('app.modules.create', $module) }}"><i class="fa-solid fa-plus"></i> Create</a>
                <form class="module-filter-form" method="GET" action="{{ route('app.module', $module) }}">
                    @if(request()->boolean('mine'))<input type="hidden" name="mine" value="1">@endif
                    <label class="module-search-field">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input name="q" type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" value="{{ request('q') }}" placeholder="Search" aria-label="Search {{ strtolower($config['title']) }}">
                    </label>
                    <label class="search-select module-filter-select" data-search-select>
                        <span class="sr-only">Country</span>
                        <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Country" aria-label="Search countries" data-search-select-input>
                        <select name="country_id" onchange="this.form.submit()" aria-label="Filter by country" data-search-select-menu data-geo-role="country">
                            <option value="">Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @selected((string) request('country_id') === (string) $country->id)>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="search-select module-filter-select" data-search-select>
                        <span class="sr-only">State</span>
                        <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="State" aria-label="Search states" data-search-select-input>
                        <select name="state_id" onchange="this.form.submit()" aria-label="Filter by state" data-search-select-menu data-search-url="{{ route('app.options', 'states') }}" data-geo-role="state">
                            <option value="">State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" data-country-id="{{ $state->country_id }}" @selected((string) request('state_id') === (string) $state->id)>{{ $state->name }}{{ $state->country ? ', '.$state->country->code : '' }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="search-select module-filter-select" data-search-select>
                        <span class="sr-only">City</span>
                        <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="City" aria-label="Search cities" data-search-select-input>
                        <select name="city_id" onchange="this.form.submit()" aria-label="Filter by city" data-search-select-menu data-search-url="{{ route('app.options', 'cities') }}" data-geo-role="city">
                            <option value="">City</option>
                        @foreach($cities as $city)
                                <option value="{{ $city->id }}" data-country-id="{{ $city->country_id }}" data-state-id="{{ $city->state_id }}" @selected((string) request('city_id') === (string) $city->id)>{{ $city->name }}{{ $city->state ? ', '.$city->state->name : '' }}</option>
                        @endforeach
                        </select>
                    </label>
                    @if($module === 'market')
                        <label class="search-select module-filter-select" data-search-select>
                            <span class="sr-only">Parent category</span>
                            <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Main Category" aria-label="Search main categories" data-search-select-input>
                            <select name="parent_category_id" onchange="this.form.submit()" aria-label="Filter by main category" data-search-select-menu data-category-role="parent">
                                <option value="">Main Category</option>
                                @foreach($categories->whereNull('parent_id') as $category)
                                    <option value="{{ $category->id }}" @selected((string) request('parent_category_id') === (string) $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </label>
                    @endif
                    <label class="search-select module-filter-select" data-search-select>
                        <span class="sr-only">Category</span>
                        <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Categories" aria-label="Search categories" data-search-select-input>
                        <select name="category_id" onchange="this.form.submit()" aria-label="Filter by category" data-search-select-menu @if($module === 'market') data-category-role="child" @endif>
                            <option value="">Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-parent-id="{{ $category->parent_id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->parent ? $category->parent->name.' / ' : '' }}{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button class="btn module-filter-submit" type="submit" aria-label="Apply filters"><i class="fa-solid fa-filter"></i></button>
                    @if(request()->filled('q') || request()->filled('country_id') || request()->filled('state_id') || request()->filled('city_id') || request()->filled('parent_category_id') || request()->filled('category_id'))
                        <a class="btn module-filter-clear" href="{{ route('app.module', request()->boolean('mine') ? [$module, 'mine' => 1] : [$module]) }}" aria-label="Clear filters"><i class="fa-solid fa-xmark"></i></a>
                    @endif
                </form>
            </span>
        @endif
    </div>

    @if(method_exists($records, 'count') && $records->count())
        <div class="{{ $module === 'market' ? 'module-feed' : 'module-profile-grid' }}">
            @foreach($records as $record)
                @if($module === 'market')
                    <article class="panel module-market-item">
                        <div class="feed-post-grid">
                            <span class="post-avatar">
                                @if($record->seller?->profile?->avatar_url)
                                    <img src="{{ $record->seller->profile->avatar_url }}" alt="">
                                @else
                                    {{ strtoupper(substr($record->seller?->name ?? 'S', 0, 1)) }}
                                @endif
                            </span>
                            <div class="post-main">
                                <div class="row" style="justify-content:space-between;gap:11px">
                                    <div>
                                        <strong class="post-author">
                                            @if($record->seller)
                                                <a href="{{ route('profile.show', ['user' => $record->seller->username]) }}">{{ $record->seller->profile?->display_name ?? $record->seller->name }}</a>
                                            @else
                                                Seller
                                            @endif
                                        </strong>
                                        <p class="muted" style="margin:3px 0 0">{{ $record->created_at?->diffForHumans() }}</p>
                                    </div>
                                    @if($record->price !== null)
                                        <strong class="module-price">${{ number_format((float) $record->price, 2) }}</strong>
                                    @endif
                                    <x-report-action type="market-listing" :id="$record->id" />
                                </div>
                                <div>
                                    <h2 class="module-item-title">{{ $record->title }}</h2>
                                    <p>{{ $record->description }}</p>
                                </div>
                                @if($record->media->count())
                                    <div class="post-media-grid">
                                        @foreach($record->media as $media)
                                            <img src="{{ $media->secure_url }}" alt="">
                                        @endforeach
                                    </div>
                                @endif
                                <div class="chip-row">
                                    @if($record->marketCategory)<span class="chip">{{ $record->marketCategory->parent ? $record->marketCategory->parent->name.' / ' : '' }}{{ $record->marketCategory->name }}</span>@elseif($record->category)<span class="chip">{{ $record->category->name }}</span>@endif
                                    @if($record->city)<span class="chip">{{ $record->city->name }}</span>@elseif($record->location)<span class="chip">{{ $record->location->name }}</span>@endif
                                    <span class="chip">{{ ucfirst($record->status) }}</span>
                                </div>
                            </div>
                        </div>
                    </article>
                @elseif(in_array($module, ['pages', 'groups'], true))
                    @php
                        $groupTypeLabels = ['public' => 'Public', 'approval' => 'By Approval', 'private' => 'Private', 'hidden' => 'Hidden'];
                        $isGroupOwner = $module === 'groups' && $record->owner_id === auth()->id();
                        $isGroupMember = $module === 'groups' && $record->members->isNotEmpty();
                        $viewerJoinRequest = $module === 'groups' ? $record->joinRequests->firstWhere('user_id', auth()->id()) : null;
                    @endphp
                    <article class="panel module-profile-item">
                        <a class="module-cover module-card-link" href="{{ $module === 'pages' ? route('app.pages.show', $record) : route('app.groups.show', $record) }}" @if($record->cover_url) style="background-image:linear-gradient(117deg, rgba(23, 34, 28, .17), rgba(57, 255, 136, .11)), url('{{ $record->cover_url }}')" @endif aria-label="Open {{ $record->name }}"></a>
                        <div class="module-profile-head">
                            <a class="profile-avatar module-avatar" href="{{ $module === 'pages' ? route('app.pages.show', $record) : route('app.groups.show', $record) }}" aria-label="Open {{ $record->name }}">
                                @if($record->avatar_url)
                                    <img src="{{ $record->avatar_url }}" alt="">
                                @else
                                    {{ strtoupper(substr($record->name, 0, 1)) }}
                                @endif
                            </a>
                            <div class="module-profile-copy">
                                <h2 class="module-item-title"><a href="{{ $module === 'pages' ? route('app.pages.show', $record) : route('app.groups.show', $record) }}">{{ $record->name }}</a></h2>
                                <p class="muted">{{ $record->owner?->profile?->display_name ?? $record->owner?->name ?? 'Owner' }} · {{ $record->created_at?->diffForHumans() }}</p>
                            </div>
                        </div>
                        @if($record->description)<p>{{ $record->description }}</p>@endif
                        <div class="chip-row">
                            @if($record->category)<span class="chip">{{ $record->category->name }}</span>@endif
                            @if($record->city)<span class="chip">{{ $record->city->name }}</span>@elseif($record->location)<span class="chip">{{ $record->location->name }}</span>@endif
                            @if(! $record->city && $record->address_city)<span class="chip">{{ $record->address_city }}</span>@endif
                            @if($record->address_postal_code)<span class="chip">{{ $record->address_postal_code }}</span>@endif
                            @if($record->address_country)<span class="chip">{{ Locale::getDisplayRegion('-'.$record->address_country, 'en') ?: $record->address_country }}</span>@endif
                            <span class="chip">{{ $module === 'groups' ? ($groupTypeLabels[$record->type] ?? ucfirst($record->type)) : ucfirst($record->visibility) }}</span>
                            <span class="chip">{{ $module === 'pages' ? $record->followers_count.' followers' : $record->members_count.' members' }}</span>
                            @if($module === 'groups' && $isGroupOwner && $record->pending_join_requests_count)
                                <span class="chip">{{ $record->pending_join_requests_count }} pending</span>
                            @endif
                            <x-report-action :type="$module === 'pages' ? 'page' : 'group'" :id="$record->id" />
                        </div>
                        @if($module === 'groups')
                            <div class="post-actions" style="margin-top:15px">
                                @if($isGroupOwner)
                                    <span class="muted">Owner</span>
                                @elseif($isGroupMember)
                                    <span class="muted">Joined</span>
                                @elseif($viewerJoinRequest)
                                    <span class="muted">Request sent</span>
                                @else
                                    <form method="POST" action="{{ route('app.groups.join-requests.store', $record) }}">
                                        @csrf
                                        <button type="submit"><i class="fa-solid fa-user-plus"></i> {{ $record->type === 'public' ? 'Join' : 'Request joining' }}</button>
                                    </form>
                                @endif
                            </div>
                            @if($isGroupOwner && $record->joinRequests->count())
                                <div class="comment-panel-static">
                                    @foreach($record->joinRequests as $joinRequest)
                                        <div class="row" style="justify-content:space-between;gap:11px">
                                            <span>{{ $joinRequest->user?->profile?->display_name ?? $joinRequest->user?->name ?? 'Member' }}</span>
                                            <span class="row">
                                                <form method="POST" action="{{ route('app.groups.join-requests.approve', [$record, $joinRequest]) }}">
                                                    @csrf
                                                    <button class="btn" type="submit">Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('app.groups.join-requests.dismiss', [$record, $joinRequest]) }}">
                                                    @csrf
                                                    <button class="btn" type="submit">Dismiss</button>
                                                </form>
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </article>
                @else
                    <article class="panel">
                        <strong>{{ $record->name ?? $record->title ?? $record->reason ?? $record->status ?? 'Record #'.$record->id }}</strong>
                        <p class="muted">{{ $record->created_at?->diffForHumans() }}</p>
                    </article>
                @endif
            @endforeach
        </div>
        {{ $records->links() }}
    @else
        <div class="empty">No {{ strtolower($config['title']) }} records yet.</div>
    @endif
</x-layouts.app>
