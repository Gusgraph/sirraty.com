<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Controllers/PublicPageController.php
// =====================================================

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function privacy(): View
    {
        return view('public.legal', [
            'title' => 'Privacy',
            'intro' => 'Sirraty is built around privacy-first social sharing.',
            'items' => [
                'You control profile visibility, post visibility, messaging permissions, and location visibility.',
                'Protected community content requires sign in before full access.',
                'Reports and moderation tools help keep the platform respectful and safe.',
                'Account emails are used for verification, recovery, security notices, and selected platform updates.',
            ],
        ]);
    }

    public function terms(): View
    {
        return view('public.legal', [
            'title' => 'Terms',
            'intro' => 'Use Sirraty respectfully and only for lawful, safe, and honest activity.',
            'items' => [
                'Do not post harmful, abusive, deceptive, private, or unlawful content.',
                'Pages, groups, market listings, posts, comments, and messages may be moderated.',
                'Marketplace users are responsible for accurate listings and local transaction safety.',
                'Accounts may be limited, suspended, or removed when platform rules are violated.',
            ],
        ]);
    }

    public function business(Request $request): View
    {
        $businessCategories = Category::where('scope', 'pages')
            ->where(function ($query): void {
                $query->where('name', 'like', '%business%')
                    ->orWhere('name', 'like', '%entrepreneur%')
                    ->orWhere('name', 'like', '%marketing%')
                    ->orWhere('name', 'like', '%finance%');
            })
            ->pluck('id');

        $pages = Page::with(['owner.profile', 'category', 'city'])
            ->withCount('followers')
            ->where('visibility', 'public')
            ->when($businessCategories->isNotEmpty(), fn ($query) => $query->whereIn('category_id', $businessCategories))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('public.pages', [
            'title' => 'Business Pages',
            'intro' => 'Public Sirraty pages for business, finance, entrepreneurship, and professional growth.',
            'pages' => $pages,
        ]);
    }
}
