<?php

namespace App\Http\Controllers;

use App\Models\RecoveryDelivery;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecoveryUnsubscribeController extends Controller
{
    public function __invoke(Request $request, RecoveryDelivery $delivery, string $token): View
    {
        abort_unless($request->hasValidSignature() && hash_equals($delivery->unsubscribe_token, $token), 403);

        $delivery->update(['status' => 'unsubscribed', 'unsubscribed_at' => now(), 'error' => 'User unsubscribed from recovery mail.']);
        $delivery->user?->forceFill([
            'email_status' => 'unsubscribed',
            'email_suppressed_at' => now(),
            'email_suppression_reason' => 'Recovery unsubscribe',
        ])->save();
        $delivery->campaign?->increment('unsubscribed_count');

        return view('public.unsubscribe', ['email' => $delivery->email]);
    }
}
