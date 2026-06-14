<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Mail/AdminTemplateMail.php
// =====================================================

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class AdminTemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $mailSubject,
        public string $body,
        private string $preheader = 'A Sirraty account message.',
        public ?string $replyToAddress = null,
        public ?string $footer = null,
        public ?int $deliveryId = null,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mailSubject,
            replyTo: $this->replyToAddress ? [$this->replyToAddress] : [],
        );
    }

    public function content(): Content
    {
        $textBody = $this->plainText($this->body);

        return new Content(
            view: 'emails.admin.template',
            text: 'emails.admin.text',
            with: [
                'subjectLine' => $this->mailSubject,
                'body' => $this->body,
                'textBody' => $textBody,
                'preheader' => $this->plainText($this->preheader ?: $textBody, 173),
                'footer' => $this->footer,
                'openUrl' => $this->deliveryId ? \Illuminate\Support\Facades\URL::signedRoute('mailing.open', ['delivery' => $this->deliveryId]) : null,
            ],
        );
    }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'X-Sirraty-Mail' => 'admin-template',
                'X-Entity-Ref-ID' => 'sirraty-'.Str::uuid()->toString(),
            ],
        );
    }

    private function plainText(string $value, int $limit = 997): string
    {
        $value = str_replace(['<br>', '<br/>', '<br />', '</p>', '</div>'], "\n", $value);
        $value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = preg_replace('/\{\{[^}]+}}/', '', $value) ?? $value;
        $value = preg_replace('/\s+/', ' ', $value) ?? $value;

        return Str::limit(trim($value), $limit);
    }
}
