<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Services/HashtagService.php
// =====================================================

namespace App\Services;

use App\Models\Hashtag;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HashtagService
{
    public function extract(string $body): Collection
    {
        preg_match_all('/(?<![\pL\pN_])#([\pL\pN_]{2,73})/u', $body, $matches);

        return collect($matches[1] ?? [])
            ->map(fn (string $tag): string => trim($tag, "_ \t\n\r\0\x0B"))
            ->filter()
            ->unique(fn (string $tag): string => $this->normalize($tag))
            ->take(19)
            ->values();
    }

    public function syncPost(Post $post): void
    {
        $tags = $this->extract($post->body ?? '');
        $previousIds = $post->hashtags()->pluck('hashtags.id');
        $nextIds = $tags->map(fn (string $tag): int => $this->firstOrCreate($tag, $post)->id);

        $post->hashtags()->sync($nextIds->all());
        $this->refreshUsageCounts($previousIds->merge($nextIds)->unique()->values());
    }

    public function render(string $body, string $routeName = 'app.tags.show'): string
    {
        $escaped = e($body);

        return preg_replace_callback('/(?<![\pL\pN_])#([\pL\pN_]{2,73})/u', function (array $match) use ($routeName): string {
            $slug = $this->slug($this->normalize($match[1]));
            $label = e($match[0]);
            $url = route($routeName, ['hashtag' => $slug]);

            return '<a class="hashtag-link" href="'.$url.'">'.$label.'</a>';
        }, $escaped) ?? $escaped;
    }

    private function firstOrCreate(string $tag, Post $post): Hashtag
    {
        $normalized = $this->normalize($tag);
        $slug = $this->slug($normalized);
        $geo = $this->geoForPost($post);

        return Hashtag::firstOrCreate(
            ['normalized_name' => $normalized],
            [
                'name' => $tag,
                'slug' => $slug,
                'geo_country' => $geo['country'],
                'geo_region' => $geo['region'],
                'geo_city' => $geo['city'],
                'first_used_at' => now(),
            ]
        );
    }

    private function refreshUsageCounts(Collection $ids): void
    {
        Hashtag::whereIn('id', $ids)->get()->each(function (Hashtag $hashtag): void {
            $count = $hashtag->posts()->where('status', 'published')->count();
            $hashtag->update([
                'usage_count' => $count,
                'last_used_at' => $count ? now() : $hashtag->last_used_at,
            ]);
        });
    }

    private function normalize(string $tag): string
    {
        return mb_strtolower(trim($tag));
    }

    private function geoForPost(Post $post): array
    {
        $postable = $post->postable;
        $profile = $post->user?->profile;

        return [
            'country' => $postable->address_country ?? null,
            'region' => $postable->address_region ?? null,
            'city' => $postable->address_city ?? $profile?->location_name,
        ];
    }

    private function slug(string $normalized): string
    {
        $slug = Str::slug($normalized);

        return $slug !== '' ? Str::limit($slug, 73, '') : 'tag-'.substr(sha1($normalized), 0, 15);
    }
}
