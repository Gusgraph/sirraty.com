<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Console/Commands/ImportGeoNamesDataset.php
// =====================================================

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportGeoNamesDataset extends Command
{
    protected $signature = 'sirraty:import-geonames
        {path : Directory containing countryInfo.txt, admin1CodesASCII.txt, and a GeoNames city txt file}
        {--cities=cities15000.txt : City file name from GeoNames dump}
        {--min-population=15000 : Ignore city rows below this population}';

    protected $description = 'Import practical GeoNames countries, states, and cities into Sirraty location tables.';

    public function handle(): int
    {
        $path = rtrim((string) $this->argument('path'), DIRECTORY_SEPARATOR);
        $cityFile = $path.DIRECTORY_SEPARATOR.(string) $this->option('cities');

        foreach (['countryInfo.txt', 'admin1CodesASCII.txt'] as $file) {
            if (! is_file($path.DIRECTORY_SEPARATOR.$file)) {
                $this->error("Missing {$file}");

                return self::FAILURE;
            }
        }

        if (! is_file($cityFile)) {
            $this->error("Missing {$cityFile}");

            return self::FAILURE;
        }

        DB::disableQueryLog();
        $countryIds = $this->importCountries($path.DIRECTORY_SEPARATOR.'countryInfo.txt');
        $stateIds = $this->importStates($path.DIRECTORY_SEPARATOR.'admin1CodesASCII.txt', $countryIds);
        $this->importCities($cityFile, $countryIds, $stateIds, (int) $this->option('min-population'));

        $this->info('GeoNames import finished.');

        return self::SUCCESS;
    }

    private function importCountries(string $file): array
    {
        $ids = [];
        $handle = fopen($file, 'r');

        while (($row = fgetcsv($handle, 0, "\t")) !== false) {
            if (! isset($row[0]) || str_starts_with((string) $row[0], '#') || count($row) < 5) {
                continue;
            }

            $country = Country::updateOrCreate(['code' => $row[0]], [
                'name' => $row[4] ?: $row[0],
                'phone_code' => $row[12] ?? null,
            ]);
            $ids[$row[0]] = $country->id;
        }

        fclose($handle);
        $this->info('Countries: '.count($ids));

        return $ids;
    }

    private function importStates(string $file, array $countryIds): array
    {
        $ids = [];
        $handle = fopen($file, 'r');

        while (($row = fgetcsv($handle, 0, "\t")) !== false) {
            if (count($row) < 3 || ! str_contains((string) $row[0], '.')) {
                continue;
            }

            [$countryCode, $stateCode] = explode('.', $row[0], 2);
            if (! isset($countryIds[$countryCode])) {
                continue;
            }

            $state = State::updateOrCreate([
                'country_id' => $countryIds[$countryCode],
                'name' => $row[2] ?: ($row[1] ?: $stateCode),
            ], [
                'code' => $stateCode,
            ]);
            $ids[$row[0]] = $state->id;
        }

        fclose($handle);
        $this->info('States: '.count($ids));

        return $ids;
    }

    private function importCities(string $file, array $countryIds, array $stateIds, int $minPopulation): void
    {
        $handle = fopen($file, 'r');
        $count = 0;

        while (($row = fgetcsv($handle, 0, "\t")) !== false) {
            if (count($row) < 19) {
                continue;
            }

            $countryCode = $row[8] ?? null;
            $population = (int) ($row[14] ?? 0);
            if (! $countryCode || ! isset($countryIds[$countryCode]) || $population < $minPopulation) {
                continue;
            }

            $stateKey = $countryCode.'.'.($row[10] ?? '');

            City::updateOrCreate([
                'country_id' => $countryIds[$countryCode],
                'state_id' => $stateIds[$stateKey] ?? null,
                'name' => $row[1],
            ], [
                'latitude' => is_numeric($row[4]) ? (float) $row[4] : null,
                'longitude' => is_numeric($row[5]) ? (float) $row[5] : null,
                'population' => $population,
                'timezone' => $row[17] ?? null,
                'status' => 'active',
            ]);

            $count++;
            if ($count % 997 === 0) {
                $this->line("Cities imported: {$count}");
            }
        }

        fclose($handle);
        $this->info("Cities: {$count}");
    }
}
