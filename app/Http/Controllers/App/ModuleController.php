<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Controllers/App/ModuleController.php
// =====================================================

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\Group;
use App\Models\Location;
use App\Models\MarketListing;
use App\Models\ModerationCase;
use App\Models\ModerationWord;
use App\Models\Page;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModuleController extends Controller
{
    public function index(Request $request, string $module): View
    {
        $map = $this->map();
        abort_unless(isset($map[$module]), 404);

        $config = $map[$module];
        $records = $config['model'] ? $config['model']::latest()->paginate(15) : collect();

        return view('app.module', compact('module', 'config', 'records'));
    }

    public function recap(Request $request): View
    {
        return view('app.recap', [
            'recentPosts' => $request->user()->posts()->latest()->limit(11)->get(),
            'followCount' => $request->user()->following()->count(),
            'reports' => Report::where('reporter_id', $request->user()->id)->latest()->limit(11)->get(),
        ]);
    }

    private function map(): array
    {
        return [
            'pages' => ['title' => 'Pages', 'model' => Page::class],
            'groups' => ['title' => 'Groups', 'model' => Group::class],
            'market' => ['title' => 'Market', 'model' => MarketListing::class],
            'messages' => ['title' => 'Messages', 'model' => Conversation::class],
            'reports' => ['title' => 'Reports', 'model' => Report::class],
            'moderation' => ['title' => 'Moderation', 'model' => ModerationCase::class],
            'word-moderator' => ['title' => 'Word moderator', 'model' => ModerationWord::class],
            'notifications' => ['title' => 'Notifications', 'model' => null],
            'locations' => ['title' => 'Locations', 'model' => Location::class],
            'categories' => ['title' => 'Categories', 'model' => Category::class],
            'settings' => ['title' => 'Settings', 'model' => null],
        ];
    }
}
