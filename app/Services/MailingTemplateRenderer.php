<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Services/MailingTemplateRenderer.php
// =====================================================

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class MailingTemplateRenderer
{
    public function render(string $content, ?User $user = null): string
    {
        $values = $this->values($user);

        return preg_replace_callback('/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/', function (array $match) use ($values): string {
            return (string) ($values[$match[1]] ?? $match[0]);
        }, $content) ?? $content;
    }

    public function values(?User $user = null): array
    {
        $username = $user?->username ?: 'member';
        $profileUrl = $user ? route('profile.show', $user) : url('/');
        $verifyUrl = $user ? URL::temporarySignedRoute(
            'verification.verify',
            now()->addDays(9),
            ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]
        ) : route('verification.notice');

        return [
            'app_name' => config('app.name', 'Sirraty'),
            'name' => $user?->name ?: 'Sirraty member',
            'username' => $username,
            'handle' => '@'.Str::of($username)->ltrim('@'),
            'email' => $user?->email ?: '',
            'profile_url' => $profileUrl,
            'verification_url' => $verifyUrl,
            'login_url' => route('login'),
            'signup_url' => route('register'),
            'password_help_url' => route('password.request'),
            'home_url' => url('/'),
        ];
    }
}
