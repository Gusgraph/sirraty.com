<?php

namespace App\Http\Controllers;

use App\Models\RecoveryDelivery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MailcowRecoveryWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $token = config('services.mailcow.recovery_webhook_token');
        abort_if($token && ! hash_equals((string) $token, (string) ($request->bearerToken() ?: $request->query('token'))), Response::HTTP_FORBIDDEN);

        $data = $request->validate([
            'email' => ['required', 'email'],
            'event' => ['required', 'in:bounce,bounced,complaint,complained,unsubscribe,unsubscribed'],
            'diagnostic' => ['nullable', 'string', 'max:1000'],
        ]);

        $status = match ($data['event']) {
            'complaint', 'complained' => 'complained',
            'unsubscribe', 'unsubscribed' => 'unsubscribed',
            default => 'bounced',
        };

        $delivery = RecoveryDelivery::where('email', strtolower($data['email']))->latest('id')->first();
        if (! $delivery) {
            return response()->json(['status' => 'ignored', 'reason' => 'delivery_not_found']);
        }

        $delivery->update([
            'status' => $status,
            'feedback_at' => now(),
            'unsubscribed_at' => $status === 'unsubscribed' ? now() : $delivery->unsubscribed_at,
            'error' => $data['diagnostic'] ?? 'Mailcow feedback: '.$status,
        ]);

        $delivery->user?->forceFill([
            'email_status' => $status,
            'email_suppressed_at' => now(),
            'email_suppression_reason' => $data['diagnostic'] ?? 'Mailcow recovery '.$status,
        ])->save();

        $campaign = $delivery->campaign;
        if ($campaign) {
            $campaign->update([
                'bounced_count' => $campaign->deliveries()->where('status', 'bounced')->count(),
                'complained_count' => $campaign->deliveries()->where('status', 'complained')->count(),
                'unsubscribed_count' => $campaign->deliveries()->where('status', 'unsubscribed')->count(),
                'suppressed_count' => $campaign->deliveries()->where('status', 'suppressed')->count(),
            ]);

            $sent = max(1, $campaign->deliveries()->whereIn('status', ['sent', 'bounced', 'complained'])->count());
            $bad = $campaign->deliveries()->whereIn('status', ['bounced', 'complained'])->count();
            if ($sent >= 25 && (($bad / $sent) * 100) >= (float) $campaign->bounce_stop_rate && in_array($campaign->status, ['queued', 'sending', 'waiting'], true)) {
                $campaign->update(['status' => 'stopped', 'stopped_at' => now(), 'stop_reason' => 'Mailcow feedback bounce stop rate reached.']);
            }
        }

        return response()->json(['status' => 'recorded', 'delivery_id' => $delivery->id, 'event' => $status]);
    }
}
