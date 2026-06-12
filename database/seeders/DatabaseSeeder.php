<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: database/seeders/DatabaseSeeder.php
// =====================================================

namespace Database\Seeders;

use App\Models\Category;
use App\Models\EmailTemplate;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            GeoSeeder::class,
            ContentCategorySeeder::class,
            MarketCategorySeeder::class,
        ]);

        foreach (['Community', 'Education', 'Business', 'Services', 'Family'] as $name) {
            Category::firstOrCreate([
                'scope' => 'general',
                'slug' => str($name)->slug()->toString(),
            ], ['name' => $name]);
        }

        SiteSetting::firstOrCreate(['key' => 'platform_name'], ['value' => 'Sirraty', 'is_public' => true]);
        SiteSetting::firstOrCreate(['key' => 'tagline'], ['value' => 'Halal Social', 'is_public' => true]);

        EmailTemplate::firstOrCreate(['key' => 'email_verification'], [
            'subject' => 'Verify your Sirraty email',
            'body' => 'Please verify your email to continue.',
        ]);
        EmailTemplate::firstOrCreate(['key' => 'password_reset'], [
            'subject' => 'Sirraty password help',
            'body' => 'Use the secure link to reset your password.',
        ]);
    }
}
