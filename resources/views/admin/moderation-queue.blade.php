{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/admin/moderation-queue.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Moderation Queue | Admin Zone">
    <div class="row" style="justify-content:space-between;margin-bottom:19px">
        <div>
            <h1 class="section-title" style="margin:0">Moderation Queue</h1>
            <p class="muted" style="margin:7px 0 0">One case per reported item.</p>
        </div>
        <a class="btn" href="{{ route('admin.dashboard') }}">Dashboard</a>
    </div>

    @forelse($records as $case)
        @php
            $item = $case->moderatable;
            $type = class_basename($case->moderatable_type ?? 'Item');
            $countKey = ($case->moderatable_type ?? '').':'.($case->moderatable_id ?? '');
            $reportCount = $reportCounts[$countKey] ?? ($case->report ? 1 : 0);
            $title = $item->name ?? $item->title ?? $item->body ?? $item->email ?? 'Item #'.$case->moderatable_id;
        @endphp
        <article class="panel" style="margin-bottom:15px">
            <div class="row" style="justify-content:space-between;align-items:flex-start">
                <div>
                    <div class="chip-row">
                        <span class="chip">{{ $type }}</span>
                        <span class="chip">{{ $reportCount }} {{ Str::plural('report', $reportCount) }}</span>
                        <span class="chip">{{ ucfirst($case->status) }}</span>
                        @if($case->decision)<span class="chip">{{ ucfirst($case->decision) }}</span>@endif
                    </div>
                    <h2 class="section-title" style="margin:11px 0 7px">{{ Str::limit($title, 97) }}</h2>
                    <p class="muted">Opened {{ $case->created_at?->diffForHumans() }} by {{ $case->openedBy?->profile?->display_name ?? $case->openedBy?->name ?? 'System' }}</p>
                    @if($case->report)
                        <p style="margin:11px 0 0"><strong>{{ $case->report->reason }}</strong> <span class="muted">{{ $case->report->details }}</span></p>
                    @endif
                </div>
            </div>
            <form method="POST" action="{{ route('admin.moderation-cases.update', $case) }}" style="margin-top:15px">
                @csrf
                @method('PATCH')
                <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(191px,1fr));gap:11px">
                    <label class="field">Status
                        <select name="status">
                            @foreach(['new' => 'New', 'assigned' => 'Assigned', 'reviewing' => 'Reviewing', 'resolved' => 'Resolved', 'dismissed' => 'Dismissed'] as $value => $label)
                                <option value="{{ $value }}" @selected($case->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="field">Decision
                        <select name="decision">
                            @foreach(['none' => 'No action', 'hide' => 'Hide', 'remove' => 'Remove', 'restore' => 'Restore', 'warn' => 'Warn', 'suspend' => 'Suspend', 'ban' => 'Ban'] as $value => $label)
                                <option value="{{ $value }}" @selected(($case->decision ?? 'none') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="field">Assign
                        <select name="assign_to_me">
                            <option value="0">Keep assignment</option>
                            <option value="1">Assign to me</option>
                        </select>
                    </label>
                </div>
                <label class="field">Notes
                    <textarea name="notes" rows="3" maxlength="2000">{{ old('notes', $case->notes) }}</textarea>
                </label>
                <div class="row" style="justify-content:flex-end">
                    <button class="btn primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Review</button>
                </div>
            </form>
        </article>
    @empty
        <div class="empty">No moderation cases are waiting.</div>
    @endforelse
    {{ $records->links() }}
</x-layouts.app>
