{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/admin/pages.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Pages | Admin Zone">
    <style>
        .admin-page-stats { display:grid;grid-template-columns:repeat(auto-fit,minmax(137px,1fr));gap:7px;margin-bottom:15px }
        .admin-page-stat { padding:9px 11px;border-top:1px solid rgba(22,199,101,.19);border-radius:7px;background:rgba(255,253,247,.03) }
        .admin-page-stat strong { display:block;font-size:1.17rem;line-height:1 }
        .admin-page-filters { display:grid;grid-template-columns:minmax(191px,1fr) 157px 173px 173px auto auto;gap:7px;align-items:end;margin-bottom:15px }
        .admin-page-table { display:grid;gap:5px }
        .admin-page-row { display:grid;grid-template-columns:39px minmax(213px,1.4fr) 137px 103px 117px 97px 97px 157px;gap:7px;align-items:center;padding:7px 9px;font-size:.83rem }
        .admin-page-row.is-head { font-size:.73rem;text-transform:uppercase;color:var(--muted);background:transparent;border-color:rgba(22,199,101,.19) }
        .admin-page-thumb { width:39px;height:39px;display:grid;place-items:center;border-radius:7px;background:rgba(22,199,101,.07);color:var(--brand);overflow:hidden }
        .admin-page-thumb img { width:100%;height:100%;object-fit:cover;display:block }
        .admin-page-title { display:grid;gap:3px;min-width:0 }
        .admin-page-title strong,.admin-page-title span { overflow:hidden;text-overflow:ellipsis;white-space:nowrap }
        .admin-page-actions { display:flex;justify-content:flex-end;gap:5px }
        .admin-page-actions .btn { min-height:31px;padding:5px 9px;font-size:.77rem }
        @media (max-width:1050px) { .admin-page-filters,.admin-page-row { grid-template-columns:1fr } .admin-page-row.is-head { display:none } .admin-page-actions { justify-content:flex-start } }
    </style>

    <div class="row" style="justify-content:space-between;margin-bottom:19px">
        <div>
            <h1 class="section-title" style="margin:0">Pages</h1>
            <p class="muted" style="margin:7px 0 0">Compact page management, ownership, visibility, SEO preview, and settings.</p>
        </div>
        <a class="btn" href="{{ route('admin.dashboard') }}">Dashboard</a>
    </div>

    <section class="admin-page-stats">
        <div class="admin-page-stat"><strong>{{ number_format($records->total()) }}</strong><span class="muted">Shown by filter</span></div>
        <div class="admin-page-stat"><strong>{{ number_format($visibilityCounts['public'] ?? 0) }}</strong><span class="muted">Public</span></div>
        <div class="admin-page-stat"><strong>{{ number_format($visibilityCounts['hidden'] ?? 0) }}</strong><span class="muted">Hidden</span></div>
        <div class="admin-page-stat"><strong>{{ number_format($approvalCount) }}</strong><span class="muted">Approval on</span></div>
    </section>

    <form class="panel admin-page-filters" method="GET" action="{{ route('admin.section', 'pages') }}">
        <label class="field">Search
            <input name="q" value="{{ $filters['search'] }}" autocomplete="off" placeholder="Name, slug, owner, bio">
        </label>
        <label class="field">Visibility
            <select name="visibility">
                <option value="">All</option>
                @foreach(['public' => 'Public', 'followers' => 'Followers', 'private' => 'Private', 'hidden' => 'Hidden'] as $value => $label)
                    <option value="{{ $value }}" @selected($filters['visibility'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label class="field">Category
            <select name="category_id">
                <option value="">All categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) $filters['categoryId'] === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </label>
        <label class="field">Country
            <select name="country_id">
                <option value="">All countries</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" @selected((string) $filters['countryId'] === (string) $country->id)>{{ $country->name }}</option>
                @endforeach
            </select>
        </label>
        <button class="btn primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
        <a class="btn" href="{{ route('admin.section', 'pages') }}">Clear</a>
    </form>

    <section class="admin-page-table">
        <div class="panel admin-page-row is-head">
            <span></span><span>Page</span><span>Owner</span><span>Category</span><span>Location</span><span>Visibility</span><span>Activity</span><span></span>
        </div>
        @forelse($records as $page)
            <article class="panel admin-page-row">
                <span class="admin-page-thumb">
                    @if($page->avatar_url || $page->cover_url)
                        <img src="{{ $page->avatar_url ?: $page->cover_url }}" alt="">
                    @else
                        <i class="fa-regular fa-file-lines"></i>
                    @endif
                </span>
                <div class="admin-page-title">
                    <strong>{{ $page->name }}</strong>
                    <span class="muted">/{{ $page->slug }}</span>
                </div>
                <span class="muted">{{ $page->owner?->profile?->display_name ?? $page->owner?->name ?? 'No owner' }}</span>
                <span class="muted">{{ $page->category?->name ?? 'None' }}</span>
                <span class="muted">{{ collect([$page->city?->name ?? $page->address_city, $page->state?->name ?? $page->address_region, $page->country?->code ?? $page->address_country])->filter()->implode(', ') ?: 'No location' }}</span>
                <span class="metric">{{ ucfirst($page->visibility) }}</span>
                <span class="muted">{{ number_format($page->followers_count) }} followers · {{ number_format($page->posts_count) }} posts</span>
                <span class="admin-page-actions">
                    <a class="btn" href="{{ route('public.pages.show', $page->slug) }}"><i class="fa-solid fa-up-right-from-square"></i></a>
                    <a class="btn primary" href="{{ route('admin.pages.edit', $page) }}"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                </span>
            </article>
        @empty
            <div class="empty">No pages match this filter.</div>
        @endforelse
    </section>

    {{ $records->links() }}
</x-layouts.app>
