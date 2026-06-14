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
    <h1 class="section-title">Admin Zone</h1>
    <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(173px,1fr));margin-bottom:19px">
        @php($links = ['Users' => 'users', 'Posts' => 'posts', 'Comments' => 'comments', 'Pages' => 'pages', 'Groups' => 'groups', 'Market listings' => 'market-listings', 'Reports' => 'reports', 'Moderation cases' => 'moderation-queue', 'Word filters' => 'word-filters', 'Locations' => 'locations', 'Categories' => 'categories'])
        @foreach($counts as $label => $count)
            <a class="panel" href="{{ $label === 'Mailing' ? route('admin.mailing') : route('admin.section', $links[$label]) }}">
                <strong style="font-size:1.7rem">{{ $count }}</strong>
                <p class="muted">{{ $label }}</p>
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
    <section class="panel">
        <h2 class="section-title">Reports</h2>
        @forelse($reports as $report)<p>{{ $report->reason }} <span class="muted">{{ $report->status }}</span></p>@empty<p class="muted">No reports are waiting.</p>@endforelse
    </section>
</x-layouts.app>
