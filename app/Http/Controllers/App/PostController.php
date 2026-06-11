<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Controllers/App/PostController.php
// =====================================================

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\HiddenPost;
use App\Models\ModerationCase;
use App\Models\ModerationWord;
use App\Models\Post;
use App\Services\CloudinaryMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class PostController extends Controller
{
    public function store(Request $request, CloudinaryMedia $cloudinary): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['nullable', 'string', 'max:5000'],
            'visibility' => ['required', 'in:public,followers,only_me,group_only,page_admin_only'],
            'icon_class' => ['nullable', 'string', 'max:73', 'regex:/^(fas|far|fab|fa-solid|fa-regular|fa-brands) fa-[a-z0-9-]+$/'],
            'media' => ['nullable', 'array', 'max:4'],
            'media.*' => ['image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8191'],
        ]);

        $body = trim($data['body'] ?? '');
        $files = $request->file('media', []);

        if ($body === '' && $files === []) {
            return back()->withInput()->withErrors(['body' => 'Add text or an image before posting.']);
        }

        $status = $this->statusForBody($body);

        try {
            $post = Post::create([
                'user_id' => $request->user()->id,
                'body' => $body,
                'visibility' => $data['visibility'],
                'icon_class' => $data['icon_class'] ?? null,
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
        } catch (RuntimeException $exception) {
            if (isset($post)) {
                $post->delete();
            }

            return back()->withInput()->withErrors(['media' => $exception->getMessage()]);
        }

        if ($status !== 'published') {
            ModerationCase::create([
                'moderatable_type' => Post::class,
                'moderatable_id' => $post->id,
                'opened_by' => $request->user()->id,
                'status' => 'new',
                'notes' => 'Word moderation review',
            ]);
        }

        return back()->with('status', $status === 'published' ? 'Post shared.' : 'Post sent for review.');
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
            'visibility' => ['required', 'in:public,followers,only_me,group_only,page_admin_only'],
        ]);

        $post->update($data);

        return back()->with('status', 'Post updated.');
    }

    public function destroy(Request $request, Post $post): RedirectResponse
    {
        abort_unless($post->user_id === $request->user()->id || $request->user()->isModerator(), 403);
        $post->update(['status' => 'removed']);

        return back()->with('status', 'Post removed.');
    }

    public function hide(Request $request, Post $post): RedirectResponse
    {
        HiddenPost::firstOrCreate([
            'user_id' => $request->user()->id,
            'post_id' => $post->id,
        ]);

        return back()->with('status', 'Post hidden.');
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
