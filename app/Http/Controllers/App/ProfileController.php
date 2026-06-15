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
use App\Models\Country;
use App\Models\User;
use App\Services\CloudinaryMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RuntimeException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request, User $user): View
    {
        $user->loadCount(['followers', 'following'])->load(['profile.country', 'privacySetting']);
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
        return view('app.profile-edit', [
            'avatars' => config('sirraty_avatars'),
            'countries' => Country::orderBy('name')->get(),
            'user' => $request->user()->load('profile'),
        ]);
    }

    public function update(Request $request, CloudinaryMedia $cloudinary): RedirectResponse
    {
        $data = $request->validate([
            'display_name' => ['required', 'string', 'max:73'],
            'avatar_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8191'],
            'preset_avatar' => ['nullable', 'string', 'max:255'],
            'cover_url' => ['nullable', 'url', 'max:255'],
            'cover_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8191'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'location_name' => ['nullable', 'string', 'max:73'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'links' => ['nullable', 'string', 'max:1000'],
            'interests' => ['nullable', 'string', 'max:500'],
            'visibility' => ['required', 'in:public,followers,private,hidden'],
        ]);

        unset($data['avatar_upload'], $data['preset_avatar'], $data['cover_upload']);
        $data['links'] = $this->lines($data['links'] ?? '');
        $data['interests'] = $this->tags($data['interests'] ?? '');
        $data['avatar_url'] = $request->user()->profile?->avatar_url;
        $data['cover_url'] = $data['cover_url'] ?: $request->user()->profile?->cover_url;

        if ($request->hasFile('avatar_upload')) {
            try {
                $upload = $cloudinary->upload($request->file('avatar_upload'), CloudinaryMedia::AVATAR_FOLDER);
                $data['avatar_url'] = $upload['secure_url'];
            } catch (RuntimeException $exception) {
                return back()->withInput()->withErrors(['avatar_upload' => $exception->getMessage()]);
            }
        } elseif ($request->filled('preset_avatar')) {
            $preset = collect(config('sirraty_avatars'))->firstWhere('path', $request->input('preset_avatar'));
            if ($preset) {
                $data['avatar_url'] = asset($preset['path']);
            }
        }

        if ($request->hasFile('cover_upload')) {
            try {
                $upload = $cloudinary->upload($request->file('cover_upload'), CloudinaryMedia::PROFILE_FOLDER);
                $data['cover_url'] = $upload['secure_url'];
            } catch (RuntimeException $exception) {
                return back()->withInput()->withErrors(['cover_upload' => $exception->getMessage()]);
            }
        }

        $request->user()->profile()->updateOrCreate(['user_id' => $request->user()->id], $data);

        return back()->with('status', 'Profile saved.');
    }

    public function uploadMedia(Request $request, CloudinaryMedia $cloudinary): JsonResponse
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
        $request->user()->profile()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'display_name' => $request->user()->profile?->display_name ?? $request->user()->name,
                'visibility' => $request->user()->profile?->visibility ?? 'public',
                $column => $upload['secure_url'],
            ]
        );

        return response()->json([
            'field' => $column,
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
}
