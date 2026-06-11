<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Notifications/SirratyVerifyEmail.php
// =====================================================

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class SirratyVerifyEmail extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirm your Sirraty email')
            ->view('emails.auth.action', [
                'title' => 'Confirm your email',
                'preheader' => 'Confirm your email to protect your Sirraty account.',
                'intro' => 'Welcome to Sirraty. Confirm this email address so your account recovery and security notices reach you.',
                'actionLabel' => 'Confirm email',
                'actionUrl' => $this->verificationUrl($notifiable),
                'note' => 'If you did not create a Sirraty account, no action is needed.',
            ]);
    }
}
