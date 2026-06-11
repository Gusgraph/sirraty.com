<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Controllers/App/PrivacyController.php
// =====================================================

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrivacyController extends Controller
{
    public function edit(Request $request): View
    {
        return view('app.privacy', ['settings' => $request->user()->privacySetting()->firstOrCreate([])]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'profile_visibility' => ['required', 'in:public,followers,private,hidden'],
            'post_default_visibility' => ['required', 'in:public,followers,private,hidden'],
            'followers_visibility' => ['required', 'in:public,followers,private,hidden'],
            'following_visibility' => ['required', 'in:public,followers,private,hidden'],
            'location_visibility' => ['required', 'in:public,followers,private,hidden'],
            'search_visibility' => ['required', 'boolean'],
            'messaging_permission' => ['required', 'in:everyone,followers,following,no_one'],
            'tagging_permission' => ['required', 'in:public,followers,private,hidden'],
            'mention_permission' => ['required', 'in:public,followers,private,hidden'],
            'comment_permission' => ['required', 'in:public,followers,private,hidden'],
            'market_contact_permission' => ['required', 'in:everyone,followers,following,no_one'],
            'page_visibility' => ['required', 'in:public,followers,private,hidden'],
            'group_visibility' => ['required', 'in:public,followers,private,hidden'],
        ]);

        $request->user()->privacySetting()->updateOrCreate(['user_id' => $request->user()->id], $data);

        return back()->with('status', 'Privacy saved.');
    }
}
