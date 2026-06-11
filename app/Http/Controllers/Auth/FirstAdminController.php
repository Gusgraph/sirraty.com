<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Controllers/Auth/FirstAdminController.php
// =====================================================

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PrivacySetting;
use App\Models\Profile;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class FirstAdminController extends Controller
{
    public function create(): View
    {
        abort_if(User::whereIn('role', ['admin', 'owner'])->exists(), 404);

        return view('auth.first-admin');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_if(User::whereIn('role', ['admin', 'owner'])->exists(), 404);

        $data = $request->validate([
            'setup_password' => ['required', 'string'],
            'name' => ['required', 'string', 'max:73'],
            'username' => ['required', 'alpha_dash:ascii', 'min:3', 'max:73', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(11)->mixedCase()->numbers()],
        ]);

        abort_unless(hash_equals((string) config('services.sirraty.setup_password'), $data['setup_password']), 403);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'owner',
            'email_verified_at' => now(),
        ]);

        Profile::create(['user_id' => $user->id, 'display_name' => $user->name]);
        PrivacySetting::create(['user_id' => $user->id]);
        SiteSetting::updateOrCreate(['key' => 'first_admin_created_at'], ['value' => now()->toISOString()]);

        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }
}
