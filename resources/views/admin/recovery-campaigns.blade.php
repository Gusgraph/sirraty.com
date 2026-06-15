<x-layouts.app title="Recovery Campaigns | Admin Zone">
    <div class="row" style="justify-content:space-between;margin-bottom:19px">
        <h1 class="section-title" style="margin:0">Recovery Campaigns</h1>
        <div class="row" style="gap:7px">
            <a class="btn" href="{{ route('admin.mailing') }}">Mailing</a>
            <a class="btn" href="{{ route('admin.mailing.queue') }}">Queue</a>
        </div>
    </div>

    <section class="panel" style="margin-bottom:19px">
        <h2 class="section-title" style="margin-bottom:11px">Cleaned Audience Preview</h2>
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(137px,1fr));gap:11px;margin-bottom:15px">
            <div><small class="muted">Eligible</small><strong style="display:block">{{ number_format($preview['eligible']) }}</strong></div>
            <div><small class="muted">Suppressed</small><strong style="display:block">{{ number_format($preview['suppressed']) }}</strong></div>
            <div><small class="muted">Bounced</small><strong style="display:block">{{ number_format($preview['bounced']) }}</strong></div>
            <div><small class="muted">Complained</small><strong style="display:block">{{ number_format($preview['complained']) }}</strong></div>
            <div><small class="muted">Unsubscribed</small><strong style="display:block">{{ number_format($preview['unsubscribed']) }}</strong></div>
        </div>
        <p class="muted" style="margin:0 0 11px">Recovery campaigns use the configured recovery SMTP provider, exclude suppressed/bounced/complained users, require unverified accounts, and include unsubscribe headers and links.</p>
        @unless($recoveryProvider['available'])
            <p style="margin:0;color:#f0b35b">{{ $recoveryProvider['label'] }} is not configured yet. Set the provider SMTP host, username, and password before queuing recovery mail.</p>
        @else
            <p class="muted" style="margin:0">Provider: {{ $recoveryProvider['label'] }}</p>
        @endunless
    </section>

    <section class="panel" style="margin-bottom:19px">
        <h2 class="section-title" style="margin-bottom:11px">Queue Recovery Notice</h2>
        <form method="POST" action="{{ route('admin.mailing.recovery.store') }}" class="grid" style="grid-template-columns:repeat(auto-fit,minmax(173px,1fr));gap:11px;align-items:end">
            @csrf
            <label>Campaign name<input name="name" required value="Sirraty is back online" autocomplete="off"></label>
            <label>Subject<input name="subject" required value="Sirraty is back online - recover your account" autocomplete="off"></label>
            <label>Clean recipient limit<input name="limit" type="number" min="1" max="5000" value="250" required></label>
            <label>Hourly cap<input name="hourly_cap" type="number" min="1" max="250" value="25" required></label>
            <label>Daily cap<input name="daily_cap" type="number" min="1" max="1000" value="100" required></label>
            <label>Bounce stop rate %<input name="bounce_stop_rate" type="number" min="1" max="10" step="0.1" value="5" required></label>
            <label style="grid-column:1/-1">HTML body
                <textarea name="body" rows="8" required>Assalamu alaikum @{{name}},<br><br>Sirraty is back online. An old Sirraty account may be connected to this email address. If you want to recover access, visit <a href="@{{login_url}}">Sirraty</a> and use account recovery or password reset.<br><br>If you do not want recovery messages, use the unsubscribe link below and we will not contact this address again.</textarea>
            </label>
            <button class="btn" type="submit" @disabled(! $recoveryProvider['available'])>Queue Recovery Notice</button>
        </form>
    </section>

    <section class="panel">
        <h2 class="section-title" style="margin-bottom:11px">Campaign Status Dashboard</h2>
        <div style="overflow:auto">
            <table style="width:100%;border-collapse:collapse;font-size:.83rem">
                <thead><tr>
                    <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Name</th>
                    <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Status</th>
                    <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Recipients</th>
                    <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Sent</th>
                    <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Bounced</th>
                    <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Unsub</th>
                </tr></thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                        <tr>
                            <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)"><a href="{{ route('admin.mailing.recovery.show', $campaign) }}">{{ $campaign->name }}</a></td>
                            <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ str_replace('_', ' ', $campaign->status) }}</td>
                            <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ number_format($campaign->recipient_count) }}</td>
                            <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ number_format($campaign->sent_count) }}</td>
                            <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ number_format($campaign->bounced_count + $campaign->complained_count) }}</td>
                            <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ number_format($campaign->unsubscribed_count) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="muted" style="padding:11px">No recovery campaigns yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.app>
