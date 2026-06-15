<x-layouts.app title="Recovery Campaign | Admin Zone">
    <div class="row" style="justify-content:space-between;margin-bottom:19px">
        <h1 class="section-title" style="margin:0">{{ $campaign->name }}</h1>
        <a class="btn" href="{{ route('admin.mailing.recovery') }}">Recovery Campaigns</a>
    </div>
    <section class="panel" style="margin-bottom:19px">
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(137px,1fr));gap:11px">
            @foreach(['status','provider','recipient_count','sent_count','failed_count','bounced_count','complained_count','suppressed_count','unsubscribed_count'] as $field)
                <div><small class="muted">{{ str_replace('_', ' ', ucfirst($field)) }}</small><strong style="display:block">{{ is_numeric($campaign->$field) ? number_format($campaign->$field) : $campaign->$field }}</strong></div>
            @endforeach
        </div>
        @if($campaign->stop_reason)<p style="color:#f0b35b">{{ $campaign->stop_reason }}</p>@endif
        <div class="row" style="gap:7px;margin-top:13px">
            @if(in_array($campaign->status, ['queued','sending','waiting'], true))
                <form method="POST" action="{{ route('admin.mailing.recovery.pause', $campaign) }}">@csrf<button class="btn">Pause</button></form>
            @endif
            @if(in_array($campaign->status, ['paused','stopped'], true))
                <form method="POST" action="{{ route('admin.mailing.recovery.resume', $campaign) }}">@csrf<button class="btn">Resume</button></form>
            @endif
        </div>
    </section>
    <section class="panel">
        <h2 class="section-title" style="margin-bottom:11px">Deliveries</h2>
        <div style="overflow:auto"><table style="width:100%;border-collapse:collapse;font-size:.83rem">
            <thead><tr><th>Email</th><th>User</th><th>Status</th><th>Sent</th><th>Error</th></tr></thead>
            <tbody>
                @foreach($deliveries as $delivery)
                    <tr>
                        <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $delivery->email }}</td>
                        <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $delivery->user?->username ?? 'External' }}</td>
                        <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $delivery->status }}</td>
                        <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $delivery->sent_at?->diffForHumans() }}</td>
                        <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ $delivery->error }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table></div>
        {{ $deliveries->links() }}
    </section>
</x-layouts.app>
