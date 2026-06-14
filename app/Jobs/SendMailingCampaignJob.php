<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Jobs/SendMailingCampaignJob.php
// =====================================================

namespace App\Jobs;

use App\Mail\AdminTemplateMail;
use App\Models\MailingCampaign;
use App\Models\SiteSetting;
use App\Services\MailingTemplateRenderer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class SendMailingCampaignJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $campaignId)
    {
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('mailing-campaign-'.$this->campaignId))->expireAfter(3600),
        ];
    }

    public function handle(MailingTemplateRenderer $renderer): void
    {
        $campaign = MailingCampaign::findOrFail($this->campaignId);
        $campaign->update(['status' => 'sending']);

        $settings = SiteSetting::whereIn('key', ['mailing.reply_to', 'mailing.footer', 'mailing.emails_per_3_minutes'])->pluck('value', 'key');
        $replyTo = $settings->get('mailing.reply_to');
        $footer = $settings->get('mailing.footer');
        $sendLimit = max(1, min(997, (int) ($settings->get('mailing.emails_per_3_minutes') ?: 73)));
        $sentThisRun = 0;

        $campaign->deliveries()
            ->with('user')
            ->where('status', 'queued')
            ->orderBy('id')
            ->chunkById(73, function ($deliveries) use ($campaign, $renderer, $replyTo, $footer, $sendLimit, &$sentThisRun): bool {
                foreach ($deliveries as $delivery) {
                    if ($sentThisRun >= $sendLimit) {
                        return false;
                    }

                    try {
                        $subject = $renderer->render($campaign->subject, $delivery->user);
                        $body = $renderer->render($campaign->body, $delivery->user);
                        Mail::to($delivery->email)->send(new AdminTemplateMail($subject, $body, Str::limit($body, 173), $replyTo, $footer, $delivery->id));
                        $delivery->update(['status' => 'sent', 'sent_at' => now(), 'error' => null]);
                        $sentThisRun++;
                    } catch (Throwable $exception) {
                        $delivery->update([
                            'status' => 'failed',
                            'error' => Str($exception->getMessage())->limit(1000),
                        ]);
                    }

                    usleep(250000);
                }

                return true;
            });

        $hasQueued = $campaign->deliveries()->where('status', 'queued')->exists();
        if ($hasQueued) {
            $campaign->update([
                'status' => 'waiting',
                'sent_count' => $campaign->deliveries()->where('status', 'sent')->count(),
                'failed_count' => $campaign->deliveries()->where('status', 'failed')->count(),
            ]);

            if (config('queue.default') !== 'sync') {
                self::dispatch($campaign->id)->delay(now()->addSeconds(73));
            }

            return;
        }

        $campaign->update([
            'status' => $campaign->deliveries()->where('status', 'failed')->exists() ? 'sent_with_errors' : 'sent',
            'sent_count' => $campaign->deliveries()->where('status', 'sent')->count(),
            'failed_count' => $campaign->deliveries()->where('status', 'failed')->count(),
            'sent_at' => now(),
        ]);
    }
}
