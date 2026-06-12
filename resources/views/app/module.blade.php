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
    <div class="row module-topbar">
        <div>
            <h1 class="section-title" style="margin:0">{{ $config['title'] }}</h1>
            <p class="muted" style="margin:7px 0 0">{{ method_exists($records, 'total') ? $records->total() : 0 }} listed</p>
        </div>
        @if(in_array($module, ['pages', 'groups', 'market'], true))
            <a class="btn primary" href="{{ route('app.modules.create', $module) }}"><i class="fa-solid fa-plus"></i> Create</a>
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
                                    @if($record->category)<span class="chip">{{ $record->category->name }}</span>@endif
                                    @if($record->location)<span class="chip">{{ $record->location->name }}</span>@endif
                                    <span class="chip">{{ ucfirst($record->status) }}</span>
                                </div>
                            </div>
                        </div>
                    </article>
                @elseif(in_array($module, ['pages', 'groups'], true))
                    <article class="panel module-profile-item">
                        <div class="module-cover" @if($record->cover_url) style="background-image:linear-gradient(117deg, rgba(23, 34, 28, .17), rgba(57, 255, 136, .11)), url('{{ $record->cover_url }}')" @endif></div>
                        <div class="module-profile-head">
                            <span class="profile-avatar module-avatar">
                                @if($record->avatar_url)
                                    <img src="{{ $record->avatar_url }}" alt="">
                                @else
                                    {{ strtoupper(substr($record->name, 0, 1)) }}
                                @endif
                            </span>
                            <div class="module-profile-copy">
                                <h2 class="module-item-title">{{ $record->name }}</h2>
                                <p class="muted">{{ $record->owner?->profile?->display_name ?? $record->owner?->name ?? 'Owner' }} · {{ $record->created_at?->diffForHumans() }}</p>
                            </div>
                        </div>
                        @if($record->description)<p>{{ $record->description }}</p>@endif
                        <div class="chip-row">
                            @if($record->category)<span class="chip">{{ $record->category->name }}</span>@endif
                            @if($record->location)<span class="chip">{{ $record->location->name }}</span>@endif
                            @if($record->address_city)<span class="chip">{{ $record->address_city }}</span>@endif
                            @if($record->address_country)<span class="chip">{{ Locale::getDisplayRegion('-'.$record->address_country, 'en') ?: $record->address_country }}</span>@endif
                            <span class="chip">{{ ucfirst($record->visibility ?? $record->type) }}</span>
                            <span class="chip">{{ $module === 'pages' ? $record->followers_count.' followers' : $record->members_count.' members' }}</span>
                        </div>
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
