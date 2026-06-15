<?php

namespace App\Http\Controllers;

use App\Models\MailingDelivery;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MailingUnsubscribeController extends Controller
{
    public function __invoke(Request $request, MailingDelivery $delivery): View
    {
        abort_unless($request->hasValidSignature(), 403);

        $delivery->update([
            'status' => 'unsubscribed',
            'error' => 'User unsubscribed from mailing campaign.',
            'updated_at' => now(),
        ]);

        $delivery->user?->forceFill([
            'email_status' => 'unsubscribed',
            'email_suppressed_at' => now(),
            'email_suppression_reason' => 'Mailing unsubscribe',
        ])->save();

        return view('public.unsubscribe', ['email' => $delivery->email]);
    }
}
