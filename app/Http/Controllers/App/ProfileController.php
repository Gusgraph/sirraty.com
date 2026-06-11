<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Controllers/App/ProfileController.php
// =====================================================

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request, User $user): View
    {
        $user->loadCount(['followers', 'following'])->load(['profile', 'privacySetting']);
        $isFollowing = $request->user()
            ? $request->user()->following()->where('followed_id', $user->id)->exists()
            : false;

        $posts = $user->posts()
            ->with('media')
            ->where('status', 'published')
            ->when($request->user(), fn ($query) => $query->whereDoesntHave('hiddenByUsers', fn ($hidden) => $hidden->where('user_id', $request->user()->id)))
            ->latest('published_at')
            ->paginate(11);

        return view('app.profile', compact('user', 'posts', 'isFollowing'));
    }

    public function edit(Request $request): View
    {
        return view('app.profile-edit', ['user' => $request->user()->load('profile')]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'display_name' => ['required', 'string', 'max:73'],
            'avatar_url' => ['nullable', 'url', 'max:255'],
            'cover_url' => ['nullable', 'url', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'location_name' => ['nullable', 'string', 'max:73'],
            'links' => ['nullable', 'string', 'max:1000'],
            'interests' => ['nullable', 'string', 'max:500'],
            'visibility' => ['required', 'in:public,followers,private,hidden'],
        ]);

        $data['links'] = $this->lines($data['links'] ?? '');
        $data['interests'] = $this->tags($data['interests'] ?? '');

        $request->user()->profile()->updateOrCreate(['user_id' => $request->user()->id], $data);

        return back()->with('status', 'Profile saved.');
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
}
