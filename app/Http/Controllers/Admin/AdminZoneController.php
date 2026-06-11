<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Controllers/Admin/AdminZoneController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Group;
use App\Models\Location;
use App\Models\MarketListing;
use App\Models\ModerationCase;
use App\Models\ModerationWord;
use App\Models\Page;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\View\View;

class AdminZoneController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'counts' => [
                'Users' => User::count(),
                'Profiles' => User::whereHas('profile')->count(),
                'Posts' => Post::count(),
                'Comments' => Comment::count(),
                'Pages' => Page::count(),
                'Groups' => Group::count(),
                'Market listings' => MarketListing::count(),
                'Reports' => Report::count(),
                'Moderation cases' => ModerationCase::count(),
                'Word filters' => ModerationWord::count(),
                'Locations' => Location::count(),
                'Categories' => Category::count(),
            ],
            'reports' => Report::latest()->limit(11)->get(),
        ]);
    }

    public function section(string $section): View
    {
        $map = [
            'users' => User::class,
            'profiles' => User::class,
            'posts' => Post::class,
            'comments' => Comment::class,
            'pages' => Page::class,
            'groups' => Group::class,
            'market-listings' => MarketListing::class,
            'reports' => Report::class,
            'moderation-queue' => ModerationCase::class,
            'word-filters' => ModerationWord::class,
            'locations' => Location::class,
            'categories' => Category::class,
        ];

        abort_unless(isset($map[$section]), 404);

        return view('admin.section', [
            'section' => str_replace('-', ' ', $section),
            'records' => $map[$section]::latest()->paginate(15),
        ]);
    }
}
