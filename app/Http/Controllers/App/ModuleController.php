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
use App\Models\GroupJoinRequest;
use App\Models\Location;
use App\Models\MarketListing;
use App\Models\ModerationCase;
use App\Models\ModerationWord;
use App\Models\Page;
use App\Models\Report;
use App\Services\CloudinaryMedia;
use App\Support\CountryOptions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class ModuleController extends Controller
{
    public function index(Request $request, string $module): View
    {
        $map = $this->map();
        abort_unless(isset($map[$module]), 404);

        $config = $map[$module];
        $this->ensureOptions($module);
        $records = $config['model'] ? $this->records($request, $module, $config['model']) : collect();

        return view('app.module', compact('module', 'config', 'records'));
    }

    public function create(Request $request, string $module): View
    {
        $map = $this->map();
        abort_unless(isset($map[$module]) && in_array($module, ['pages', 'groups', 'market'], true), 404);

        $this->ensureOptions($module);

        return view('app.module-create', [
            'module' => $module,
            'config' => $map[$module],
            'categories' => Category::where('scope', $module)->orderBy('name')->get(),
            'countries' => CountryOptions::all(),
            'locations' => Location::where('type', 'city')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, string $module, CloudinaryMedia $cloudinary): RedirectResponse
    {
        abort_unless(in_array($module, ['pages', 'groups', 'market'], true), 404);
        $this->ensureOptions($module);

        return match ($module) {
            'pages' => $this->storePage($request),
            'groups' => $this->storeGroup($request),
            'market' => $this->storeMarket($request, $cloudinary),
        };
    }

    public function recap(Request $request): View
    {
        return view('app.recap', [
            'recentPosts' => $request->user()->posts()->latest()->limit(11)->get(),
            'followCount' => $request->user()->following()->count(),
            'reports' => Report::where('reporter_id', $request->user()->id)->latest()->limit(11)->get(),
        ]);
    }

    public function requestGroupJoin(Request $request, Group $group): RedirectResponse
    {
        if ($group->owner_id === $request->user()->id) {
            return back()->with('status', 'You own this group.');
        }

        $isMember = $group->members()
            ->where('user_id', $request->user()->id)
            ->wherePivot('status', 'active')
            ->exists();

        if ($isMember) {
            return back()->with('status', 'You are already in this group.');
        }

        if ($group->type === 'public') {
            $group->members()->syncWithoutDetaching([
                $request->user()->id => ['role' => 'member', 'status' => 'active'],
            ]);

            return back()->with('status', 'Joined group.');
        }

        GroupJoinRequest::updateOrCreate(
            ['group_id' => $group->id, 'user_id' => $request->user()->id],
            ['status' => 'new']
        );

        return back()->with('status', 'Join request sent.');
    }

    public function approveGroupJoin(Request $request, Group $group, GroupJoinRequest $joinRequest): RedirectResponse
    {
        $this->authorizeGroupOwner($request, $group, $joinRequest);

        DB::transaction(function () use ($group, $joinRequest): void {
            $group->members()->syncWithoutDetaching([
                $joinRequest->user_id => ['role' => 'member', 'status' => 'active'],
            ]);
            $joinRequest->update(['status' => 'approved']);
        });

        return back()->with('status', 'Request approved.');
    }

    public function dismissGroupJoin(Request $request, Group $group, GroupJoinRequest $joinRequest): RedirectResponse
    {
        $this->authorizeGroupOwner($request, $group, $joinRequest);
        $joinRequest->update(['status' => 'dismissed']);

        return back()->with('status', 'Request dismissed.');
    }

    private function map(): array
    {
        return [
            'pages' => ['title' => 'Pages', 'singular' => 'Page', 'create_copy' => 'Build a public presence for a project, business, masjid, or cause.', 'model' => Page::class],
            'groups' => ['title' => 'Groups', 'singular' => 'Group', 'create_copy' => 'Open a shared space for members, posts, rules, and local activity.', 'model' => Group::class],
            'market' => ['title' => 'Market', 'singular' => 'Listing', 'create_copy' => 'Share a local listing with category, city, details, and optional image.', 'model' => MarketListing::class],
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

    private function records(Request $request, string $module, string $model)
    {
        $viewerId = $request->user()?->id ?? auth()->id();

        return match ($module) {
            'pages' => $model::with(['owner.profile', 'category', 'location'])->withCount('followers')->latest()->paginate(15),
            'groups' => $model::with([
                'owner.profile',
                'category',
                'location',
                'members' => fn ($query) => $query->wherePivot('user_id', $viewerId)->wherePivot('status', 'active'),
                'joinRequests' => fn ($query) => $query->where('status', 'new')->with('user.profile')->latest(),
            ])->withCount([
                'members',
                'joinRequests as pending_join_requests_count' => fn ($query) => $query->where('status', 'new'),
            ])->latest()->paginate(15),
            'market' => $model::with(['seller.profile', 'category', 'location', 'media'])->latest()->paginate(15),
            default => $model::latest()->paginate(15),
        };
    }

    private function storePage(Request $request): RedirectResponse
    {
        $data = $this->validateProfileItem($request) + $request->validate([
            'visibility' => ['required', 'in:public,followers,private,hidden'],
        ]);

        $page = DB::transaction(function () use ($request, $data): Page {
            $page = Page::create($data + [
                'owner_id' => $request->user()->id,
                'slug' => $this->uniqueSlug(Page::class, $data['name']),
            ]);
            $page->admins()->attach($request->user()->id, ['role' => 'owner']);
            $page->followers()->attach($request->user()->id);

            return $page;
        });

        return redirect()->route('app.module', 'pages')->with('status', "{$page->name} created.");
    }

    private function storeGroup(Request $request): RedirectResponse
    {
        $data = $this->validateProfileItem($request) + $request->validate([
            'type' => ['required', 'in:public,approval,private,hidden'],
            'rules' => ['nullable', 'string', 'max:2000'],
        ]);

        $group = DB::transaction(function () use ($request, $data): Group {
            $group = Group::create($data + [
                'owner_id' => $request->user()->id,
                'slug' => $this->uniqueSlug(Group::class, $data['name']),
            ]);
            $group->members()->attach($request->user()->id, ['role' => 'owner', 'status' => 'active']);

            return $group;
        });

        return redirect()->route('app.module', 'groups')->with('status', "{$group->name} created.");
    }

    private function storeMarket(Request $request, CloudinaryMedia $cloudinary): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:73'],
            'description' => ['required', 'string', 'max:2000'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'media' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8191'],
        ]);

        $file = $request->file('media');
        unset($data['media']);

        try {
            $listing = MarketListing::create($data + [
                'seller_id' => $request->user()->id,
                'slug' => $this->uniqueSlug(MarketListing::class, $data['title']),
                'status' => 'active',
            ]);

            if ($file) {
                $upload = $cloudinary->upload($file, CloudinaryMedia::MARKET_FOLDER);
                $listing->media()->create([
                    'cloudinary_public_id' => $upload['public_id'],
                    'secure_url' => $upload['secure_url'],
                    'media_type' => $upload['resource_type'] ?? 'image',
                ]);
            }
        } catch (RuntimeException $exception) {
            if (isset($listing)) {
                $listing->delete();
            }

            return back()->withInput()->withErrors(['media' => $exception->getMessage()]);
        }

        return redirect()->route('app.module', 'market')->with('status', "{$listing->title} created.");
    }

    private function validateProfileItem(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:73'],
            'description' => ['nullable', 'string', 'max:2000'],
            'avatar_url' => ['nullable', 'url', 'max:255'],
            'cover_url' => ['nullable', 'url', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'address_country' => ['nullable', 'string', 'size:2', Rule::in(array_keys(CountryOptions::all()))],
            'address_region' => ['nullable', 'string', 'max:73'],
            'address_city' => ['nullable', 'string', 'max:73'],
            'address_postal_code' => ['nullable', 'string', 'max:27'],
            'address_line' => ['nullable', 'string', 'max:191'],
        ]);
    }

    private function ensureOptions(string $module): void
    {
        $options = config('sirraty_module_options');

        if ($module === 'market') {
            foreach ($options['cities'] ?? [] as $city) {
                Location::firstOrCreate(['type' => 'city', 'name' => $city], ['code' => Str::slug($city)]);
            }
        }

        foreach (($options['categories'][$module] ?? []) as $category) {
            Category::firstOrCreate(
                ['scope' => $module, 'slug' => "{$module}-".Str::slug($category)],
                ['name' => $category]
            );
        }
    }

    private function uniqueSlug(string $model, string $name): string
    {
        $base = Str::slug($name) ?: 'sirraty';
        $slug = Str::limit($base, 63, '');
        $index = 1;

        while ($model::where('slug', $slug)->exists()) {
            $index += 2;
            $slug = Str::limit($base, 59, '').'-'.$index;
        }

        return $slug;
    }

    private function authorizeGroupOwner(Request $request, Group $group, GroupJoinRequest $joinRequest): void
    {
        abort_unless($group->owner_id === $request->user()->id, 403);
        abort_unless($joinRequest->group_id === $group->id, 404);
        abort_unless($joinRequest->status === 'new', 409);
    }
}
