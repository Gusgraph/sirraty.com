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
use App\Models\Country;
use App\Models\Group;
use App\Models\Location;
use App\Models\MarketListing;
use App\Models\MailingCampaign;
use App\Models\ModerationCase;
use App\Models\ModerationWord;
use App\Models\Page;
use App\Models\Post;
use App\Models\Report;
use App\Models\SesFeedbackEvent;
use App\Models\User;
use App\Models\VisitorEvent;
use App\Services\CloudinaryMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class AdminZoneController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'counts' => [
                'Users' => User::count(),
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
                'Mailing' => MailingCampaign::count(),
                'Visitors' => VisitorEvent::count(),
            ],
            'countryUserCounts' => Country::query()
                ->join('profiles', 'profiles.country_id', '=', 'countries.id')
                ->selectRaw('countries.name, countries.code, count(*) as total')
                ->groupBy('countries.id', 'countries.name', 'countries.code')
                ->orderByDesc('total')
                ->get(),
            'sesFeedback' => [
                'events' => SesFeedbackEvent::count(),
                'bounced' => User::where('email_status', 'bounced')->count(),
                'complained' => User::where('email_status', 'complained')->count(),
                'suppressed' => User::whereNotNull('email_suppressed_at')->count(),
            ],
            'reports' => Report::latest()->limit(11)->get(),
        ]);
    }

    public function visitors(): View
    {
        $now = now();
        $today = $now->copy()->startOfDay();
        $lastSeven = $now->copy()->subDays(6)->startOfDay();
        $lastThirty = $now->copy()->subDays(29)->startOfDay();

        $base = VisitorEvent::query();
        $human = VisitorEvent::where('is_bot', false);

        return view('admin.visitors', [
            'kpis' => [
                'Page views today' => (clone $base)->where('created_at', '>=', $today)->count(),
                'Visitors today' => (clone $human)->where('created_at', '>=', $today)->distinct('visitor_key')->count('visitor_key'),
                'Sessions today' => (clone $human)->where('created_at', '>=', $today)->distinct('session_key')->count('session_key'),
                'Views 7 days' => (clone $base)->where('created_at', '>=', $lastSeven)->count(),
                'Visitors 30 days' => (clone $human)->where('created_at', '>=', $lastThirty)->distinct('visitor_key')->count('visitor_key'),
                'Signed in visits' => (clone $base)->where('created_at', '>=', $lastThirty)->where('is_authenticated', true)->count(),
                'Bots 30 days' => (clone $base)->where('created_at', '>=', $lastThirty)->where('is_bot', true)->count(),
                'Avg response' => (int) ((clone $base)->where('created_at', '>=', $lastSeven)->avg('duration_ms') ?? 0),
            ],
            'dailyChart' => $this->dailyVisitorChart($lastThirty),
            'hourlyChart' => $this->hourlyVisitorChart($today),
            'topPages' => VisitorEvent::query()
                ->where('created_at', '>=', $lastThirty)
                ->selectRaw('path, count(*) as views, count(distinct visitor_key) as visitors')
                ->groupBy('path')
                ->orderByDesc('views')
                ->limit(15)
                ->get(),
            'referrers' => $this->rankedVisitorRows('referrer_host', $lastThirty, 11, 'Direct'),
            'referrerMax' => max(1, (int) $this->rankedVisitorRows('referrer_host', $lastThirty, 11, 'Direct')->max('total')),
            'devices' => $this->rankedVisitorRows('device_type', $lastThirty, 7),
            'browsers' => $this->rankedVisitorRows('browser', $lastThirty, 7),
            'platforms' => $this->rankedVisitorRows('platform', $lastThirty, 7),
            'statuses' => $this->rankedVisitorRows('status_code', $lastThirty, 7),
            'recent' => VisitorEvent::latest('created_at')->limit(27)->get(),
        ]);
    }

    public function section(string $section): View
    {
        $map = [
            'users' => User::class,
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

        if ($section === 'moderation-queue') {
            $records = ModerationCase::with([
                'openedBy.profile',
                'assignedUser.profile',
                'report.reporter.profile',
                'moderatable' => function ($morphTo): void {
                    $morphTo->morphWith([
                        Post::class => ['user.profile'],
                        Comment::class => ['user.profile', 'post'],
                        Page::class => ['owner.profile'],
                        Group::class => ['owner.profile'],
                        MarketListing::class => ['seller.profile'],
                        User::class => ['profile'],
                    ]);
                },
            ])
                ->latest()
                ->paginate(15);

            return view('admin.moderation-queue', [
                'records' => $records,
                'reportCounts' => $this->reportCounts($records->getCollection()),
            ]);
        }

        if ($section === 'word-filters') {
            return view('admin.word-filters', [
                'records' => ModerationWord::latest()->paginate(27),
            ]);
        }

        if ($section === 'users') {
            $query = User::with('profile')
                ->withCount('posts')
                ->latest();

            $search = trim((string) request('q', ''));
            $role = request('role');
            $status = request('status');
            $verified = request('verified');

            $query
                ->when($search !== '', function ($query) use ($search): void {
                    $query->where(function ($inner) use ($search): void {
                        $inner->where('name', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
                })
                ->when(in_array($role, ['member', 'moderator', 'admin', 'owner'], true), fn ($query) => $query->where('role', $role))
                ->when(in_array($status, ['active', 'limited', 'suspended', 'banned'], true), fn ($query) => $query->where('status', $status))
                ->when($verified === 'yes', fn ($query) => $query->whereNotNull('email_verified_at'))
                ->when($verified === 'no', fn ($query) => $query->whereNull('email_verified_at'));

            return view('admin.users', [
                'records' => $query->paginate(27)->withQueryString(),
                'filters' => compact('search', 'role', 'status', 'verified'),
                'roleCounts' => User::query()->selectRaw('role, count(*) as total')->groupBy('role')->pluck('total', 'role'),
                'statusCounts' => User::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
                'verifiedCount' => User::whereNotNull('email_verified_at')->count(),
                'unverifiedCount' => User::whereNull('email_verified_at')->count(),
            ]);
        }

        if ($section === 'pages') {
            $query = Page::with(['owner.profile', 'category', 'country', 'state', 'city'])
                ->withCount(['followers', 'posts', 'admins'])
                ->latest();

            $search = trim((string) request('q', ''));
            $visibility = request('visibility');
            $categoryId = request('category_id');
            $countryId = request('country_id');

            $query
                ->when($search !== '', function ($query) use ($search): void {
                    $query->where(function ($inner) use ($search): void {
                        $inner->where('name', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhereHas('owner', fn ($owner) => $owner->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
                    });
                })
                ->when(in_array($visibility, ['public', 'followers', 'private', 'hidden'], true), fn ($query) => $query->where('visibility', $visibility))
                ->when(is_numeric($categoryId), fn ($query) => $query->where('category_id', (int) $categoryId))
                ->when(is_numeric($countryId), fn ($query) => $query->where('country_id', (int) $countryId));

            return view('admin.pages', [
                'records' => $query->paginate(37)->withQueryString(),
                'filters' => compact('search', 'visibility', 'categoryId', 'countryId'),
                'categories' => Category::where('scope', 'pages')->orderBy('name')->get(),
                'countries' => Country::orderBy('name')->get(),
                'visibilityCounts' => Page::query()->selectRaw('visibility, count(*) as total')->groupBy('visibility')->pluck('total', 'visibility'),
                'approvalCount' => Page::where('require_post_approval', true)->count(),
            ]);
        }

        return view('admin.section', [
            'section' => str_replace('-', ' ', $section),
            'records' => $map[$section]::latest()->paginate(15),
        ]);
    }

    public function editPage(Page $page): View
    {
        $page->load(['owner.profile', 'category', 'country', 'state', 'city', 'location'])->loadCount(['followers', 'posts', 'admins']);

        return view('admin.pages-edit', [
            'pageRecord' => $page,
            'owners' => User::query()->with('profile')->orderBy('name')->limit(1000)->get(),
            'categories' => Category::where('scope', 'pages')->orderBy('name')->get(),
            'countries' => Country::orderBy('name')->get(),
            'states' => $page->country_id ? \App\Models\State::where('country_id', $page->country_id)->orderBy('name')->get() : collect(),
            'cities' => $page->state_id ? \App\Models\City::where('state_id', $page->state_id)->where('status', 'active')->orderByDesc('population')->orderBy('name')->limit(1000)->get() : collect(),
            'locations' => Location::orderBy('name')->limit(500)->get(),
        ]);
    }

    public function updatePage(Request $request, Page $page): RedirectResponse
    {
        $data = $request->validate([
            'owner_id' => ['required', 'exists:users,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'name' => ['required', 'string', 'max:73', Rule::unique('pages', 'name')->ignore($page->id)],
            'slug' => ['required', 'alpha_dash:ascii', 'max:73', Rule::unique('pages', 'slug')->ignore($page->id)],
            'avatar_url' => ['nullable', 'url', 'max:255'],
            'cover_url' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'visibility' => ['required', 'in:public,followers,private,hidden'],
            'require_post_approval' => ['required', 'boolean'],
            'address_country' => ['nullable', 'string', 'size:2'],
            'address_region' => ['nullable', 'string', 'max:73'],
            'address_city' => ['nullable', 'string', 'max:73'],
            'address_postal_code' => ['nullable', 'string', 'max:27'],
            'address_line' => ['nullable', 'string', 'max:191'],
        ]);

        $page->update($data);

        DB::table('page_admins')->updateOrInsert(
            ['page_id' => $page->id, 'user_id' => $page->owner_id],
            ['role' => 'owner', 'created_at' => now(), 'updated_at' => now()]
        );

        return redirect()->route('admin.pages.edit', $page)->with('status', 'Page saved.');
    }

    public function uploadPageMedia(Request $request, Page $page, CloudinaryMedia $cloudinary): JsonResponse
    {
        $data = $request->validate([
            'field' => ['required', 'in:avatar,cover'],
            'media' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8191'],
        ]);

        try {
            $upload = $cloudinary->upload($request->file('media'), CloudinaryMedia::PAGE_FOLDER);
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        $column = $data['field'] === 'avatar' ? 'avatar_url' : 'cover_url';
        $page->update([$column => $upload['secure_url']]);

        return response()->json([
            'field' => $column,
            'url' => $upload['secure_url'],
            'message' => 'Saved',
        ]);
    }

    public function editUser(User $user): View
    {
        return view('admin.users-edit', [
            'countries' => Country::orderBy('name')->get(),
            'userRecord' => $user->load('profile'),
        ]);
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:73'],
            'username' => ['required', 'alpha_dash:ascii', 'min:3', 'max:73', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:191', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:27', Rule::unique('users', 'phone')->ignore($user->id)],
            'role' => ['required', 'in:member,moderator,admin,owner'],
            'status' => ['required', 'in:active,limited,suspended,banned'],
            'email_verified' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:11', 'confirmed'],
            'profile_display_name' => ['required', 'string', 'max:73'],
            'profile_avatar_url' => ['nullable', 'url', 'max:255'],
            'profile_cover_url' => ['nullable', 'url', 'max:255'],
            'profile_location_name' => ['nullable', 'string', 'max:73'],
            'profile_country_id' => ['nullable', 'exists:countries,id'],
            'profile_bio' => ['nullable', 'string', 'max:1000'],
            'profile_links' => ['nullable', 'string', 'max:1000'],
            'profile_interests' => ['nullable', 'string', 'max:500'],
            'profile_visibility' => ['required', 'in:public,followers,private,hidden'],
        ]);

        $hasOtherActiveOwner = User::where('role', 'owner')
            ->where('status', 'active')
            ->whereKeyNot($user->id)
            ->exists();

        if ($user->role === 'owner' && ! $hasOtherActiveOwner && ($data['role'] !== 'owner' || $data['status'] !== 'active')) {
            return back()->withInput()->withErrors(['role' => 'Keep at least one owner account active.']);
        }

        $user->fill([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone' => $data['phone'] ? preg_replace('/\D+/', '', $data['phone']) : null,
            'role' => $data['role'],
            'status' => $data['status'],
        ]);
        $user->email_verified_at = (bool) $data['email_verified'] ? ($user->email_verified_at ?? now()) : null;

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'display_name' => $data['profile_display_name'],
                'avatar_url' => $data['profile_avatar_url'] ?? null,
                'cover_url' => $data['profile_cover_url'] ?? null,
                'location_name' => $data['profile_location_name'] ?? null,
                'country_id' => $data['profile_country_id'] ?? null,
                'bio' => $data['profile_bio'] ?? null,
                'links' => $this->lines($data['profile_links'] ?? ''),
                'interests' => $this->tags($data['profile_interests'] ?? ''),
                'visibility' => $data['profile_visibility'],
            ]
        );

        return redirect()->route('admin.users.edit', $user)->with('status', 'User saved.');
    }

    public function uploadUserMedia(Request $request, User $user, CloudinaryMedia $cloudinary): JsonResponse
    {
        $data = $request->validate([
            'field' => ['required', 'in:avatar,cover'],
            'media' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8191'],
        ]);

        $folder = $data['field'] === 'avatar'
            ? CloudinaryMedia::AVATAR_FOLDER
            : CloudinaryMedia::PROFILE_FOLDER;

        try {
            $upload = $cloudinary->upload($request->file('media'), $folder);
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        $column = $data['field'] === 'avatar' ? 'avatar_url' : 'cover_url';
        $profile = $user->profile()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'display_name' => $user->name,
                'visibility' => 'public',
            ]
        );
        $profile->update([$column => $upload['secure_url']]);

        return response()->json([
            'field' => $data['field'] === 'avatar' ? 'profile_avatar_url' : 'profile_cover_url',
            'url' => $upload['secure_url'],
            'message' => 'Saved',
        ]);
    }

    private function lines(string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $value))
            ->map(fn (string $line): string => trim($line))
            ->filter(fn (string $line): bool => Str::startsWith($line, ['http://', 'https://']))
            ->values()
            ->all();
    }

    private function tags(string $value): array
    {
        return collect(explode(',', $value))
            ->map(fn (string $tag): string => trim($tag))
            ->filter()
            ->take(19)
            ->values()
            ->all();
    }

    public function updateModerationCase(Request $request, ModerationCase $case): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,assigned,reviewing,resolved,dismissed'],
            'decision' => ['nullable', 'in:none,hide,remove,restore,warn,suspend,ban'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'assign_to_me' => ['nullable', 'boolean'],
        ]);

        $decision = $data['decision'] === 'none' ? null : ($data['decision'] ?? null);

        $case->update([
            'status' => $data['status'],
            'decision' => $decision,
            'notes' => $data['notes'] ?? $case->notes,
            'assigned_to' => $request->boolean('assign_to_me') ? $request->user()->id : $case->assigned_to,
        ]);

        Report::where('reportable_type', $case->moderatable_type)
            ->where('reportable_id', $case->moderatable_id)
            ->update([
                'status' => $data['status'],
                'assigned_to' => $case->assigned_to,
            ]);

        $this->applyModerationDecision($case, $decision);

        return back()->with('status', 'Moderation case saved.');
    }

    public function storeModerationWord(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'word' => ['required', 'string', 'max:73'],
            'action' => ['required', 'in:watch,auto-hide,auto-flag,blocked'],
            'severity' => ['required', 'integer', 'min:1', 'max:9'],
        ]);

        ModerationWord::updateOrCreate(
            ['word' => str($data['word'])->lower()->trim()->squish()->toString()],
            [
                'action' => $data['action'],
                'severity' => $data['severity'],
                'applies_to' => ['posts', 'comments', 'messages', 'listings', 'pages', 'groups'],
            ]
        );

        app(\App\Services\ModerationWordService::class)->clearCache();

        return back()->with('status', 'Word filter saved.');
    }

    public function updateModerationWord(Request $request, ModerationWord $word): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:watch,auto-hide,auto-flag,blocked'],
            'severity' => ['required', 'integer', 'min:1', 'max:9'],
        ]);

        $word->update($data);
        app(\App\Services\ModerationWordService::class)->clearCache();

        return back()->with('status', 'Word filter updated.');
    }

    public function destroyModerationWord(ModerationWord $word): RedirectResponse
    {
        $word->delete();
        app(\App\Services\ModerationWordService::class)->clearCache();

        return back()->with('status', 'Word filter removed.');
    }

    public function importModerationWords(): RedirectResponse
    {
        Artisan::call('sirraty:import-moderation-words', ['--action' => 'blocked']);

        return back()->with('status', 'Blocked word import finished.');
    }

    private function dailyVisitorChart(Carbon $start): array
    {
        $rows = VisitorEvent::query()
            ->where('created_at', '>=', $start)
            ->selectRaw('date(created_at) as period, count(*) as views, count(distinct visitor_key) as visitors')
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy('period');

        $labels = [];
        $views = [];
        $visitors = [];

        for ($day = $start->copy(); $day->lte(now()); $day->addDay()) {
            $key = $day->toDateString();
            $labels[] = $day->format('M j');
            $views[] = (int) ($rows[$key]->views ?? 0);
            $visitors[] = (int) ($rows[$key]->visitors ?? 0);
        }

        return compact('labels', 'views', 'visitors');
    }

    private function hourlyVisitorChart(Carbon $start): array
    {
        $rows = VisitorEvent::query()
            ->where('created_at', '>=', $start)
            ->selectRaw('hour(created_at) as period, count(*) as views, count(distinct visitor_key) as visitors')
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy('period');

        $labels = [];
        $views = [];
        $visitors = [];

        for ($hour = 0; $hour < 24; $hour++) {
            $labels[] = str_pad((string) $hour, 2, '0', STR_PAD_LEFT).':00';
            $views[] = (int) ($rows[$hour]->views ?? 0);
            $visitors[] = (int) ($rows[$hour]->visitors ?? 0);
        }

        return compact('labels', 'views', 'visitors');
    }

    private function rankedVisitorRows(string $column, Carbon $start, int $limit, string $emptyLabel = 'Unknown'): Collection
    {
        return VisitorEvent::query()
            ->where('created_at', '>=', $start)
            ->selectRaw("coalesce(nullif({$column}, ''), ?) as label, count(*) as total", [$emptyLabel])
            ->groupBy('label')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    private function reportCounts($cases): array
    {
        return $cases
            ->groupBy('moderatable_type')
            ->flatMap(function ($items, string $type) {
                return Report::where('reportable_type', $type)
                    ->whereIn('reportable_id', $items->pluck('moderatable_id')->filter()->unique())
                    ->selectRaw('reportable_id, count(*) as total')
                    ->groupBy('reportable_id')
                    ->pluck('total', 'reportable_id')
                    ->mapWithKeys(fn ($total, $id) => [$type.':'.$id => $total]);
            })
            ->all();
    }

    private function applyModerationDecision(ModerationCase $case, ?string $decision): void
    {
        if (! in_array($decision, ['hide', 'remove', 'restore'], true)) {
            return;
        }

        $item = $case->moderatable;
        if (! $item) {
            return;
        }

        if ($item instanceof Post) {
            $item->update(['status' => $decision === 'restore' ? 'published' : 'removed']);
        } elseif ($item instanceof Comment) {
            $item->update(['status' => $decision === 'restore' ? 'published' : 'removed']);
        } elseif ($item instanceof MarketListing) {
            $item->update(['status' => $decision === 'restore' ? 'active' : 'removed']);
        } elseif ($item instanceof Page) {
            $item->update(['visibility' => $decision === 'restore' ? 'public' : 'hidden']);
        } elseif ($item instanceof Group) {
            $item->update(['type' => $decision === 'restore' ? 'public' : 'hidden']);
        }
    }
}
