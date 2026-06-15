<?php

namespace App\Mail;

use App\Models\RecoveryDelivery;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class RecoveryCampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $mailSubject,
        public string $body,
        public RecoveryDelivery $delivery,
        public ?string $replyToAddress = null,
    ) {
    }

    public function envelope(): Envelope
    {
        $from = config('mail.recovery_from.address')
            ? new Address(config('mail.recovery_from.address'), config('mail.recovery_from.name'))
            : null;

        return new Envelope(
            from: $from,
            subject: $this->mailSubject,
            replyTo: $this->replyToAddress ? [$this->replyToAddress] : [],
        );
    }

    public function content(): Content
    {
        $unsubscribeUrl = URL::signedRoute('mailing.recovery.unsubscribe', [
            'delivery' => $this->delivery->id,
            'token' => $this->delivery->unsubscribe_token,
        ]);

        return new Content(
            view: 'emails.recovery.template',
            text: 'emails.recovery.text',
            with: [
                'subjectLine' => $this->mailSubject,
                'body' => $this->body,
                'textBody' => $this->plainText($this->body),
                'unsubscribeUrl' => $unsubscribeUrl,
            ],
        );
    }

    public function headers(): \Illuminate\Mail\Mailables\Headers
    {
        $unsubscribeUrl = URL::signedRoute('mailing.recovery.unsubscribe', [
            'delivery' => $this->delivery->id,
            'token' => $this->delivery->unsubscribe_token,
        ]);

        return new \Illuminate\Mail\Mailables\Headers(
            text: [
                'X-Sirraty-Mail' => 'recovery-campaign',
                'X-Entity-Ref-ID' => 'sirraty-recovery-'.Str::uuid()->toString(),
                'List-Unsubscribe' => '<'.$unsubscribeUrl.'>',
                'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
            ],
        );
    }

    private function plainText(string $value): string
    {
        $value = str_replace(['<br>', '<br/>', '<br />', '</p>', '</div>'], "\n", $value);
        $value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = preg_replace('/\{\{[^}]+}}/', '', $value) ?? $value;
        $value = preg_replace('/\s+/', ' ', $value) ?? $value;

        return trim($value);
    }
}
