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
    <div class="row" style="justify-content:space-between;margin-bottom:19px">
        <h1 class="section-title" style="margin:0">{{ $config['title'] }}</h1>
        <span class="muted">{{ method_exists($records, 'total') ? $records->total() : 0 }} records</span>
    </div>
    @if(method_exists($records, 'count') && $records->count())
        <div class="grid">
            @foreach($records as $record)
                <article class="panel">
                    <strong>{{ $record->name ?? $record->title ?? $record->reason ?? $record->status ?? 'Record #'.$record->id }}</strong>
                    <p class="muted">{{ $record->created_at?->diffForHumans() }}</p>
                </article>
            @endforeach
        </div>
        {{ $records->links() }}
    @else
        <div class="empty">No {{ strtolower($config['title']) }} records yet.</div>
    @endif
</x-layouts.app>
