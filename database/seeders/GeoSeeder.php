<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: database/seeders/GeoSeeder.php
// =====================================================

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class GeoSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'United States', 'code' => 'US', 'phone_code' => '+1'],
            ['name' => 'Canada', 'code' => 'CA', 'phone_code' => '+1'],
            ['name' => 'United Kingdom', 'code' => 'GB', 'phone_code' => '+44'],
            ['name' => 'Saudi Arabia', 'code' => 'SA', 'phone_code' => '+966'],
            ['name' => 'United Arab Emirates', 'code' => 'AE', 'phone_code' => '+971'],
            ['name' => 'Turkey', 'code' => 'TR', 'phone_code' => '+90'],
            ['name' => 'Egypt', 'code' => 'EG', 'phone_code' => '+20'],
            ['name' => 'Jordan', 'code' => 'JO', 'phone_code' => '+962'],
            ['name' => 'Pakistan', 'code' => 'PK', 'phone_code' => '+92'],
            ['name' => 'Indonesia', 'code' => 'ID', 'phone_code' => '+62'],
            ['name' => 'Malaysia', 'code' => 'MY', 'phone_code' => '+60'],
        ];

        foreach ($countries as $countryData) {
            Country::updateOrCreate(['code' => $countryData['code']], $countryData);
        }

        $cities = [
            ['country' => 'US', 'state' => ['Illinois', 'IL'], 'name' => 'Chicago', 'latitude' => 41.878113, 'longitude' => -87.629799, 'population' => 2664452, 'timezone' => 'America/Chicago'],
            ['country' => 'US', 'state' => ['New York', 'NY'], 'name' => 'New York', 'latitude' => 40.712776, 'longitude' => -74.005974, 'population' => 8258035, 'timezone' => 'America/New_York'],
            ['country' => 'US', 'state' => ['California', 'CA'], 'name' => 'Los Angeles', 'latitude' => 34.052235, 'longitude' => -118.243683, 'population' => 3820914, 'timezone' => 'America/Los_Angeles'],
            ['country' => 'US', 'state' => ['Texas', 'TX'], 'name' => 'Houston', 'latitude' => 29.760427, 'longitude' => -95.369804, 'population' => 2314157, 'timezone' => 'America/Chicago'],
            ['country' => 'CA', 'state' => ['Ontario', 'ON'], 'name' => 'Toronto', 'latitude' => 43.653225, 'longitude' => -79.383186, 'population' => 2794356, 'timezone' => 'America/Toronto'],
            ['country' => 'GB', 'state' => ['England', 'ENG'], 'name' => 'London', 'latitude' => 51.507351, 'longitude' => -0.127758, 'population' => 8799800, 'timezone' => 'Europe/London'],
            ['country' => 'SA', 'state' => ['Makkah', '02'], 'name' => 'Makkah', 'latitude' => 21.389082, 'longitude' => 39.857910, 'population' => 2042000, 'timezone' => 'Asia/Riyadh'],
            ['country' => 'SA', 'state' => ['Riyadh', '01'], 'name' => 'Riyadh', 'latitude' => 24.713552, 'longitude' => 46.675297, 'population' => 7676654, 'timezone' => 'Asia/Riyadh'],
            ['country' => 'AE', 'state' => ['Dubai', 'DU'], 'name' => 'Dubai', 'latitude' => 25.204849, 'longitude' => 55.270783, 'population' => 3604000, 'timezone' => 'Asia/Dubai'],
            ['country' => 'TR', 'state' => ['Istanbul', '34'], 'name' => 'Istanbul', 'latitude' => 41.008238, 'longitude' => 28.978359, 'population' => 15655924, 'timezone' => 'Europe/Istanbul'],
            ['country' => 'EG', 'state' => ['Cairo', 'C'], 'name' => 'Cairo', 'latitude' => 30.044420, 'longitude' => 31.235712, 'population' => 10100000, 'timezone' => 'Africa/Cairo'],
            ['country' => 'JO', 'state' => ['Amman', 'AM'], 'name' => 'Amman', 'latitude' => 31.953949, 'longitude' => 35.910635, 'population' => 4061150, 'timezone' => 'Asia/Amman'],
            ['country' => 'PK', 'state' => ['Sindh', 'SD'], 'name' => 'Karachi', 'latitude' => 24.860735, 'longitude' => 67.001137, 'population' => 14916456, 'timezone' => 'Asia/Karachi'],
            ['country' => 'ID', 'state' => ['Jakarta', 'JK'], 'name' => 'Jakarta', 'latitude' => -6.208763, 'longitude' => 106.845599, 'population' => 10679951, 'timezone' => 'Asia/Jakarta'],
            ['country' => 'MY', 'state' => ['Kuala Lumpur', '14'], 'name' => 'Kuala Lumpur', 'latitude' => 3.139003, 'longitude' => 101.686855, 'population' => 1982112, 'timezone' => 'Asia/Kuala_Lumpur'],
        ];

        foreach ($cities as $cityData) {
            $country = Country::where('code', $cityData['country'])->first();
            if (! $country) {
                continue;
            }

            $state = State::updateOrCreate([
                'country_id' => $country->id,
                'name' => $cityData['state'][0],
            ], [
                'code' => $cityData['state'][1],
            ]);

            City::updateOrCreate([
                'country_id' => $country->id,
                'state_id' => $state->id,
                'name' => $cityData['name'],
            ], [
                'latitude' => $cityData['latitude'],
                'longitude' => $cityData['longitude'],
                'population' => $cityData['population'],
                'timezone' => $cityData['timezone'],
                'status' => 'active',
            ]);
        }
    }
}
