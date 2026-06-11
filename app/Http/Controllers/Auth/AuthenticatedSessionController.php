<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Controllers/Auth/AuthenticatedSessionController.php
// =====================================================

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('welcome', ['authModal' => 'signin']);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('signin', [
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Enter your username, email, or phone.',
            'password.required' => 'Enter your password.',
        ]);

        $login = trim($data['login']);
        $throttleKey = Str::lower($login).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'login' => 'Too many attempts. Please wait and try again.',
            ])->errorBag('signin')->redirectTo(route('login'));
        }

        $phone = preg_replace('/\D+/', '', $login);
        $user = User::query()
            ->where('email', $login)
            ->orWhere('username', $login)
            ->when($phone !== '', fn ($query) => $query->orWhere('phone', $phone))
            ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            RateLimiter::hit($throttleKey, 73);

            Log::notice('Signin failed', [
                'login' => $login,
                'matched_user' => (bool) $user,
                'ip' => $request->ip(),
            ]);

            throw ValidationException::withMessages(['login' => 'These details did not match.'])
                ->errorBag('signin')
                ->redirectTo(route('login'));
        }

        if ($user->status !== 'active') {
            RateLimiter::hit($throttleKey, 73);

            throw ValidationException::withMessages(['login' => 'This account cannot sign in right now.'])
                ->errorBag('signin')
                ->redirectTo(route('login'));
        }

        RateLimiter::clear($throttleKey);
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $request->user()->forceFill(['last_seen_at' => now()])->save();

        return redirect()->route('app.interest', status: 303);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
