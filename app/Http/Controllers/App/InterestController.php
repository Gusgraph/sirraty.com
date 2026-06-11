<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Controllers/App/InterestController.php
// =====================================================

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InterestController extends Controller
{
    public function __invoke(Request $request): View
    {
        $scope = $request->query('scope', 'all');
        $followingIds = $request->user()->following()->pluck('followed_id');

        $posts = Post::with(['media', 'user.profile'])
            ->where('status', 'published')
            ->whereDoesntHave('hiddenByUsers', fn ($query) => $query->where('user_id', $request->user()->id))
            ->when($scope === 'following', fn ($query) => $query->whereIn('user_id', $followingIds))
            ->where(function ($query) use ($request, $followingIds): void {
                $query->where('visibility', 'public')
                    ->orWhere('user_id', $request->user()->id)
                    ->orWhere(function ($inner) use ($followingIds): void {
                        $inner->where('visibility', 'followers')->whereIn('user_id', $followingIds);
                    });
            })
            ->latest('published_at')
            ->paginate(15);

        return view('app.interest', compact('posts', 'scope'));
    }
}
