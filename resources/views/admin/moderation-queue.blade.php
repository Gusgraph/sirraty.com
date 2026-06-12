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
    @php
        $statusClass = [
            'new' => 'queue-new',
            'assigned' => 'queue-assigned',
            'reviewing' => 'queue-reviewing',
            'resolved' => 'queue-resolved',
            'dismissed' => 'queue-dismissed',
        ];
        $decisionImpact = [
            'hide' => 'Content will be hidden from public view.',
            'remove' => 'Content will be removed from public view.',
            'restore' => 'Content will be restored where allowed.',
            'warn' => 'No content change. Use notes for warning action.',
            'suspend' => 'No content change. Use notes for account action.',
            'ban' => 'No content change. Use notes for account action.',
        ];
        $ownerFor = function ($item) {
            if ($item instanceof \App\Models\Post || $item instanceof \App\Models\Comment) {
                return $item->user;
            }
            if ($item instanceof \App\Models\Page || $item instanceof \App\Models\Group) {
                return $item->owner;
            }
            if ($item instanceof \App\Models\MarketListing) {
                return $item->seller;
            }
            if ($item instanceof \App\Models\User) {
                return $item;
            }
            return null;
        };
        $contentUrlFor = function ($item) {
            if ($item instanceof \App\Models\Post) {
                return $item->postable instanceof \App\Models\Page
                    ? route('app.pages.show', $item->postable)
                    : ($item->postable instanceof \App\Models\Group ? route('app.groups.show', $item->postable) : route('profile.show', $item->user));
            }
            if ($item instanceof \App\Models\Comment) {
                return $item->post?->postable instanceof \App\Models\Page
                    ? route('app.pages.show', $item->post->postable)
                    : ($item->post?->postable instanceof \App\Models\Group ? route('app.groups.show', $item->post->postable) : route('profile.show', $item->user));
            }
            if ($item instanceof \App\Models\Page) {
                return route('app.pages.show', $item);
            }
            if ($item instanceof \App\Models\Group) {
                return route('app.groups.show', $item);
            }
            if ($item instanceof \App\Models\User) {
                return route('profile.show', $item);
            }
            return null;
        };
    @endphp

    <style>
        .moderation-table {
            display: grid;
            gap: 7px;
        }
        .moderation-row {
            display: grid;
            grid-template-columns: 73px minmax(181px, 1.4fr) minmax(151px, .9fr) minmax(151px, .9fr) minmax(319px, 1.7fr);
            gap: 11px;
            align-items: stretch;
            padding: 9px 11px;
            border-left: 5px solid rgba(22, 199, 101, .37);
        }
        .moderation-head {
            min-height: 31px;
            font-size: .77rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0;
            border-left-color: transparent;
        }
        .moderation-row.queue-new { border-left-color: #f59e0b; background: rgba(245, 158, 11, .07); }
        .moderation-row.queue-assigned { border-left-color: #38bdf8; background: rgba(56, 189, 248, .07); }
        .moderation-row.queue-reviewing { border-left-color: #a78bfa; background: rgba(167, 139, 250, .07); }
        .moderation-row.queue-resolved { border-left-color: #22c55e; background: rgba(34, 197, 94, .07); }
        .moderation-row.queue-dismissed { border-left-color: #94a3b8; background: rgba(148, 163, 184, .07); }
        .moderation-cell {
            min-width: 0;
        }
        .moderation-title {
            display: block;
            font-weight: 800;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .moderation-mini {
            margin: 3px 0 0;
            color: var(--muted);
            font-size: .83rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .moderation-actions {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 7px;
            align-items: end;
        }
        .moderation-actions .field {
            margin: 0;
            gap: 3px;
            font-size: .77rem;
        }
        .moderation-actions select,
        .moderation-actions input {
            min-height: 33px;
            padding: 5px 7px;
            font-size: .83rem;
        }
        .moderation-actions button {
            min-height: 33px;
            padding: 5px 11px;
        }
        .decision-note {
            grid-column: 1 / -1;
            color: var(--muted);
            font-size: .79rem;
        }
        @media (max-width: 1180px) {
            .moderation-row {
                grid-template-columns: 57px minmax(0, 1fr);
            }
            .moderation-head {
                display: none;
            }
            .moderation-actions {
                grid-column: 1 / -1;
            }
        }
        @media (max-width: 720px) {
            .moderation-row,
            .moderation-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="row" style="justify-content:space-between;margin-bottom:19px">
        <div>
            <h1 class="section-title" style="margin:0">Moderation Queue</h1>
            <p class="muted" style="margin:7px 0 0">Compact list. One row per reported item.</p>
        </div>
        <a class="btn" href="{{ route('admin.dashboard') }}">Dashboard</a>
    </div>

    @if($records->count())
        <section class="moderation-table">
            <div class="moderation-row moderation-head">
                <span>Reports</span>
                <span>Content</span>
                <span>Owner</span>
                <span>Status</span>
                <span>Actions</span>
            </div>
            @foreach($records as $case)
                @php
                    $item = $case->moderatable;
                    $type = class_basename($case->moderatable_type ?? 'Item');
                    $countKey = ($case->moderatable_type ?? '').':'.($case->moderatable_id ?? '');
                    $reportCount = $reportCounts[$countKey] ?? ($case->report ? 1 : 0);
                    $title = $item->name ?? $item->title ?? $item->body ?? $item->email ?? 'Item #'.$case->moderatable_id;
                    $owner = $ownerFor($item);
                    $ownerName = $owner?->profile?->display_name ?? $owner?->name ?? 'Unknown';
                    $contentUrl = $contentUrlFor($item);
                    $decision = $case->decision ?? 'none';
                @endphp
                <article class="panel moderation-row {{ $statusClass[$case->status] ?? 'queue-new' }}">
                    <div class="moderation-cell">
                        <strong>{{ $reportCount }}</strong>
                        <p class="moderation-mini">{{ Str::plural('report', $reportCount) }}</p>
                    </div>
                    <div class="moderation-cell">
                        <span class="chip">{{ $type }}</span>
                        @if($contentUrl)
                            <a class="moderation-title" href="{{ $contentUrl }}">{{ Str::limit($title, 73) }}</a>
                        @else
                            <span class="moderation-title">{{ Str::limit($title, 73) }}</span>
                        @endif
                        @if($case->report)
                            <p class="moderation-mini">{{ $case->report->reason }} · {{ Str::limit($case->report->details, 73) }}</p>
                        @endif
                    </div>
                    <div class="moderation-cell">
                        @if($owner)
                            <a class="moderation-title" href="{{ route('profile.show', ['user' => $owner->username]) }}">{{ Str::limit($ownerName, 37) }}</a>
                            <p class="moderation-mini">{{ '@'.$owner->username }}</p>
                        @else
                            <span class="moderation-title">Unknown</span>
                        @endif
                    </div>
                    <div class="moderation-cell">
                        <span class="chip">{{ ucfirst($case->status) }}</span>
                        <span class="chip">{{ $decision === 'none' ? 'No action' : ucfirst($decision) }}</span>
                        <p class="moderation-mini">Opened {{ $case->created_at?->diffForHumans() }}</p>
                    </div>
                    <form class="moderation-actions" method="POST" action="{{ route('admin.moderation-cases.update', $case) }}">
                        @csrf
                        @method('PATCH')
                        <label class="field">Status
                            <select name="status">
                                @foreach(['new' => 'New', 'assigned' => 'Assigned', 'reviewing' => 'Reviewing', 'resolved' => 'Resolved', 'dismissed' => 'Dismissed'] as $value => $label)
                                    <option value="{{ $value }}" @selected($case->status === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="field">Action
                            <select name="decision" data-decision-select>
                                @foreach(['none' => 'No content action', 'hide' => 'Hide content', 'remove' => 'Remove content', 'restore' => 'Restore content', 'warn' => 'Warn user', 'suspend' => 'Suspend user', 'ban' => 'Ban user'] as $value => $label)
                                    <option value="{{ $value }}" @selected($decision === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="field">Assign
                            <select name="assign_to_me">
                                <option value="0">{{ $case->assignedUser ? 'Assigned' : 'Unassigned' }}</option>
                                <option value="1">Assign to me</option>
                            </select>
                        </label>
                        <button class="btn primary" type="submit"><i class="fa-solid fa-check"></i> Apply</button>
                        <input type="hidden" name="notes" value="{{ $case->notes }}">
                        <span class="decision-note">{{ $decisionImpact[$decision] ?? 'No content change will be applied.' }}</span>
                    </form>
                </article>
            @endforeach
        </section>
        {{ $records->links() }}
    @else
        <div class="empty">No moderation cases are waiting.</div>
    @endif
</x-layouts.app>
