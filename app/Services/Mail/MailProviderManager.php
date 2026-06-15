<?php

namespace App\Services\Mail;

use App\Mail\AdminTemplateMail;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Mail;

class MailProviderManager
{
    public function providers(): array
    {
        return [
            'default' => [
                'label' => 'Laravel default',
                'mailer' => config('mail.default'),
                'available' => true,
                'description' => 'Uses the current MAIL_MAILER setting.',
            ],
            'smtp' => [
                'label' => 'SMTP',
                'mailer' => 'smtp',
                'available' => array_key_exists('smtp', config('mail.mailers', [])),
                'description' => 'Generic SMTP transport.',
            ],
            'ses' => [
                'label' => 'Amazon SES',
                'mailer' => 'ses',
                'available' => array_key_exists('ses', config('mail.mailers', [])),
                'description' => 'AWS SES transport when the account is healthy.',
            ],
            'postmark' => [
                'label' => 'Postmark',
                'mailer' => 'postmark',
                'available' => array_key_exists('postmark', config('mail.mailers', [])) && (bool) config('services.postmark.token'),
                'description' => 'Transactional recovery provider option.',
            ],
            'mailgun' => [
                'label' => 'Mailgun',
                'mailer' => 'mailgun',
                'available' => array_key_exists('mailgun', config('mail.mailers', [])) && (bool) config('services.mailgun.domain'),
                'description' => 'API/SMTP provider option with webhooks.',
            ],
            'mailcow' => [
                'label' => 'Mailcow recovery',
                'mailer' => 'mailcow',
                'available' => array_key_exists('mailcow', config('mail.mailers', [])) && (bool) config('mail.mailers.mailcow.host') && (bool) config('mail.mailers.mailcow.username'),
                'description' => 'Dedicated Sirraty recovery SMTP provider.',
            ],
            'inmotion' => [
                'label' => 'InMotion recovery',
                'mailer' => 'inmotion',
                'available' => array_key_exists('inmotion', config('mail.mailers', [])) && (bool) config('mail.mailers.inmotion.host') && (bool) config('mail.mailers.inmotion.username'),
                'description' => 'InMotion Hosting SMTP for recovery campaigns.',
            ],
            'recovery' => [
                'label' => 'Recovery failover',
                'mailer' => 'recovery',
                'available' => array_key_exists('recovery', config('mail.mailers', [])),
                'description' => 'Uses RECOVERY_MAILERS in priority order.',
            ],
            'log' => [
                'label' => 'Log only',
                'mailer' => 'log',
                'available' => array_key_exists('log', config('mail.mailers', [])),
                'description' => 'Writes mail to logs for dry-run testing.',
            ],
        ];
    }

    public function selectedKey(): string
    {
        $key = (string) (SiteSetting::where('key', 'mailing.provider')->value('value') ?: 'default');

        return array_key_exists($key, $this->providers()) ? $key : 'default';
    }

    public function selected(): array
    {
        $providers = $this->providers();
        $key = $this->selectedKey();

        return ['key' => $key] + $providers[$key];
    }

    public function mailerName(): string
    {
        $selected = $this->selected();

        return $selected['mailer'] ?: config('mail.default');
    }

    public function sendAdminTemplate(string $to, AdminTemplateMail $mail): void
    {
        Mail::mailer($this->mailerName())->to($to)->send($mail);
    }
}
