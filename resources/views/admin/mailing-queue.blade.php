{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/admin/mailing-queue.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Mail Queue | Admin Zone">
    <div class="row" style="justify-content:space-between;margin-bottom:19px">
        <h1 class="section-title" style="margin:0">Mail Queue</h1>
        <div class="row" style="gap:7px">
            <a class="btn" href="{{ route('admin.mailing') }}">Mailing</a>
            <a class="btn" href="{{ route('admin.dashboard') }}">Dashboard</a>
        </div>
    </div>

    <section class="panel" style="margin-bottom:19px">
        <div class="row" style="justify-content:space-between;gap:11px;align-items:flex-start">
            <div>
                <h2 class="section-title" style="margin-bottom:7px">{{ $campaign?->name ?? 'No Campaign' }}</h2>
                @if($campaign)
                    <p class="muted" id="mail-queue-status-line" style="margin:0">Status: {{ str_replace('_', ' ', $campaign->status) }} · Batch: {{ $settings['emails_per_3_minutes'] }} emails each 5 minutes</p>
                @else
                    <p class="muted" style="margin:0">No campaign has been queued yet.</p>
                @endif
            </div>
            @if($campaign)
                <div class="row" style="gap:7px;justify-content:flex-end">
                    @if($campaign->deliveries()->where('status', 'failed')->exists())
                        <form method="POST" action="{{ route('admin.mailing.queue.retry-failed', $campaign) }}">
                            @csrf
                            <button class="btn" type="submit">Retry Failed</button>
                        </form>
                    @endif
                    @if(in_array($campaign->status, ['queued', 'sending', 'waiting', 'sent_with_errors'], true))
                        <form method="POST" action="{{ route('admin.mailing.queue.pause', $campaign) }}">
                            @csrf
                            <button class="btn" type="submit">Pause</button>
                        </form>
                    @endif
                    @if($campaign->status === 'paused')
                        <form method="POST" action="{{ route('admin.mailing.queue.resume', $campaign) }}">
                            @csrf
                            <button class="btn" type="submit">Resume Sending</button>
                        </form>
                    @elseif(in_array($campaign->status, ['queued', 'sending', 'waiting', 'sent_with_errors'], true))
                        <form method="POST" action="{{ route('admin.mailing.queue.process', $campaign) }}">
                            @csrf
                            <button class="btn" type="submit">Run Next Batch</button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
        @if($campaign)
            <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(137px,1fr));gap:11px;margin-top:15px">
                <div><small class="muted">Recipients</small><strong id="mail-stat-recipients" style="display:block">{{ number_format($campaign->recipient_count) }}</strong></div>
                <div><small class="muted">Sent</small><strong id="mail-stat-sent" style="display:block">{{ number_format($campaign->sent_count) }}</strong></div>
                <div><small class="muted">Open</small><strong id="mail-stat-opened" style="display:block">{{ number_format($campaign->deliveries()->whereNotNull('opened_at')->count()) }}</strong></div>
                <div><small class="muted">Feedback</small><strong id="mail-stat-feedback" style="display:block">{{ number_format($campaign->deliveries()->whereNotNull('feedback_status')->count()) }}</strong></div>
                <div><small class="muted">Failed</small><strong id="mail-stat-failed" style="display:block">{{ number_format($campaign->failed_count) }}</strong></div>
                <div><small class="muted">Queued</small><strong id="mail-stat-queued" style="display:block">{{ number_format(max(0, $campaign->recipient_count - $campaign->sent_count - $campaign->failed_count)) }}</strong></div>
            </div>
        @endif
    </section>

    <div class="grid" style="grid-template-columns:minmax(213px,273px) minmax(0,1fr);gap:19px;align-items:start">
        <aside class="panel">
            <h2 class="section-title" style="margin-bottom:7px">Campaigns</h2>
            <div style="display:grid;gap:5px;max-height:573px;overflow:auto">
                @foreach($campaigns as $row)
                    <a href="{{ route('admin.mailing.queue', $row) }}" style="display:block;padding:7px 0;border-top:1px solid rgba(22,199,101,.19)">
                        <strong style="display:block;font-size:.83rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $row->name }}</strong>
                        <span class="muted" style="font-size:.73rem">{{ str_replace('_', ' ', $row->status) }} · sent {{ number_format($row->sent_count) }}/{{ number_format($row->recipient_count) }} · open {{ number_format($row->deliveries()->whereNotNull('opened_at')->count()) }} · feedback {{ number_format($row->deliveries()->whereNotNull('feedback_status')->count()) }}</span>
                    </a>
                @endforeach
            </div>
            {{ $campaigns->links() }}
        </aside>

        <section class="panel">
            <h2 class="section-title" style="margin-bottom:11px">Emails</h2>
            <style>
                .mail-send-row {
                    transition: opacity .45s ease, transform .45s ease, max-height .45s ease, padding .45s ease, margin .45s ease;
                    max-height: 47px;
                }
                .mail-send-row.is-dismissing {
                    opacity: 0;
                    transform: translateY(-5px);
                    max-height: 0;
                    padding-top: 0 !important;
                    padding-bottom: 0 !important;
                    margin: 0;
                    overflow: hidden;
                }
            </style>
            <div id="mail-send-window" style="display:grid;gap:5px;max-height:573px;overflow:auto;border-top:1px solid rgba(22,199,101,.27);padding-top:7px;margin-bottom:11px">
                @forelse(($deliveries?->getCollection() ?? collect()) as $delivery)
                    <div class="mail-send-row" data-delivery-id="{{ $delivery->id }}" data-email="{{ $delivery->email }}" data-status="{{ $delivery->status }}" style="display:grid;grid-template-columns:27px minmax(0,1fr) auto auto auto;gap:7px;align-items:center;padding:5px 0;border-bottom:1px solid rgba(22,199,101,.13);font-size:.79rem">
                        <span class="mail-send-icon">
                            @if($delivery->status === 'sent')
                                <i class="fa-solid fa-circle-check" style="color:#16c765"></i>
                            @elseif($delivery->status === 'failed')
                                <i class="fa-solid fa-triangle-exclamation" style="color:#b83247"></i>
                            @else
                                <i class="fa-regular fa-clock" style="color:#a36f13"></i>
                            @endif
                        </span>
                        <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $delivery->email }}</span>
                        <span class="muted">{{ $delivery->opened_at ? 'Open' : 'Not open' }}</span>
                        <span class="muted">{{ $delivery->feedback_status ? ucfirst($delivery->feedback_status) : 'Clear' }}</span>
                        <span class="muted">{{ ucfirst($delivery->status) }}</span>
                    </div>
                @empty
                    <p class="muted" style="margin:0;padding:7px 0">No emails are in this campaign window.</p>
                @endforelse
            </div>
            <div style="overflow:auto">
                <table style="width:100%;border-collapse:collapse;font-size:.79rem">
                    <thead>
                        <tr>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Email</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">User</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Status</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Open</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Feedback</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Time</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveries ?? [] as $delivery)
                            <tr>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $delivery->email }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $delivery->user?->username ?? 'External' }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ ucfirst($delivery->status) }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $delivery->opened_at ? 'Open' : 'Not open' }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $delivery->feedback_status ? ucfirst($delivery->feedback_status).' '.$delivery->feedback_subtype : 'Clear' }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $delivery->sent_at?->diffForHumans() ?? $delivery->updated_at?->diffForHumans() }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13);max-width:273px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $delivery->error }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="muted" style="padding:11px">No email deliveries for this campaign.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($deliveries)
                {{ $deliveries->links() }}
            @endif
        </section>
    </div>

    <section class="panel" style="margin-top:19px">
        <h2 class="section-title" style="margin-bottom:11px">History</h2>
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(319px,1fr));gap:19px;align-items:start">
            <div style="overflow:auto">
                <table style="width:100%;border-collapse:collapse;font-size:.79rem">
                    <thead>
                        <tr>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Campaign</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Status</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Sent</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Open</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Feedback</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Failed</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historyCampaigns as $history)
                            <tr>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)"><a href="{{ route('admin.mailing.queue', $history) }}">{{ $history->name }}</a></td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ str_replace('_', ' ', $history->status) }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ number_format($history->sent_count) }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ number_format($history->deliveries()->whereNotNull('opened_at')->count()) }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ number_format($history->deliveries()->whereNotNull('feedback_status')->count()) }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ number_format($history->failed_count) }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $history->sent_at?->diffForHumans() ?? $history->updated_at?->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="muted" style="padding:11px">No completed campaign history yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="overflow:auto">
                <table style="width:100%;border-collapse:collapse;font-size:.79rem">
                    <thead>
                        <tr>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Email</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Campaign</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Status</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Open</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Feedback</th>
                            <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historyDeliveries as $historyDelivery)
                            <tr>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $historyDelivery->email }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $historyDelivery->campaign?->name ?? 'Campaign removed' }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ ucfirst($historyDelivery->status) }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $historyDelivery->opened_at ? 'Open' : 'Not open' }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $historyDelivery->feedback_status ? ucfirst($historyDelivery->feedback_status) : 'Clear' }}</td>
                                <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $historyDelivery->sent_at?->diffForHumans() ?? $historyDelivery->updated_at?->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="muted" style="padding:11px">No sent email history yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @if($campaign)
        <script>
            (() => {
                const statusUrl = @json(route('admin.mailing.queue.status', $campaign));
                const formatter = new Intl.NumberFormat();
                const sendWindow = document.getElementById('mail-send-window');
                const statusLine = document.getElementById('mail-queue-status-line');
                const sentRemovalTimers = new Map();
                const iconFor = (status) => {
                    if (status === 'sent') return '<i class="fa-solid fa-circle-check" style="color:#16c765"></i>';
                    if (status === 'failed') return '<i class="fa-solid fa-triangle-exclamation" style="color:#b83247"></i>';
                    if (status === 'sending') return '<i class="fa-solid fa-spinner fa-spin" style="color:#1eb9c5"></i>';
                    return '<i class="fa-regular fa-clock" style="color:#a36f13"></i>';
                };
                const escapeHtml = (value) => {
                    const holder = document.createElement('div');
                    holder.textContent = value || '';
                    return holder.innerHTML;
                };
                const scheduleSentRemoval = () => {
                    sendWindow?.querySelectorAll('.mail-send-row[data-status="sent"]').forEach((row) => {
                        const key = row.dataset.deliveryId || row.dataset.email || Math.random().toString(36);
                        if (sentRemovalTimers.has(key)) return;
                        sentRemovalTimers.set(key, window.setTimeout(() => {
                            row.classList.add('is-dismissing');
                            window.setTimeout(() => {
                                row.remove();
                                sentRemovalTimers.delete(key);
                                if (sendWindow && ! sendWindow.querySelector('.mail-send-row')) {
                                    sendWindow.innerHTML = '<p class="muted" style="margin:0;padding:7px 0">Waiting for the next email in this campaign window.</p>';
                                }
                            }, 500);
                        }, 4000));
                    });
                };
                const updateQueue = async () => {
                    const response = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
                    if (! response.ok) return;
                    const data = await response.json();
                    document.getElementById('mail-stat-recipients').textContent = formatter.format(data.campaign.recipient_count);
                    document.getElementById('mail-stat-sent').textContent = formatter.format(data.campaign.sent_count);
                    document.getElementById('mail-stat-opened').textContent = formatter.format(data.campaign.opened_count);
                    document.getElementById('mail-stat-feedback').textContent = formatter.format(data.campaign.feedback_count);
                    document.getElementById('mail-stat-failed').textContent = formatter.format(data.campaign.failed_count);
                    document.getElementById('mail-stat-queued').textContent = formatter.format(data.campaign.queued_count);
                    if (statusLine) {
                        statusLine.textContent = `Status: ${data.campaign.status} · Batch: {{ $settings['emails_per_3_minutes'] }} emails each 5 minutes · Updated ${data.campaign.updated_at || 'now'}`;
                    }
                    if (! sendWindow) return;
                    if (! data.deliveries.length) {
                        sendWindow.innerHTML = '<p class="muted" style="margin:0;padding:7px 0">No emails are in this campaign window.</p>';
                        return;
                    }
                    sendWindow.innerHTML = data.deliveries.map((delivery) => `
                        <div class="mail-send-row" data-delivery-id="${escapeHtml(String(delivery.id || ''))}" data-email="${escapeHtml(delivery.email)}" data-status="${escapeHtml(delivery.status)}" style="display:grid;grid-template-columns:27px minmax(0,1fr) auto auto auto;gap:7px;align-items:center;padding:5px 0;border-bottom:1px solid rgba(22,199,101,.13);font-size:.79rem">
                            <span class="mail-send-icon">${iconFor(delivery.status)}</span>
                            <span title="${escapeHtml(delivery.error || '')}" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${escapeHtml(delivery.email)}</span>
                            <span class="muted">${delivery.opened ? 'Open' : 'Not open'}</span>
                            <span class="muted">${escapeHtml(delivery.feedback_status ? `${delivery.feedback_status} ${delivery.feedback_subtype || ''}` : 'Clear')}</span>
                            <span class="muted">${escapeHtml((delivery.status || '').replace('_', ' '))}</span>
                        </div>
                    `).join('');
                    scheduleSentRemoval();
                };
                updateQueue();
                scheduleSentRemoval();
                setInterval(updateQueue, 7000);
            })();
        </script>
    @endif
</x-layouts.app>
