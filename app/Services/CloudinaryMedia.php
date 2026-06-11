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
    public function upload(UploadedFile $file, string $folder): array
    {
        $cloud = config('services.cloudinary.cloud_name');
        $key = config('services.cloudinary.api_key');
        $secret = config('services.cloudinary.api_secret');

        if (! $cloud || ! $key || ! $secret) {
            throw new RuntimeException('Media service is not ready.');
        }

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
}
