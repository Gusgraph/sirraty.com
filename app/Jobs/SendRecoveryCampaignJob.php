<?php

namespace App\Jobs;

use App\Mail\RecoveryCampaignMail;
use App\Models\RecoveryCampaign;
use App\Models\RecoveryDelivery;
use App\Services\Mail\MailingProtectionService;
use App\Services\MailingTemplateRenderer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class SendRecoveryCampaignJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $campaignId)
    {
    }

    public function middleware(): array
    {
        return [(new WithoutOverlapping('recovery-campaign-'.$this->campaignId))->expireAfter(973)->dontRelease()];
    }

    public function handle(MailingTemplateRenderer $renderer, MailingProtectionService $protection): void
    {
        $campaign = RecoveryCampaign::findOrFail($this->campaignId);
        if (! $this->mailerForProvider($campaign->provider) || ! in_array($campaign->status, ['queued', 'sending', 'waiting'], true)) {
            return;
        }

        $campaign->update(['status' => 'sending', 'started_at' => $campaign->started_at ?? now()]);

        $hourSent = $campaign->deliveries()->where('status', 'sent')->where('sent_at', '>=', now()->subHour())->count();
        $daySent = $campaign->deliveries()->where('status', 'sent')->where('sent_at', '>=', now()->subDay())->count();
        $remainingHour = max(0, $campaign->hourly_cap - $hourSent);
        $remainingDay = max(0, $campaign->daily_cap - $daySent);
        $sendLimit = min($remainingHour, $remainingDay);

        if ($sendLimit <= 0) {
            $campaign->update(['status' => 'waiting']);
            self::dispatch($campaign->id)->delay(now()->addMinutes(15));
            return;
        }

        $sentThisRun = 0;
        $campaign->deliveries()
            ->with('user')
            ->where('status', 'queued')
            ->orderBy('id')
            ->chunkById(25, function ($deliveries) use ($campaign, $renderer, $protection, $sendLimit, &$sentThisRun): bool {
                foreach ($deliveries as $delivery) {
                    if ($sentThisRun >= $sendLimit || $this->shouldStopForBounces($campaign)) {
                        return false;
                    }

                    try {
                        $inspection = $protection->inspect($delivery->email, $delivery->user, 'transactional');
                        if (! $inspection['allowed']) {
                            $delivery->update(['status' => 'suppressed', 'error' => $inspection['reason']]);
                            continue;
                        }

                        $subject = $renderer->render($campaign->subject, $delivery->user);
                        $body = $renderer->render($campaign->body, $delivery->user);
                        Mail::mailer($this->mailerForProvider($campaign->provider))->to($inspection['email'])->send(new RecoveryCampaignMail($subject, $body, $delivery));
                        $delivery->update(['status' => 'sent', 'sent_at' => now(), 'error' => null]);
                        $sentThisRun++;
                    } catch (Throwable $exception) {
                        $delivery->update(['status' => 'failed', 'error' => Str::limit($exception->getMessage(), 1000)]);
                    }

                    usleep(500000);
                }

                return true;
            });

        $this->refreshCounts($campaign);

        if ($this->shouldStopForBounces($campaign)) {
            $campaign->update([
                'status' => 'stopped',
                'stopped_at' => now(),
                'stop_reason' => 'Bounce stop rate reached.',
            ]);
            return;
        }

        if ($campaign->deliveries()->where('status', 'queued')->exists()) {
            $campaign->update(['status' => 'waiting']);
            self::dispatch($campaign->id)->delay(now()->addMinutes(15));
            return;
        }

        $campaign->update(['status' => 'completed', 'completed_at' => now()]);
    }

    private function shouldStopForBounces(RecoveryCampaign $campaign): bool
    {
        $sent = max(1, $campaign->deliveries()->whereIn('status', ['sent', 'bounced', 'complained'])->count());
        $bad = $campaign->deliveries()->whereIn('status', ['bounced', 'complained'])->count();

        return $sent >= 25 && (($bad / $sent) * 100) >= (float) $campaign->bounce_stop_rate;
    }

    private function refreshCounts(RecoveryCampaign $campaign): void
    {
        $campaign->update([
            'sent_count' => $campaign->deliveries()->where('status', 'sent')->count(),
            'failed_count' => $campaign->deliveries()->where('status', 'failed')->count(),
            'bounced_count' => $campaign->deliveries()->where('status', 'bounced')->count(),
            'complained_count' => $campaign->deliveries()->where('status', 'complained')->count(),
            'suppressed_count' => $campaign->deliveries()->where('status', 'suppressed')->count(),
            'unsubscribed_count' => $campaign->deliveries()->where('status', 'unsubscribed')->count(),
            'skipped_count' => $campaign->deliveries()->where('status', 'skipped')->count(),
        ]);
    }

    private function mailerForProvider(string $provider): ?string
    {
        $providers = [
            'mailcow' => 'mailcow',
            'inmotion' => 'inmotion',
            'recovery' => 'recovery',
            'log' => 'log',
        ];

        return $providers[$provider] ?? null;
    }
}
