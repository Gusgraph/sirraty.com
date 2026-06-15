<?php

namespace App\Services\Mail;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Str;

class MailingProtectionService
{
    public function settings(): array
    {
        $settings = SiteSetting::whereIn('key', [
            'mailing.enabled',
            'mailing.protection_mode',
            'mailing.allow_transactional_recovery',
            'mailing.block_free_bulk_domains',
        ])->pluck('value', 'key');

        return [
            'bulk_enabled' => (string) ($settings->get('mailing.enabled') ?? '0') === '1',
            'mode' => (string) ($settings->get('mailing.protection_mode') ?? 'strict'),
            'transactional_recovery' => (string) ($settings->get('mailing.allow_transactional_recovery') ?? '1') === '1',
            'block_free_bulk_domains' => (string) ($settings->get('mailing.block_free_bulk_domains') ?? '0') === '1',
        ];
    }

    public function inspect(string $email, ?User $user = null, string $purpose = 'bulk'): array
    {
        $email = strtolower(trim($email));
        $settings = $this->settings();
        $reasons = [];

        if ($purpose === 'bulk' && ! $settings['bulk_enabled']) {
            $reasons[] = 'Bulk mailing is disabled.';
        }

        if ($purpose !== 'bulk' && ! $settings['transactional_recovery']) {
            $reasons[] = 'Transactional recovery mail is disabled.';
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $reasons[] = 'Invalid email format.';
        }

        if (Str::endsWith($email, '.test')) {
            $reasons[] = 'Test email domain is excluded.';
        }

        if ($this->isDisposable($email)) {
            $reasons[] = 'Disposable email domain is blocked.';
        }

        if ($purpose === 'bulk' && $settings['block_free_bulk_domains'] && $this->isFreeMailbox($email)) {
            $reasons[] = 'Free mailbox domains are blocked for bulk recovery.';
        }

        if ($user?->email_suppressed_at) {
            $reasons[] = 'User is suppressed by feedback.';
        }

        if (in_array($user?->email_status, ['bounced', 'complained', 'suppressed'], true)) {
            $reasons[] = 'User email status is '.$user->email_status.'.';
        }

        return [
            'allowed' => $reasons === [],
            'email' => $email,
            'reasons' => $reasons,
            'reason' => implode(' ', $reasons),
        ];
    }

    public function allowQuery($query)
    {
        return $query
            ->whereNotNull('email')
            ->where('email', 'not like', '%.test')
            ->whereNull('email_suppressed_at')
            ->where('email_status', 'active');
    }

    public function summary(): array
    {
        $settings = $this->settings();

        return [
            'bulk_enabled' => $settings['bulk_enabled'],
            'mode' => $settings['mode'],
            'transactional_recovery' => $settings['transactional_recovery'],
            'block_free_bulk_domains' => $settings['block_free_bulk_domains'],
            'disposable_domains' => count($this->disposableDomains()),
            'free_mailbox_domains' => count($this->freeMailboxDomains()),
        ];
    }

    private function isDisposable(string $email): bool
    {
        return in_array($this->domain($email), $this->disposableDomains(), true);
    }

    private function isFreeMailbox(string $email): bool
    {
        return in_array($this->domain($email), $this->freeMailboxDomains(), true);
    }

    private function domain(string $email): string
    {
        return strtolower(Str::afterLast($email, '@'));
    }

    private function disposableDomains(): array
    {
        return ['mailinator.com', '10minutemail.com', 'guerrillamail.com', 'tempmail.com', 'yopmail.com', 'example.test'];
    }

    private function freeMailboxDomains(): array
    {
        return ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'live.com', 'aol.com', 'icloud.com', 'proton.me', 'protonmail.com'];
    }
}
