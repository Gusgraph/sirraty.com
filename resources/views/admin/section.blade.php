{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/admin/section.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app :title="ucwords($section).' | Admin Zone'">
    <div class="row" style="justify-content:space-between;margin-bottom:19px">
        <h1 class="section-title" style="margin:0">{{ ucwords($section) }}</h1>
        <a class="btn" href="{{ route('admin.dashboard') }}">Dashboard</a>
    </div>
    @forelse($records as $record)
        <article class="panel" style="margin-bottom:11px">
            <strong>{{ $record->name ?? $record->title ?? $record->email ?? $record->word ?? $record->reason ?? 'Record #'.$record->id }}</strong>
            <p class="muted">{{ $record->status ?? $record->role ?? $record->created_at?->diffForHumans() }}</p>
        </article>
    @empty
        <div class="empty">No records in this section.</div>
    @endforelse
    {{ $records->links() }}
</x-layouts.app>
