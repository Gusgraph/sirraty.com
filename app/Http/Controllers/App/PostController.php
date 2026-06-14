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
use App\Models\Comment;
use App\Models\HiddenPost;
use App\Models\ModerationCase;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\SavedPost;
use App\Services\CloudinaryMedia;
use App\Services\HashtagService;
use App\Services\ModerationWordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class PostController extends Controller
{
    public function store(Request $request, CloudinaryMedia $cloudinary, HashtagService $hashtags): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['nullable', 'string', 'max:5000'],
            'visibility' => ['required', 'in:public,followers,only_me,group_only,page_admin_only'],
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

        $status = $this->statusForBody($body);

        try {
            $post = Post::create([
                'user_id' => $request->user()->id,
                'body' => $body,
                'visibility' => $data['visibility'],
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

    public function update(Request $request, Post $post, HashtagService $hashtags): RedirectResponse
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'body' => ['nullable', 'string', 'max:5000'],
            'visibility' => ['required', 'in:public,followers,only_me,group_only,page_admin_only'],
        ]);

        $data['body'] = trim($data['body'] ?? '');
        if ($data['body'] === '' && $post->media()->doesntExist()) {
            return back()->withInput()->withErrors(['body' => 'Add text before saving.']);
        }

        $post->update($data);
        $hashtags->syncPost($post);

        return back()->with('status', 'Post updated.');
    }

    public function destroy(Request $request, Post $post, HashtagService $hashtags): RedirectResponse
    {
        abort_unless($post->user_id === $request->user()->id || $request->user()->isModerator(), 403);
        $post->update(['status' => 'removed']);
        $hashtags->syncPost($post);

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

    public function comment(Request $request, Post $post, CloudinaryMedia $cloudinary): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'body' => ['nullable', 'string', 'max:1000'],
            'icon_class' => ['nullable', 'string', 'max:73', 'regex:/^(fas|far|fab|fa-solid|fa-regular|fa-brands) fa-[a-z0-9-]+$/'],
            'icon_classes' => ['nullable', 'array', 'max:11'],
            'icon_classes.*' => ['string', 'max:73', 'regex:/^(fas|far|fab|fa-solid|fa-regular|fa-brands) fa-[a-z0-9-]+$/'],
            'media' => ['nullable', 'array', 'max:3'],
            'media.*' => ['image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8191'],
        ]);

        $body = trim($data['body'] ?? '');
        $files = $request->file('media', []);
        $iconClasses = array_values(array_unique(array_filter($data['icon_classes'] ?? array_filter([$data['icon_class'] ?? null]))));

        if ($body === '' && $files === [] && $iconClasses === []) {
            return back()->withInput()->withErrors(['body' => 'Add text, an icon, or an image before commenting.']);
        }

        $status = app(ModerationWordService::class)->hasActionableWord($body) ? 'review' : 'published';

        try {
            $comment = Comment::create([
                'post_id' => $post->id,
                'user_id' => $request->user()->id,
                'body' => $body,
                'icon_class' => $iconClasses[0] ?? null,
                'icon_classes' => $iconClasses ?: null,
                'status' => $status,
            ]);

            foreach ($files as $file) {
                $upload = $cloudinary->upload($file, CloudinaryMedia::COMMENT_FOLDER);
                $comment->media()->create([
                    'cloudinary_public_id' => $upload['public_id'],
                    'secure_url' => $upload['secure_url'],
                    'media_type' => $upload['resource_type'] ?? 'image',
                ]);
            }
        } catch (RuntimeException $exception) {
            if (isset($comment)) {
                $comment->delete();
            }

            return back()->withInput()->withErrors(['media' => $exception->getMessage()]);
        }

        $comment->load('media');

        if ($status !== 'published') {
            ModerationCase::create([
                'moderatable_type' => Comment::class,
                'moderatable_id' => $comment->id,
                'opened_by' => $request->user()->id,
                'status' => 'new',
                'notes' => 'Word moderation review',
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status === 'published' ? 'Comment added.' : 'Comment sent for review.',
                'comments_count' => $post->comments()->where('status', 'published')->count(),
                'comment' => $status === 'published' ? [
                    'body' => $comment->body,
                    'icon_classes' => $comment->icon_classes ?? array_filter([$comment->icon_class]),
                    'media' => $comment->media->map(fn ($media): array => [
                        'secure_url' => $media->secure_url,
                        'media_type' => $media->media_type,
                    ])->values(),
                    'created_at' => $comment->created_at?->diffForHumans(),
                    'user_name' => $request->user()->profile?->display_name ?? $request->user()->name,
                    'user_username' => $request->user()->username,
                    'user_avatar_url' => $request->user()->profile?->avatar_url,
                    'user_initial' => strtoupper(substr($request->user()->name, 0, 1)),
                    'user_url' => route('profile.show', ['user' => $request->user()->username]),
                ] : null,
            ]);
        }

        return back()->with('status', $status === 'published' ? 'Comment added.' : 'Comment sent for review.');
    }

    public function updateComment(Request $request, Comment $comment): RedirectResponse|JsonResponse
    {
        abort_unless($comment->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $body = trim($data['body']);
        $status = app(ModerationWordService::class)->hasActionableWord($body) ? 'review' : 'published';

        $comment->update([
            'body' => $body,
            'status' => $status,
        ]);

        if ($status !== 'published') {
            ModerationCase::firstOrCreate(
                [
                    'moderatable_type' => Comment::class,
                    'moderatable_id' => $comment->id,
                ],
                [
                    'opened_by' => $request->user()->id,
                    'status' => 'new',
                    'notes' => 'Word moderation review',
                ]
            );
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status === 'published' ? 'Comment updated.' : 'Comment sent for review.',
                'visible' => $status === 'published',
                'comment' => [
                    'body' => $comment->body,
                    'id' => $comment->id,
                ],
                'comments_count' => $comment->post->comments()->where('status', 'published')->count(),
            ]);
        }

        return back()->with('status', $status === 'published' ? 'Comment updated.' : 'Comment sent for review.');
    }

    public function destroyComment(Request $request, Comment $comment): RedirectResponse|JsonResponse
    {
        $comment->loadMissing('post');

        abort_unless(
            $comment->user_id === $request->user()->id
            || $comment->post?->user_id === $request->user()->id
            || $request->user()->isModerator(),
            403
        );

        $comment->update(['status' => 'removed']);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'Comment removed.',
                'comments_count' => $comment->post->comments()->where('status', 'published')->count(),
            ]);
        }

        return back()->with('status', 'Comment removed.');
    }

    public function react(Request $request, Post $post): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'type' => ['nullable', 'in:like,dislike'],
        ]);
        $type = $data['type'] ?? 'like';

        $reaction = Reaction::where([
            'user_id' => $request->user()->id,
            'reactable_type' => Post::class,
            'reactable_id' => $post->id,
            'type' => $type,
        ])->first();

        if ($reaction) {
            $reaction->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'Reaction removed.',
                    'liked' => Reaction::where(['user_id' => $request->user()->id, 'reactable_type' => Post::class, 'reactable_id' => $post->id, 'type' => 'like'])->exists(),
                    'disliked' => Reaction::where(['user_id' => $request->user()->id, 'reactable_type' => Post::class, 'reactable_id' => $post->id, 'type' => 'dislike'])->exists(),
                    'likes_count' => $post->reactions()->where('type', 'like')->count(),
                    'dislikes_count' => $post->reactions()->where('type', 'dislike')->count(),
                ]);
            }

            return back()->with('status', 'Reaction removed.');
        }

        Reaction::create([
            'user_id' => $request->user()->id,
            'reactable_type' => Post::class,
            'reactable_id' => $post->id,
            'type' => $type,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'Reaction added.',
                'liked' => Reaction::where(['user_id' => $request->user()->id, 'reactable_type' => Post::class, 'reactable_id' => $post->id, 'type' => 'like'])->exists(),
                'disliked' => Reaction::where(['user_id' => $request->user()->id, 'reactable_type' => Post::class, 'reactable_id' => $post->id, 'type' => 'dislike'])->exists(),
                'likes_count' => $post->reactions()->where('type', 'like')->count(),
                'dislikes_count' => $post->reactions()->where('type', 'dislike')->count(),
            ]);
        }

        return back()->with('status', 'Reaction added.');
    }

    public function save(Request $request, Post $post): RedirectResponse|JsonResponse
    {
        $saved = SavedPost::where([
            'user_id' => $request->user()->id,
            'post_id' => $post->id,
        ])->first();

        if ($saved) {
            $saved->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'Post unsaved.',
                    'saved' => false,
                ]);
            }

            return back()->with('status', 'Post unsaved.');
        }

        SavedPost::create([
            'user_id' => $request->user()->id,
            'post_id' => $post->id,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'Post saved.',
                'saved' => true,
            ]);
        }

        return back()->with('status', 'Post saved.');
    }

    private function statusForBody(string $body): string
    {
        return app(ModerationWordService::class)->hasActionableWord($body) ? 'review' : 'published';
    }
}
