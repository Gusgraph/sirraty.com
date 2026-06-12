<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Controllers/App/FollowController.php
// =====================================================

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function store(Request $request, User $user): RedirectResponse|JsonResponse
    {
        abort_if($request->user()->is($user), 422);
        Follow::firstOrCreate(['follower_id' => $request->user()->id, 'followed_id' => $user->id]);

        if ($request->expectsJson()) {
            return response()->json([
                'followed' => true,
                'label' => 'Following',
                'action' => route('app.unfollow', $user),
                'method' => 'DELETE',
            ]);
        }

        return back()->with('status', 'Followed.');
    }

    public function destroy(Request $request, User $user): RedirectResponse|JsonResponse
    {
        Follow::where(['follower_id' => $request->user()->id, 'followed_id' => $user->id])->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'followed' => false,
                'label' => 'Follow',
                'action' => route('app.follow', $user),
                'method' => 'POST',
            ]);
        }

        return back()->with('status', 'Unfollowed.');
    }
}
