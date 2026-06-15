<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendRecoveryCampaignJob;
use App\Models\RecoveryCampaign;
use App\Services\Mail\MailProviderManager;
use App\Services\Mail\RecoveryAudienceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecoveryCampaignController extends Controller
{
    public function index(RecoveryAudienceService $audience, MailProviderManager $providers): View
    {
        return view('admin.recovery-campaigns', [
            'preview' => $audience->preview(),
            'campaigns' => RecoveryCampaign::latest()->limit(20)->get(),
            'recoveryProvider' => $this->recoveryProvider($providers),
            'mailcow' => config('mail.mailers.mailcow'),
            'inmotion' => config('mail.mailers.inmotion'),
        ]);
    }

    public function store(Request $request, RecoveryAudienceService $audience, MailProviderManager $providers): RedirectResponse
    {
        $provider = $this->recoveryProvider($providers);
        abort_unless($provider['available'], 422, $provider['label'].' is not configured.');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:173'],
            'subject' => ['required', 'string', 'max:191'],
            'body' => ['required', 'string', 'max:20000'],
            'limit' => ['required', 'integer', 'min:1', 'max:5000'],
            'hourly_cap' => ['required', 'integer', 'min:1', 'max:250'],
            'daily_cap' => ['required', 'integer', 'min:1', 'max:1000'],
            'bounce_stop_rate' => ['required', 'numeric', 'min:1', 'max:10'],
        ]);

        $campaign = RecoveryCampaign::create([
            'created_by' => $request->user()->id,
            'name' => $data['name'],
            'subject' => $data['subject'],
            'body' => $data['body'],
            'provider' => $provider['key'],
            'status' => 'queued',
            'hourly_cap' => $data['hourly_cap'],
            'daily_cap' => $data['daily_cap'],
            'bounce_stop_rate' => $data['bounce_stop_rate'],
            'queued_at' => now(),
        ]);

        $count = $audience->seedCampaign($campaign, (int) $data['limit']);
        if ($count === 0) {
            $campaign->update(['status' => 'no_recipients', 'completed_at' => now()]);
            return redirect()->route('admin.mailing.recovery.show', $campaign)->with('warning', 'No eligible recovery recipients were found.');
        }

        SendRecoveryCampaignJob::dispatch($campaign->id);

        return redirect()->route('admin.mailing.recovery.show', $campaign)->with('status', "Recovery campaign queued for {$count} cleaned recipients.");
    }

    public function show(RecoveryCampaign $campaign): View
    {
        return view('admin.recovery-campaign', [
            'campaign' => $campaign->load('creator'),
            'deliveries' => $campaign->deliveries()->with('user.profile')->latest('updated_at')->paginate(50),
        ]);
    }

    public function pause(RecoveryCampaign $campaign): RedirectResponse
    {
        if (in_array($campaign->status, ['queued', 'sending', 'waiting'], true)) {
            $campaign->update(['status' => 'paused', 'stopped_at' => now(), 'stop_reason' => 'Paused by admin.']);
        }

        return back()->with('status', 'Recovery campaign paused.');
    }

    public function resume(RecoveryCampaign $campaign): RedirectResponse
    {
        abort_unless(in_array($campaign->status, ['paused', 'stopped'], true), 422, 'Campaign cannot be resumed.');
        $campaign->update(['status' => 'queued', 'stop_reason' => null]);
        SendRecoveryCampaignJob::dispatch($campaign->id);

        return back()->with('status', 'Recovery campaign resumed.');
    }

    private function recoveryProvider(MailProviderManager $providers): array
    {
        $available = $providers->providers();
        $key = (string) config('mail.recovery_provider', env('RECOVERY_MAIL_PROVIDER', 'inmotion'));

        if (! array_key_exists($key, $available)) {
            $key = 'inmotion';
        }

        return ['key' => $key] + $available[$key];
    }
}
