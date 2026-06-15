<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MailingAddressUnsubscribeController extends Controller
{
    public function __invoke(Request $request): View
    {
        abort_unless($request->hasValidSignature(), 403);

        $email = strtolower((string) $request->query('email'));
        abort_unless(filter_var($email, FILTER_VALIDATE_EMAIL), 422);

        User::whereRaw('LOWER(email) = ?', [$email])->update([
            'email_status' => 'unsubscribed',
            'email_suppressed_at' => now(),
            'email_suppression_reason' => 'Mailing unsubscribe',
            'updated_at' => now(),
        ]);

        return view('public.unsubscribe', ['email' => $email]);
    }
}
