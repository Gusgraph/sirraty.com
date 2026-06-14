<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Controllers/AwsSesWebhookController.php
// =====================================================

namespace App\Http\Controllers;

use App\Models\MailingDelivery;
use App\Models\SesFeedbackEvent;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AwsSesWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        abort_if(! is_array($payload), Response::HTTP_BAD_REQUEST, 'Invalid SNS payload.');
        abort_if(! $this->tokenIsValid($request), Response::HTTP_FORBIDDEN, 'Invalid webhook token.');
        abort_if(! $this->signatureIsValid($payload), Response::HTTP_FORBIDDEN, 'Invalid SNS signature.');

        $type = (string) ($payload['Type'] ?? '');
        if ($type === 'SubscriptionConfirmation') {
            $this->confirmSubscription($payload);

            return response()->json(['status' => 'confirmed']);
        }

        if ($type !== 'Notification') {
            return response()->json(['status' => 'ignored']);
        }

        $message = json_decode((string) ($payload['Message'] ?? ''), true);
        if (! is_array($message)) {
            Log::warning('SES SNS notification did not include JSON message.', ['sns_message_id' => $payload['MessageId'] ?? null]);

            return response()->json(['status' => 'ignored']);
        }

        $notificationType = strtolower((string) ($message['notificationType'] ?? $message['eventType'] ?? ''));
        if (! in_array($notificationType, ['bounce', 'complaint'], true)) {
            return response()->json(['status' => 'ignored']);
        }

        $handled = $notificationType === 'bounce'
            ? $this->handleBounce($payload, $message)
            : $this->handleComplaint($payload, $message);

        return response()->json(['status' => 'recorded', 'count' => $handled]);
    }

    private function handleBounce(array $snsPayload, array $message): int
    {
        $bounce = $message['bounce'] ?? [];
        $recipients = $bounce['bouncedRecipients'] ?? [];
        $subtype = trim(($bounce['bounceType'] ?? 'Bounce').' '.($bounce['bounceSubType'] ?? ''));
        $occurredAt = $this->time($bounce['timestamp'] ?? $message['mail']['timestamp'] ?? null);

        $count = 0;
        foreach ($recipients as $recipient) {
            $email = strtolower((string) ($recipient['emailAddress'] ?? ''));
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $this->recordFeedback(
                $email,
                'bounced',
                $subtype ?: null,
                $recipient['status'] ?? null,
                $recipient['diagnosticCode'] ?? null,
                $snsPayload,
                $message,
                $occurredAt,
            );
            $count++;
        }

        return $count;
    }

    private function handleComplaint(array $snsPayload, array $message): int
    {
        $complaint = $message['complaint'] ?? [];
        $recipients = $complaint['complainedRecipients'] ?? [];
        $subtype = $complaint['complaintFeedbackType'] ?? $complaint['complaintSubType'] ?? 'Complaint';
        $occurredAt = $this->time($complaint['timestamp'] ?? $message['mail']['timestamp'] ?? null);

        $count = 0;
        foreach ($recipients as $recipient) {
            $email = strtolower((string) ($recipient['emailAddress'] ?? ''));
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $this->recordFeedback(
                $email,
                'complained',
                $subtype ?: null,
                null,
                null,
                $snsPayload,
                $message,
                $occurredAt,
            );
            $count++;
        }

        return $count;
    }

    private function recordFeedback(
        string $email,
        string $status,
        ?string $subtype,
        ?string $recipientStatus,
        ?string $diagnosticCode,
        array $snsPayload,
        array $message,
        ?Carbon $occurredAt,
    ): void {
        $user = User::where('email', $email)->first();
        $delivery = MailingDelivery::where('email', $email)->latest('id')->first();
        $snsMessageId = $snsPayload['MessageId'] ?? null;
        $sesMessageId = $message['mail']['messageId'] ?? null;

        SesFeedbackEvent::updateOrCreate(
            [
                'sns_message_id' => $snsMessageId,
                'email' => $email,
                'feedback_type' => $status,
            ],
            [
                'user_id' => $user?->id,
                'mailing_delivery_id' => $delivery?->id,
                'feedback_subtype' => $subtype,
                'recipient_status' => $recipientStatus,
                'diagnostic_code' => $diagnosticCode,
                'ses_message_id' => $sesMessageId,
                'occurred_at' => $occurredAt,
                'payload' => $message,
            ],
        );

        $delivery?->forceFill([
            'feedback_status' => $status,
            'feedback_subtype' => $subtype,
            'feedback_at' => $occurredAt ?? now(),
            'feedback_payload' => $message,
        ])->save();

        $user?->forceFill([
            'email_status' => $status,
            'email_suppressed_at' => $occurredAt ?? now(),
            'email_suppression_reason' => $subtype ?: $status,
        ])->save();
    }

    private function tokenIsValid(Request $request): bool
    {
        $token = config('services.ses.sns_webhook_token');

        return ! $token || hash_equals((string) $token, (string) $request->query('token'));
    }

    private function signatureIsValid(array $payload): bool
    {
        if (! filter_var(config('services.ses.sns_verify_signature'), FILTER_VALIDATE_BOOL)) {
            return true;
        }

        if (empty($payload['Signature']) || empty($payload['SigningCertURL'])) {
            return false;
        }

        $certUrl = (string) $payload['SigningCertURL'];
        $host = parse_url($certUrl, PHP_URL_HOST);
        $scheme = parse_url($certUrl, PHP_URL_SCHEME);
        $path = parse_url($certUrl, PHP_URL_PATH);
        if ($scheme !== 'https' || ! $host || ! Str::endsWith($host, '.amazonaws.com') || ! Str::contains($host, 'sns.') || ! Str::endsWith((string) $path, '.pem')) {
            return false;
        }

        $certificate = Http::timeout(7)->get($certUrl)->body();
        if ($certificate === '') {
            return false;
        }

        $algorithm = ((string) ($payload['SignatureVersion'] ?? '1')) === '2' ? OPENSSL_ALGO_SHA256 : OPENSSL_ALGO_SHA1;

        return openssl_verify($this->signatureString($payload), base64_decode((string) $payload['Signature']), $certificate, $algorithm) === 1;
    }

    private function signatureString(array $payload): string
    {
        $fields = ($payload['Type'] ?? '') === 'Notification'
            ? ['Message', 'MessageId', 'Subject', 'Timestamp', 'TopicArn', 'Type']
            : ['Message', 'MessageId', 'SubscribeURL', 'Timestamp', 'Token', 'TopicArn', 'Type'];

        $string = '';
        foreach ($fields as $field) {
            if (array_key_exists($field, $payload)) {
                $string .= $field."\n".$payload[$field]."\n";
            }
        }

        return $string;
    }

    private function confirmSubscription(array $payload): void
    {
        if (! filter_var(config('services.ses.sns_auto_confirm'), FILTER_VALIDATE_BOOL) || empty($payload['SubscribeURL'])) {
            Log::info('SES SNS subscription confirmation received.', ['topic' => $payload['TopicArn'] ?? null]);

            return;
        }

        Http::timeout(7)->get((string) $payload['SubscribeURL']);
        Log::info('SES SNS subscription confirmation requested.', ['topic' => $payload['TopicArn'] ?? null]);
    }

    private function time(?string $value): ?Carbon
    {
        return $value ? Carbon::parse($value) : null;
    }
}
