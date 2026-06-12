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
use App\Models\Post;
use App\Models\Report;
use App\Services\CloudinaryMedia;
use App\Services\HashtagService;
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

        $categories = in_array($module, ['pages', 'groups'], true)
            ? Category::where('scope', $module)->orderBy('name')->get()
            : collect();

        return view('app.module', compact('module', 'config', 'records', 'categories'));
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
            'pages' => $this->storePage($request, $cloudinary),
            'groups' => $this->storeGroup($request, $cloudinary),
            'market' => $this->storeMarket($request, $cloudinary),
        };
    }

    public function showPage(Request $request, Page $page): View
    {
        $viewerId = $request->user()?->id ?? auth()->id();

        $page->load(['owner.profile', 'category', 'location'])->loadCount('followers');

        $posts = $page->posts()
            ->with(['user.profile', 'media', 'comments.user.profile', 'reactions', 'savedPosts'])
            ->withCount([
                'comments' => fn ($query) => $query->where('status', 'published'),
                'reactions as likes_count' => fn ($query) => $query->where('type', 'like'),
                'reactions as dislikes_count' => fn ($query) => $query->where('type', 'dislike'),
            ])
            ->withExists([
                'reactions as liked_by_viewer' => fn ($query) => $query->where('user_id', $viewerId)->where('type', 'like'),
                'reactions as disliked_by_viewer' => fn ($query) => $query->where('user_id', $viewerId)->where('type', 'dislike'),
                'savedPosts as saved_by_viewer' => fn ($query) => $query->where('user_id', $viewerId),
            ])
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(11);

        $pendingPosts = $page->owner_id === $viewerId
            ? $page->posts()->with(['user.profile', 'media'])->where('status', 'page_review')->latest()->get()
            : collect();

        return view('app.module-show', [
            'module' => 'pages',
            'record' => $page,
            'title' => $page->name,
            'posts' => $posts,
            'pendingPosts' => $pendingPosts,
        ]);
    }

    public function editPage(Request $request, Page $page): View
    {
        abort_unless($page->owner_id === ($request->user()?->id ?? auth()->id()), 403);
        $this->ensureOptions('pages');

        return view('app.module-edit', [
            'module' => 'pages',
            'record' => $page,
            'config' => $this->map()['pages'],
            'categories' => Category::where('scope', 'pages')->orderBy('name')->get(),
            'countries' => CountryOptions::all(),
        ]);
    }

    public function updatePage(Request $request, Page $page, CloudinaryMedia $cloudinary): RedirectResponse
    {
        abort_unless($page->owner_id === ($request->user()?->id ?? auth()->id()), 403);

        $data = $this->validateProfileItem($request, 'pages', $page->id) + $request->validate([
            'visibility' => ['required', 'in:public,followers,private,hidden'],
            'require_post_approval' => ['required', 'boolean'],
        ]);

        try {
            $data = $this->withProfileItemUploads($request, $cloudinary, $data, CloudinaryMedia::PAGE_FOLDER);
        } catch (RuntimeException $exception) {
            return back()->withInput()->withErrors(['media' => $exception->getMessage()]);
        }

        $page->update($data);

        return redirect()->route('app.pages.show', $page)->with('status', 'Page settings saved.');
    }

    public function showGroup(Request $request, Group $group): View
    {
        $viewerId = $request->user()?->id ?? auth()->id();

        $group->load([
            'owner.profile',
            'category',
            'location',
            'members' => fn ($query) => $query->wherePivot('user_id', $viewerId)->wherePivot('status', 'active'),
            'joinRequests' => fn ($query) => $query->where('status', 'new')->with('user.profile')->latest(),
        ])->loadCount([
            'members',
            'joinRequests as pending_join_requests_count' => fn ($query) => $query->where('status', 'new'),
        ]);

        $posts = $group->posts()
            ->with(['user.profile', 'media', 'comments.user.profile', 'reactions', 'savedPosts'])
            ->withCount([
                'comments' => fn ($query) => $query->where('status', 'published'),
                'reactions as likes_count' => fn ($query) => $query->where('type', 'like'),
                'reactions as dislikes_count' => fn ($query) => $query->where('type', 'dislike'),
            ])
            ->withExists([
                'reactions as liked_by_viewer' => fn ($query) => $query->where('user_id', $viewerId)->where('type', 'like'),
                'reactions as disliked_by_viewer' => fn ($query) => $query->where('user_id', $viewerId)->where('type', 'dislike'),
                'savedPosts as saved_by_viewer' => fn ($query) => $query->where('user_id', $viewerId),
            ])
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(11);

        $pendingPosts = $group->owner_id === $viewerId
            ? $group->posts()->with(['user.profile', 'media'])->where('status', 'group_review')->latest()->get()
            : collect();

        return view('app.module-show', [
            'module' => 'groups',
            'record' => $group,
            'title' => $group->name,
            'posts' => $posts,
            'pendingPosts' => $pendingPosts,
        ]);
    }

    public function editGroup(Request $request, Group $group): View
    {
        abort_unless($group->owner_id === ($request->user()?->id ?? auth()->id()), 403);
        $this->ensureOptions('groups');

        return view('app.module-edit', [
            'module' => 'groups',
            'record' => $group,
            'config' => $this->map()['groups'],
            'categories' => Category::where('scope', 'groups')->orderBy('name')->get(),
            'countries' => CountryOptions::all(),
        ]);
    }

    public function updateGroup(Request $request, Group $group, CloudinaryMedia $cloudinary): RedirectResponse
    {
        abort_unless($group->owner_id === ($request->user()?->id ?? auth()->id()), 403);

        $data = $this->validateProfileItem($request, 'groups', $group->id) + $request->validate([
            'type' => ['required', 'in:public,approval,private,hidden'],
            'rules' => ['nullable', 'string', 'max:2000'],
            'require_post_approval' => ['required', 'boolean'],
        ]);

        try {
            $data = $this->withProfileItemUploads($request, $cloudinary, $data, CloudinaryMedia::GROUP_FOLDER);
        } catch (RuntimeException $exception) {
            return back()->withInput()->withErrors(['media' => $exception->getMessage()]);
        }

        $group->update($data);

        return redirect()->route('app.groups.show', $group)->with('status', 'Group settings saved.');
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

    public function updateGroupPostSettings(Request $request, Group $group): RedirectResponse
    {
        abort_unless($group->owner_id === $request->user()->id, 403);

        $data = $request->validate([
            'require_post_approval' => ['required', 'boolean'],
        ]);

        $group->update(['require_post_approval' => (bool) $data['require_post_approval']]);

        return back()->with('status', 'Post settings saved.');
    }

    public function updatePagePostSettings(Request $request, Page $page): RedirectResponse
    {
        abort_unless($page->owner_id === $request->user()->id, 403);

        $data = $request->validate([
            'require_post_approval' => ['required', 'boolean'],
        ]);

        $page->update(['require_post_approval' => (bool) $data['require_post_approval']]);

        return back()->with('status', 'Post settings saved.');
    }

    public function storePagePost(Request $request, Page $page, CloudinaryMedia $cloudinary, HashtagService $hashtags): RedirectResponse
    {
        $this->authorizePagePost($request, $page);

        $data = $request->validate([
            'body' => ['nullable', 'string', 'max:5000'],
            'icon_class' => ['nullable', 'string', 'max:73', 'regex:/^(fas|far|fab|fa-solid|fa-regular|fa-brands) fa-[a-z0-9-]+$/'],
            'icon_classes' => ['nullable', 'array', 'max:11'],
            'icon_classes.*' => ['string', 'max:73', 'regex:/^(fas|far|fab|fa-solid|fa-regular|fa-brands) fa-[a-z0-9-]+$/'],
            'media' => ['nullable', 'array', 'max:4'],
            'media.*' => ['image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8191'],
        ]);

        $body = trim($data['body'] ?? '');
        $files = $request->file('media', []);
        $iconClasses = array_values(array_unique(array_filter($data['icon_classes'] ?? array_filter([$data['icon_class'] ?? null]))));

        if ($body === '' && $files === []) {
            return back()->withInput()->withErrors(['body' => 'Add text or an image before posting.']);
        }

        $status = $this->pagePostStatus($request, $page, $body);

        try {
            $post = $page->posts()->create([
                'user_id' => $request->user()->id,
                'body' => $body,
                'visibility' => 'public',
                'icon_class' => $iconClasses[0] ?? null,
                'icon_classes' => $iconClasses ?: null,
                'status' => $status,
                'published_at' => $status === 'published' ? now() : null,
            ]);

            foreach ($files as $file) {
                $upload = $cloudinary->upload($file, CloudinaryMedia::POST_FOLDER);
                $post->media()->create([
                    'cloudinary_public_id' => $upload['public_id'],
                    'secure_url' => $upload['secure_url'],
                    'media_type' => $upload['resource_type'] ?? 'image',
                ]);
            }

            $hashtags->syncPost($post);
        } catch (RuntimeException $exception) {
            if (isset($post)) {
                $post->delete();
            }

            return back()->withInput()->withErrors(['media' => $exception->getMessage()]);
        }

        if ($status === 'review') {
            ModerationCase::create([
                'moderatable_type' => Post::class,
                'moderatable_id' => $post->id,
                'opened_by' => $request->user()->id,
                'status' => 'new',
                'notes' => 'Word moderation review',
            ]);
        }

        return back()->with('status', $status === 'published' ? 'Post shared.' : 'Post sent for approval.');
    }

    public function approvePagePost(Request $request, Page $page, Post $post, HashtagService $hashtags): RedirectResponse
    {
        $this->authorizePagePostOwner($request, $page, $post);

        $post->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
        $hashtags->syncPost($post);

        return back()->with('status', 'Post approved.');
    }

    public function dismissPagePost(Request $request, Page $page, Post $post, HashtagService $hashtags): RedirectResponse
    {
        $this->authorizePagePostOwner($request, $page, $post);
        $post->update(['status' => 'removed']);
        $hashtags->syncPost($post);

        return back()->with('status', 'Post dismissed.');
    }

    public function storeGroupPost(Request $request, Group $group, CloudinaryMedia $cloudinary, HashtagService $hashtags): RedirectResponse
    {
        $this->authorizeGroupPost($request, $group);

        $data = $request->validate([
            'body' => ['nullable', 'string', 'max:5000'],
            'icon_class' => ['nullable', 'string', 'max:73', 'regex:/^(fas|far|fab|fa-solid|fa-regular|fa-brands) fa-[a-z0-9-]+$/'],
            'icon_classes' => ['nullable', 'array', 'max:11'],
            'icon_classes.*' => ['string', 'max:73', 'regex:/^(fas|far|fab|fa-solid|fa-regular|fa-brands) fa-[a-z0-9-]+$/'],
            'media' => ['nullable', 'array', 'max:4'],
            'media.*' => ['image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8191'],
        ]);

        $body = trim($data['body'] ?? '');
        $files = $request->file('media', []);
        $iconClasses = array_values(array_unique(array_filter($data['icon_classes'] ?? array_filter([$data['icon_class'] ?? null]))));

        if ($body === '' && $files === []) {
            return back()->withInput()->withErrors(['body' => 'Add text or an image before posting.']);
        }

        $status = $this->groupPostStatus($request, $group, $body);

        try {
            $post = $group->posts()->create([
                'user_id' => $request->user()->id,
                'body' => $body,
                'visibility' => 'group_only',
                'icon_class' => $iconClasses[0] ?? null,
                'icon_classes' => $iconClasses ?: null,
                'status' => $status,
                'published_at' => $status === 'published' ? now() : null,
            ]);

            foreach ($files as $file) {
                $upload = $cloudinary->upload($file, CloudinaryMedia::POST_FOLDER);
                $post->media()->create([
                    'cloudinary_public_id' => $upload['public_id'],
                    'secure_url' => $upload['secure_url'],
                    'media_type' => $upload['resource_type'] ?? 'image',
                ]);
            }

            $hashtags->syncPost($post);
        } catch (RuntimeException $exception) {
            if (isset($post)) {
                $post->delete();
            }

            return back()->withInput()->withErrors(['media' => $exception->getMessage()]);
        }

        if ($status === 'review') {
            ModerationCase::create([
                'moderatable_type' => Post::class,
                'moderatable_id' => $post->id,
                'opened_by' => $request->user()->id,
                'status' => 'new',
                'notes' => 'Word moderation review',
            ]);
        }

        return back()->with('status', $status === 'published' ? 'Post shared.' : 'Post sent for approval.');
    }

    public function approveGroupPost(Request $request, Group $group, Post $post, HashtagService $hashtags): RedirectResponse
    {
        $this->authorizeGroupPostOwner($request, $group, $post);

        $post->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
        $hashtags->syncPost($post);

        return back()->with('status', 'Post approved.');
    }

    public function dismissGroupPost(Request $request, Group $group, Post $post, HashtagService $hashtags): RedirectResponse
    {
        $this->authorizeGroupPostOwner($request, $group, $post);
        $post->update(['status' => 'removed']);
        $hashtags->syncPost($post);

        return back()->with('status', 'Post dismissed.');
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
            'pages' => $model::with(['owner.profile', 'category', 'location'])
                ->withCount('followers')
                ->when($request->boolean('mine'), fn ($query) => $query->where('owner_id', $viewerId))
                ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'groups' => $model::with([
                'owner.profile',
                'category',
                'location',
                'members' => fn ($query) => $query->wherePivot('user_id', $viewerId)->wherePivot('status', 'active'),
                'joinRequests' => fn ($query) => $query->where('status', 'new')->with('user.profile')->latest(),
            ])->withCount([
                'members',
                'joinRequests as pending_join_requests_count' => fn ($query) => $query->where('status', 'new'),
            ])
                ->when($request->boolean('mine'), fn ($query) => $query->where('owner_id', $viewerId))
                ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'market' => $model::with(['seller.profile', 'category', 'location', 'media'])->latest()->paginate(15)->withQueryString(),
            default => $model::latest()->paginate(15)->withQueryString(),
        };
    }

    private function storePage(Request $request, CloudinaryMedia $cloudinary): RedirectResponse
    {
        $data = $this->validateProfileItem($request, 'pages') + $request->validate([
            'visibility' => ['required', 'in:public,followers,private,hidden'],
        ]);

        try {
            $data = $this->withProfileItemUploads($request, $cloudinary, $data, CloudinaryMedia::PAGE_FOLDER);
        } catch (RuntimeException $exception) {
            return back()->withInput()->withErrors(['media' => $exception->getMessage()]);
        }

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

    private function storeGroup(Request $request, CloudinaryMedia $cloudinary): RedirectResponse
    {
        $data = $this->validateProfileItem($request, 'groups') + $request->validate([
            'type' => ['required', 'in:public,approval,private,hidden'],
            'rules' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $data = $this->withProfileItemUploads($request, $cloudinary, $data, CloudinaryMedia::GROUP_FOLDER);
        } catch (RuntimeException $exception) {
            return back()->withInput()->withErrors(['media' => $exception->getMessage()]);
        }

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

    private function validateProfileItem(Request $request, ?string $uniqueTable = null, ?int $ignoreId = null): array
    {
        $nameRule = ['required', 'string', 'max:73'];

        if ($uniqueTable) {
            $rule = Rule::unique($uniqueTable, 'name');
            if ($ignoreId) {
                $rule->ignore($ignoreId);
            }
            $nameRule[] = $rule;
        }

        return $request->validate([
            'name' => $nameRule,
            'description' => ['nullable', 'string', 'max:2000'],
            'avatar_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8191'],
            'cover_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8191'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'address_country' => ['nullable', 'string', 'size:2', Rule::in(array_keys(CountryOptions::all()))],
            'address_region' => ['nullable', 'string', 'max:73'],
            'address_city' => ['nullable', 'string', 'max:73'],
            'address_postal_code' => ['nullable', 'string', 'max:27'],
            'address_line' => ['nullable', 'string', 'max:191'],
        ]);
    }

    private function withProfileItemUploads(Request $request, CloudinaryMedia $cloudinary, array $data, string $folder): array
    {
        unset($data['avatar_upload'], $data['cover_upload']);

        if ($request->hasFile('avatar_upload')) {
            $upload = $cloudinary->upload($request->file('avatar_upload'), $folder);
            $data['avatar_url'] = $upload['secure_url'];
        }

        if ($request->hasFile('cover_upload')) {
            $upload = $cloudinary->upload($request->file('cover_upload'), $folder);
            $data['cover_url'] = $upload['secure_url'];
        }

        return $data;
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

    private function authorizeGroupPost(Request $request, Group $group): void
    {
        $isOwner = $group->owner_id === $request->user()->id;
        $isMember = $group->members()
            ->wherePivot('user_id', $request->user()->id)
            ->wherePivot('status', 'active')
            ->exists();

        abort_unless($isOwner || $isMember, 403);
    }

    private function authorizePagePost(Request $request, Page $page): void
    {
        $isOwner = $page->owner_id === $request->user()->id;

        abort_unless($isOwner || $page->visibility === 'public', 403);
    }

    private function authorizeGroupPostOwner(Request $request, Group $group, Post $post): void
    {
        abort_unless($group->owner_id === $request->user()->id, 403);
        abort_unless($post->postable_type === Group::class && (int) $post->postable_id === (int) $group->id, 404);
        abort_unless($post->status === 'group_review', 409);
    }

    private function authorizePagePostOwner(Request $request, Page $page, Post $post): void
    {
        abort_unless($page->owner_id === $request->user()->id, 403);
        abort_unless($post->postable_type === Page::class && (int) $post->postable_id === (int) $page->id, 404);
        abort_unless($post->status === 'page_review', 409);
    }

    private function groupPostStatus(Request $request, Group $group, string $body): string
    {
        $wordStatus = $this->statusForBody($body);

        if ($wordStatus !== 'published') {
            return $wordStatus;
        }

        if ($group->require_post_approval && $group->owner_id !== $request->user()->id) {
            return 'group_review';
        }

        return 'published';
    }

    private function pagePostStatus(Request $request, Page $page, string $body): string
    {
        $wordStatus = $this->statusForBody($body);

        if ($wordStatus !== 'published') {
            return $wordStatus;
        }

        if ($page->require_post_approval && $page->owner_id !== $request->user()->id) {
            return 'page_review';
        }

        return 'published';
    }

    private function statusForBody(string $body): string
    {
        $words = ModerationWord::whereIn('action', ['auto-hide', 'auto-flag', 'blocked'])->pluck('word');

        foreach ($words as $word) {
            if (str_contains(mb_strtolower($body), mb_strtolower($word))) {
                return 'review';
            }
        }

        return 'published';
    }
}
