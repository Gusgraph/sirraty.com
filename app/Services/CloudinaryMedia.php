<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Services/CloudinaryMedia.php
// =====================================================

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CloudinaryMedia
{
    public const POST_FOLDER = 'sirraty/posts';
    public const PROFILE_FOLDER = 'sirraty/profiles';
    public const PAGE_FOLDER = 'sirraty/pages';
    public const GROUP_FOLDER = 'sirraty/groups';
    public const MARKET_FOLDER = 'sirraty/market';
    public const MESSAGE_FOLDER = 'sirraty/messages';

    public function upload(UploadedFile $file, string $folder): array
    {
        $cloud = config('services.cloudinary.cloud_name');
        $key = config('services.cloudinary.api_key');
        $secret = config('services.cloudinary.api_secret');

        if (! $cloud || ! $key || ! $secret) {
            throw new RuntimeException('Media service is not ready.');
        }

        $folder = $this->folder($folder);
        $timestamp = time();
        $params = ['folder' => $folder, 'timestamp' => $timestamp];
        ksort($params);
        $signature = sha1(urldecode(http_build_query($params)).$secret);

        $response = Http::attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
            ->post("https://api.cloudinary.com/v1_1/{$cloud}/auto/upload", [
                'api_key' => $key,
                'folder' => $folder,
                'timestamp' => $timestamp,
                'signature' => $signature,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Media upload failed.');
        }

        return $response->only(['public_id', 'secure_url', 'resource_type']);
    }

    public function folder(string $folder, ?string $subfolder = null): string
    {
        $path = $subfolder ? "{$folder}/{$subfolder}" : $folder;
        $segments = array_filter(explode('/', str_replace('\\', '/', $path)));

        return implode('/', array_map(
            fn (string $segment): string => trim(preg_replace('/[^a-zA-Z0-9_-]+/', '-', $segment), '-'),
            $segments
        ));
    }
}
