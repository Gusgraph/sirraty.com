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
use App\Models\Hashtag;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InterestController extends Controller
{
    public function __invoke(Request $request): View
    {
        $viewer = $request->user();
        $scope = $viewer ? $request->query('scope', 'all') : 'all';
        $followingIds = $viewer ? $viewer->following()->pluck('followed_id') : collect();
        $viewerId = $viewer?->id ?? 0;

        $posts = Post::with([
            'comments.media',
            'comments.user.profile',
            'media',
            'user.profile',
        ])
            ->withCount([
                'comments',
                'reactions as likes_count' => fn ($query) => $query->where('type', 'like'),
                'reactions as dislikes_count' => fn ($query) => $query->where('type', 'dislike'),
            ])
            ->withExists([
                'reactions as liked_by_viewer' => fn ($query) => $query->where('user_id', $viewerId)->where('type', 'like'),
                'reactions as disliked_by_viewer' => fn ($query) => $query->where('user_id', $viewerId)->where('type', 'dislike'),
                'savedPosts as saved_by_viewer' => fn ($query) => $query->where('user_id', $viewerId),
            ])
            ->where('status', 'published')
            ->when($viewer, fn ($query) => $query->whereDoesntHave('hiddenByUsers', fn ($hidden) => $hidden->where('user_id', $viewerId)))
            ->when($scope === 'following', fn ($query) => $query->whereIn('user_id', $followingIds))
            ->where(function ($query) use ($viewer, $viewerId, $followingIds): void {
                $query->where('visibility', 'public');

                if ($viewer) {
                    $query->orWhere('user_id', $viewerId)
                        ->orWhere(function ($inner) use ($followingIds): void {
                            $inner->where('visibility', 'followers')->whereIn('user_id', $followingIds);
                        });
                }
            })
            ->latest('published_at')
            ->paginate(15);

        $rankedTags = Hashtag::where('usage_count', '>', 0)
            ->orderByDesc('usage_count')
            ->orderByDesc('last_used_at')
            ->limit(19)
            ->get();

        return view('app.interest', compact('posts', 'scope', 'rankedTags'));
    }
}
