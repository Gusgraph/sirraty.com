{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/admin/dashboard.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Admin Zone | Sirraty">
    <style>
        .admin-stat-card { display:flex;align-items:center;gap:13px;min-height:91px }
        .admin-stat-icon { width:39px;height:39px;display:grid;place-items:center;flex:0 0 auto;border-radius:7px;background:rgba(22,199,101,.09);color:var(--brand);box-shadow:inset 0 0 0 1px rgba(22,199,101,.19) }
        .admin-stat-card strong { display:block;font-size:1.7rem;line-height:1 }
        .admin-stat-card p { margin:5px 0 0 }
    </style>
    <h1 class="section-title">Admin Zone</h1>
    <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(173px,1fr));margin-bottom:19px">
        @php($links = ['Users' => 'users', 'Posts' => 'posts', 'Comments' => 'comments', 'Pages' => 'pages', 'Groups' => 'groups', 'Market listings' => 'market-listings', 'Reports' => 'reports', 'Moderation cases' => 'moderation-queue', 'Word filters' => 'word-filters', 'Locations' => 'locations', 'Categories' => 'categories'])
        @php($icons = ['Users' => 'fa-users', 'Posts' => 'fa-feather-pointed', 'Comments' => 'fa-comments', 'Pages' => 'fa-file-lines', 'Groups' => 'fa-user-group', 'Market listings' => 'fa-store', 'Reports' => 'fa-flag', 'Moderation cases' => 'fa-shield-halved', 'Word filters' => 'fa-filter', 'Locations' => 'fa-location-dot', 'Categories' => 'fa-layer-group', 'Mailing' => 'fa-envelope-open-text', 'Visitors' => 'fa-chart-line'])
        @foreach($counts as $label => $count)
            <a class="panel admin-stat-card" href="{{ $label === 'Mailing' ? route('admin.mailing') : ($label === 'Visitors' ? route('admin.visitors') : route('admin.section', $links[$label])) }}">
                <span class="admin-stat-icon"><i class="fa-solid {{ $icons[$label] ?? 'fa-square-poll-vertical' }}"></i></span>
                <span>
                    <strong>{{ $count }}</strong>
                    <p class="muted">{{ $label }}</p>
                </span>
            </a>
        @endforeach
    </div>
    <section class="panel" style="margin-bottom:19px">
        <h2 class="section-title" style="margin-bottom:7px">Country Users</h2>
        <div class="tag-rank" style="max-height:273px;overflow:auto;padding-right:7px">
            @forelse($countryUserCounts as $country)
                <span style="display:grid;grid-template-columns:minmax(0,1fr) 47px auto;gap:7px;align-items:center;padding:3px 0;border-top:1px solid rgba(22,199,101,.19);font-size:.79rem">
                    <strong style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $country->name }}</strong>
                    <span class="muted">{{ $country->code }}</span>
                    <span>{{ number_format($country->total) }}</span>
                </span>
            @empty
                <p class="muted">No country data yet.</p>
            @endforelse
        </div>
    </section>
    <section class="panel" style="margin-bottom:19px">
        <div class="row" style="justify-content:space-between;margin-bottom:7px">
            <h2 class="section-title" style="margin:0">SES Feedback</h2>
            <a class="btn" href="{{ route('admin.mailing') }}" style="padding:5px 9px;font-size:.79rem">Mailing</a>
        </div>
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(137px,1fr));gap:11px">
            <div style="border-top:1px solid rgba(30,185,197,.27);padding-top:7px">
                <small class="muted">Events</small>
                <strong style="display:block">{{ number_format($sesFeedback['events']) }}</strong>
            </div>
            <div style="border-top:1px solid rgba(30,185,197,.27);padding-top:7px">
                <small class="muted">Bounced</small>
                <strong style="display:block">{{ number_format($sesFeedback['bounced']) }}</strong>
            </div>
            <div style="border-top:1px solid rgba(30,185,197,.27);padding-top:7px">
                <small class="muted">Complained</small>
                <strong style="display:block">{{ number_format($sesFeedback['complained']) }}</strong>
            </div>
            <div style="border-top:1px solid rgba(30,185,197,.27);padding-top:7px">
                <small class="muted">Suppressed</small>
                <strong style="display:block">{{ number_format($sesFeedback['suppressed']) }}</strong>
            </div>
        </div>
    </section>
    <section class="panel">
        <h2 class="section-title">Reports</h2>
        @forelse($reports as $report)<p>{{ $report->reason }} <span class="muted">{{ $report->status }}</span></p>@empty<p class="muted">No reports are waiting.</p>@endforelse
    </section>
</x-layouts.app>
