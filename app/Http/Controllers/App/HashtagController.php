<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Controllers/App/HashtagController.php
// =====================================================

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Hashtag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HashtagController extends Controller
{
    public function show(Request $request, Hashtag $hashtag): View
    {
        $followingIds = $request->user()->following()->pluck('followed_id');

        $posts = $hashtag->posts()
            ->with(['comments.user.profile', 'media', 'user.profile', 'hashtags'])
            ->withCount([
                'comments',
                'reactions as likes_count' => fn ($query) => $query->where('type', 'like'),
                'reactions as dislikes_count' => fn ($query) => $query->where('type', 'dislike'),
            ])
            ->withExists([
                'reactions as liked_by_viewer' => fn ($query) => $query->where('user_id', $request->user()->id)->where('type', 'like'),
                'reactions as disliked_by_viewer' => fn ($query) => $query->where('user_id', $request->user()->id)->where('type', 'dislike'),
                'savedPosts as saved_by_viewer' => fn ($query) => $query->where('user_id', $request->user()->id),
            ])
            ->where('status', 'published')
            ->whereDoesntHave('hiddenByUsers', fn ($query) => $query->where('user_id', $request->user()->id))
            ->where(function ($query) use ($request, $followingIds): void {
                $query->where('visibility', 'public')
                    ->orWhere('user_id', $request->user()->id)
                    ->orWhere(function ($inner) use ($followingIds): void {
                        $inner->where('visibility', 'followers')->whereIn('user_id', $followingIds);
                    });
            })
            ->latest('published_at')
            ->paginate(15);

        $rankedTags = Hashtag::where('usage_count', '>', 0)
            ->orderByDesc('usage_count')
            ->orderByDesc('last_used_at')
            ->limit(19)
            ->get();

        return view('app.hashtag', compact('hashtag', 'posts', 'rankedTags'));
    }

    public function publicShow(Hashtag $hashtag): View
    {
        $posts = $hashtag->posts()
            ->with(['media', 'user.profile', 'hashtags'])
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->latest('published_at')
            ->limit(11)
            ->get();

        $rankedTags = Hashtag::where('usage_count', '>', 0)
            ->orderByDesc('usage_count')
            ->orderByDesc('last_used_at')
            ->limit(19)
            ->get();

        return view('public.hashtag', compact('hashtag', 'posts', 'rankedTags'));
    }
}
