<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Controllers/Admin/MailingController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendMailingCampaignJob;
use App\Mail\AdminTemplateMail;
use App\Models\Country;
use App\Models\EmailTemplate;
use App\Models\MailingCampaign;
use App\Models\MailingDelivery;
use App\Models\SesFeedbackEvent;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\Mail\MailProviderManager;
use App\Services\Mail\MailingProtectionService;
use App\Services\MailingTemplateRenderer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MailingController extends Controller
{
    public function index(MailProviderManager $providers, MailingProtectionService $protection): View
    {
        $this->ensureDefaults();

        return view('admin.mailing', [
            'templates' => EmailTemplate::orderBy('key')->get(),
            'campaigns' => MailingCampaign::with('creator')->latest()->limit(27)->get(),
            'countries' => Country::orderBy('name')->get(),
            'settings' => $this->settings(),
            'mailStatus' => [
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
                'queue' => config('queue.default'),
                'selected_provider' => $providers->selected()['label'],
                'provider_mailer' => $providers->mailerName(),
            ],
            'mailProviders' => $providers->providers(),
            'selectedProvider' => $providers->selectedKey(),
            'protection' => $protection->summary(),
            'sesFeedback' => $this->sesFeedbackSummary(),
        ]);
    }

    public function updateSettings(Request $request, MailProviderManager $providers): RedirectResponse
    {
        $data = $request->validate([
            'mailing_enabled' => ['required', 'boolean'],
            'reply_to' => ['nullable', 'email', 'max:191'],
            'footer' => ['nullable', 'string', 'max:1000'],
            'max_recipients' => ['required', 'integer', 'min:1', 'max:250000'],
            'emails_per_3_minutes' => ['required', 'integer', 'min:1', 'max:997'],
            'mail_provider' => ['required', Rule::in(['default', 'smtp', 'ses', 'postmark', 'mailgun', 'mailcow', 'inmotion', 'recovery', 'log'])],
            'protection_mode' => ['required', Rule::in(['strict', 'monitor'])],
            'allow_transactional_recovery' => ['required', 'boolean'],
            'block_free_bulk_domains' => ['required', 'boolean'],
        ]);

        $availableProviders = $providers->providers();
        if ($data['mail_provider'] !== 'default' && ! ($availableProviders[$data['mail_provider']]['available'] ?? false)) {
            return back()->withErrors(['mail_provider' => 'Selected mail provider is not configured yet.'])->withInput();
        }

        $this->putSetting('mailing.enabled', (string) $data['mailing_enabled']);
        $this->putSetting('mailing.reply_to', $data['reply_to'] ?? '');
        $this->putSetting('mailing.footer', $data['footer'] ?? '');
        $this->putSetting('mailing.max_recipients', (string) $data['max_recipients']);
        $this->putSetting('mailing.emails_per_3_minutes', (string) $data['emails_per_3_minutes']);
        $this->putSetting('mailing.provider', $data['mail_provider']);
        $this->putSetting('mailing.protection_mode', $data['protection_mode']);
        $this->putSetting('mailing.allow_transactional_recovery', (string) $data['allow_transactional_recovery']);
        $this->putSetting('mailing.block_free_bulk_domains', (string) $data['block_free_bulk_domains']);

        return back()->with('status', 'Mailing settings saved.');
    }

    public function saveTemplate(Request $request, ?EmailTemplate $template = null): RedirectResponse
    {
        $data = $request->validate([
            'key' => ['required', 'alpha_dash:ascii', 'max:73', Rule::unique('email_templates', 'key')->ignore($template?->id)],
            'subject' => ['required', 'string', 'max:191'],
            'body' => ['required', 'string', 'max:20000'],
            'enabled' => ['nullable', 'boolean'],
        ]);

        $payload = [
            'key' => $data['key'],
            'subject' => $data['subject'],
            'body' => $data['body'],
            'enabled' => $request->boolean('enabled'),
        ];

        $template ? $template->update($payload) : EmailTemplate::create($payload);

        return back()->with('status', 'Email template saved.');
    }

    public function sendTest(Request $request, MailingTemplateRenderer $renderer, MailingProtectionService $protection, MailProviderManager $providers): RedirectResponse
    {
        $data = $request->validate([
            'template_id' => ['required', 'exists:email_templates,id'],
            'test_email' => ['required', 'email', 'max:191'],
        ]);

        $inspection = $protection->inspect($data['test_email'], null, 'transactional');
        abort_unless($inspection['allowed'], 422, $inspection['reason']);

        $template = EmailTemplate::findOrFail($data['template_id']);
        $user = $request->user();
        $subject = $renderer->render($template->subject, $user);
        $body = $renderer->render($template->body, $user);
        $providers->sendAdminTemplate($inspection['email'], new AdminTemplateMail(
            $subject,
            $body,
            'Sirraty test email.',
            $this->settings()['reply_to'] ?: null,
            $this->settings()['footer'] ?: null,
            null,
            $inspection['email'],
        ));

        return back()->with('status', 'Test email sent.');
    }

    public function sendCampaign(Request $request, MailingProtectionService $protection): RedirectResponse
    {
        $settings = $this->settings();
        abort_if($settings['enabled'] !== '1', 403, 'Mailing is disabled.');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:173'],
            'template_id' => ['required', 'exists:email_templates,id'],
            'audience_type' => ['required', 'in:all,unverified,role,status,country'],
            'role' => ['nullable', 'in:member,moderator,admin,owner'],
            'status' => ['nullable', 'in:active,limited,suspended,banned'],
            'country_id' => ['nullable', 'exists:countries,id'],
        ]);

        $template = EmailTemplate::findOrFail($data['template_id']);
        abort_unless($template->enabled, 422, 'Template is disabled.');

        $filters = [
            'role' => $data['role'] ?? null,
            'status' => $data['status'] ?? null,
            'country_id' => $data['country_id'] ?? null,
        ];
        $query = $this->audienceQuery($data['audience_type'], $filters);
        $limit = (int) $settings['max_recipients'];

        $campaign = MailingCampaign::create([
            'created_by' => $request->user()->id,
            'email_template_id' => $template->id,
            'name' => $data['name'],
            'subject' => $template->subject,
            'body' => $template->body,
            'audience_type' => $data['audience_type'],
            'audience_filters' => array_filter($filters),
            'status' => 'queued',
            'queued_at' => now(),
        ]);

        $recipientCount = 0;
        $query->select('id', 'email')
            ->orderBy('id')
            ->chunkById(273, function ($users) use ($campaign, &$recipientCount, $limit, $protection): bool {
                foreach ($users as $user) {
                    if ($recipientCount >= $limit) {
                        return false;
                    }

                    $inspection = $protection->inspect($user->email, $user, 'bulk');
                    if (! $inspection['allowed']) {
                        continue;
                    }

                    $campaign->deliveries()->firstOrCreate(
                        ['email' => $inspection['email']],
                        ['user_id' => $user->id, 'status' => 'queued'],
                    );
                    $recipientCount++;
                }

                return true;
            }, 'id');

        if ($recipientCount === 0) {
            $campaign->update([
                'recipient_count' => 0,
                'status' => 'no_recipients',
                'sent_at' => now(),
            ]);

            return redirect()->route('admin.mailing.queue', $campaign)->with('warning', 'Campaign created, but no matching recipients were found.');
        }

        $campaign->update(['recipient_count' => $recipientCount]);
        SendMailingCampaignJob::dispatch($campaign->id);

        return redirect()->route('admin.mailing.queue', $campaign)->with('status', "Campaign queued for {$recipientCount} recipients.");
    }

    public function queue(?MailingCampaign $campaign = null): View
    {
        $campaign ??= MailingCampaign::latest()->first();
        $deliveries = $campaign
            ? $this->liveDeliveryWindow($campaign)->paginate(27)->withQueryString()
            : null;

        return view('admin.mailing-queue', [
            'campaigns' => MailingCampaign::with('creator')->latest()->paginate(27),
            'campaign' => $campaign,
            'deliveries' => $deliveries,
            'historyCampaigns' => MailingCampaign::with('creator')
                ->whereIn('status', ['sent', 'sent_with_errors', 'failed', 'no_recipients'])
                ->latest('updated_at')
                ->limit(27)
                ->get(),
            'historyDeliveries' => MailingDelivery::with(['campaign', 'user.profile'])
                ->whereIn('status', ['sent', 'failed'])
                ->latest('updated_at')
                ->limit(73)
                ->get(),
            'settings' => $this->settings(),
        ]);
    }

    public function processQueue(MailingCampaign $campaign): RedirectResponse
    {
        abort_unless(in_array($campaign->status, ['queued', 'sending', 'waiting', 'sent_with_errors', 'paused'], true), 422, 'Campaign cannot be processed.');

        $campaign->update(['status' => 'queued']);
        SendMailingCampaignJob::dispatch($campaign->id);

        return back()->with('status', 'Next mailing batch started.');
    }

    public function pause(MailingCampaign $campaign): RedirectResponse
    {
        abort_unless(in_array($campaign->status, ['queued', 'sending', 'waiting', 'sent_with_errors'], true), 422, 'Campaign cannot be paused.');

        $campaign->update(['status' => 'paused']);

        return back()->with('status', 'Mailing campaign paused.');
    }

    public function resume(MailingCampaign $campaign): RedirectResponse
    {
        abort_unless($campaign->status === 'paused', 422, 'Campaign cannot be resumed.');

        $campaign->update(['status' => 'queued']);
        SendMailingCampaignJob::dispatch($campaign->id);

        return back()->with('status', 'Mailing campaign resumed.');
    }

    public function retryFailed(MailingCampaign $campaign): RedirectResponse
    {
        $retryCount = $campaign->deliveries()->where('status', 'failed')->count();

        if ($retryCount === 0) {
            return back()->with('warning', 'No failed emails are available to retry.');
        }

        $campaign->deliveries()
            ->where('status', 'failed')
            ->update([
                'status' => 'queued',
                'error' => null,
                'sent_at' => null,
                'updated_at' => now(),
            ]);

        $campaign->update([
            'status' => 'queued',
            'sent_count' => $campaign->deliveries()->where('status', 'sent')->count(),
            'failed_count' => 0,
            'sent_at' => null,
        ]);

        SendMailingCampaignJob::dispatch($campaign->id);

        return back()->with('status', "{$retryCount} failed emails were queued again.");
    }

    public function queueStatus(MailingCampaign $campaign): JsonResponse
    {
        $campaign->refresh();
        $sentCount = $campaign->deliveries()->where('status', 'sent')->count();
        $failedCount = $campaign->deliveries()->where('status', 'failed')->count();
        $openedCount = $campaign->deliveries()->whereNotNull('opened_at')->count();
        $feedbackCount = $campaign->deliveries()->whereNotNull('feedback_status')->count();
        $queuedCount = $campaign->deliveries()->where('status', 'queued')->count();

        return response()->json([
            'campaign' => [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'status' => str_replace('_', ' ', $campaign->status),
                'recipient_count' => $campaign->recipient_count,
                'sent_count' => $sentCount,
                'failed_count' => $failedCount,
                'opened_count' => $openedCount,
                'feedback_count' => $feedbackCount,
                'queued_count' => $queuedCount,
                'updated_at' => $campaign->updated_at?->diffForHumans(),
            ],
            'deliveries' => $campaign->deliveries()
                ->where(function (Builder $query): void {
                    $query
                        ->where('status', '<>', 'sent')
                        ->orWhere('sent_at', '>=', now()->subSeconds(12));
                })
                ->with('user.profile')
                ->latest('updated_at')
                ->limit(27)
                ->get()
                ->map(fn (MailingDelivery $delivery): array => [
                    'id' => $delivery->id,
                    'email' => $delivery->email,
                    'user' => $delivery->user?->username ?? 'External',
                    'status' => $delivery->status,
                    'opened' => (bool) $delivery->opened_at,
                    'opened_at' => $delivery->opened_at?->diffForHumans(),
                    'open_count' => $delivery->open_count,
                    'feedback_status' => $delivery->feedback_status,
                    'feedback_subtype' => $delivery->feedback_subtype,
                    'time' => $delivery->sent_at?->diffForHumans() ?? $delivery->updated_at?->diffForHumans(),
                    'error' => $delivery->error,
                ]),
        ]);
    }

    public function open(MailingDelivery $delivery): Response
    {
        $delivery->forceFill([
            'opened_at' => $delivery->opened_at ?? now(),
            'last_opened_at' => now(),
            'open_count' => $delivery->open_count + 1,
        ])->save();

        return response(base64_decode('R0lGODlhAQABAPAAAP///wAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=='), 200, [
            'Content-Type' => 'image/gif',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    private function liveDeliveryWindow(MailingCampaign $campaign)
    {
        return $campaign->deliveries()
            ->where(function (Builder $query): void {
                $query
                    ->where('status', '<>', 'sent')
                    ->orWhere('sent_at', '>=', now()->subSeconds(12));
            })
            ->with('user.profile')
            ->latest('updated_at');
    }

    private function audienceQuery(string $type, array $filters): Builder
    {
        return app(MailingProtectionService::class)->allowQuery(User::query())
            ->when($type === 'unverified', fn (Builder $query) => $query->whereNull('email_verified_at'))
            ->when($type === 'role', fn (Builder $query) => $query->where('role', $filters['role'] ?? 'member'))
            ->when($type === 'status', fn (Builder $query) => $query->where('status', $filters['status'] ?? 'active'))
            ->when($type === 'country', fn (Builder $query) => $query->where('status', 'active'))
            ->when(! empty($filters['country_id']), function (Builder $query) use ($filters): void {
                $query->whereHas('profile', fn (Builder $profile) => $profile->where('country_id', $filters['country_id']));
            });
    }

    private function settings(): array
    {
        $settings = SiteSetting::whereIn('key', [
            'mailing.enabled',
            'mailing.reply_to',
            'mailing.footer',
            'mailing.max_recipients',
            'mailing.emails_per_3_minutes',
            'mailing.provider',
            'mailing.protection_mode',
            'mailing.allow_transactional_recovery',
            'mailing.block_free_bulk_domains',
        ])->pluck('value', 'key');

        return [
            'enabled' => (string) ($settings->get('mailing.enabled') ?? '0'),
            'reply_to' => (string) ($settings->get('mailing.reply_to') ?? ''),
            'footer' => (string) ($settings->get('mailing.footer') ?? 'Sirraty · Halal Social'),
            'max_recipients' => (string) ($settings->get('mailing.max_recipients') ?? '973'),
            'emails_per_3_minutes' => (string) ($settings->get('mailing.emails_per_3_minutes') ?? '25'),
            'provider' => (string) ($settings->get('mailing.provider') ?? 'default'),
            'protection_mode' => (string) ($settings->get('mailing.protection_mode') ?? 'strict'),
            'allow_transactional_recovery' => (string) ($settings->get('mailing.allow_transactional_recovery') ?? '1'),
            'block_free_bulk_domains' => (string) ($settings->get('mailing.block_free_bulk_domains') ?? '0'),
        ];
    }

    private function sesFeedbackSummary(): array
    {
        return [
            'events' => SesFeedbackEvent::count(),
            'bounced' => User::where('email_status', 'bounced')->count(),
            'complained' => User::where('email_status', 'complained')->count(),
            'suppressed' => User::whereNotNull('email_suppressed_at')->count(),
        ];
    }

    private function putSetting(string $key, string $value): void
    {
        SiteSetting::updateOrCreate(['key' => $key], ['value' => $value, 'is_public' => false]);
    }

    private function ensureDefaults(): void
    {
        foreach ($this->defaultTemplates() as $key => $template) {
            EmailTemplate::updateOrCreate(['key' => $key], $template);
        }

        $this->putSetting('mailing.enabled', $this->settings()['enabled']);
        $this->putSetting('mailing.footer', $this->settings()['footer']);
        $this->putSetting('mailing.max_recipients', $this->settings()['max_recipients']);
        $this->putSetting('mailing.emails_per_3_minutes', $this->settings()['emails_per_3_minutes']);
        $this->putSetting('mailing.provider', $this->settings()['provider']);
        $this->putSetting('mailing.protection_mode', $this->settings()['protection_mode']);
        $this->putSetting('mailing.allow_transactional_recovery', $this->settings()['allow_transactional_recovery']);
        $this->putSetting('mailing.block_free_bulk_domains', $this->settings()['block_free_bulk_domains']);
    }

    private function defaultTemplates(): array
    {
        return [
            'complete_signup' => [
                'subject' => 'Complete your Sirraty signup',
                'body' => $this->templateHtml(
                    'Complete your Sirraty signup',
                    'Assalamu alaikum {{name}}, your Sirraty account is ready. Confirm your email to protect your profile, recovery, and notifications.',
                    'Confirm Email',
                    '{{verification_url}}',
                    'You can sign in after confirmation from {{login_url}}.'
                ),
                'enabled' => true,
            ],
            'welcome' => [
                'subject' => 'Welcome to Sirraty',
                'body' => $this->templateHtml(
                    'Welcome to Sirraty',
                    'Assalamu alaikum {{name}}, your Sirraty profile is ready. Start with Interest, pages, groups, and local market posts.',
                    'Open Profile',
                    '{{profile_url}}',
                    'Your handle is {{handle}}.'
                ),
                'enabled' => true,
            ],
            'password_help' => [
                'subject' => 'Sirraty password help',
                'body' => $this->templateHtml(
                    'Password help',
                    'Assalamu alaikum {{name}}, use the private password help page to request a reset link for your Sirraty account.',
                    'Password Help',
                    '{{password_help_url}}',
                    'If you did not ask for this, you can ignore this email.'
                ),
                'enabled' => true,
            ],
            'security_notice' => [
                'subject' => 'Sirraty account security notice',
                'body' => $this->templateHtml(
                    'Account security notice',
                    'Assalamu alaikum {{name}}, this is a security notice for your Sirraty account {{handle}}.',
                    'Review Account',
                    '{{login_url}}',
                    'Keep your email, password, and recovery details up to date.'
                ),
                'enabled' => true,
            ],
            'admin_warning' => [
                'subject' => 'Sirraty account notice',
                'body' => $this->templateHtml(
                    'Account notice',
                    'Assalamu alaikum {{name}}, we need your attention on your Sirraty account. Please review your profile and recent activity.',
                    'Review Profile',
                    '{{profile_url}}',
                    'This notice is sent to keep Sirraty safe and respectful.'
                ),
                'enabled' => true,
            ],
            'announcement' => [
                'subject' => 'Sirraty update',
                'body' => $this->templateHtml(
                    'Sirraty update',
                    'Assalamu alaikum {{name}}, Sirraty has a new platform update for your account and community tools.',
                    'Open Sirraty',
                    '{{login_url}}',
                    'Thank you for being part of Sirraty.'
                ),
                'enabled' => true,
            ],
        ];
    }

    private function templateHtml(string $title, string $message, string $button, string $url, string $note): string
    {
        return <<<HTML
<p style="margin:0 0 19px;font-size:15px;line-height:1.57;color:#435047">{$message}</p>
<p style="margin:0 0 19px"><a href="{$url}" style="display:inline-block;padding:13px 19px;background:#247553;color:#ffffff;text-decoration:none;border-radius:7px;font-weight:700">{$button}</a></p>
<p style="margin:0 0 19px;font-size:13px;line-height:1.57;color:#647067">{$note}</p>
<p style="margin:0;padding-top:19px;border-top:1px solid #d9d1c3;font-size:13px;line-height:1.57;color:#647067">If the button does not open, copy this link:<br><a href="{$url}" style="color:#247553;word-break:break-all">{$url}</a></p>
HTML;
    }
}
