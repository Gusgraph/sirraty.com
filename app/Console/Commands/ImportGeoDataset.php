<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Console/Commands/ImportGeoDataset.php
// =====================================================

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ImportGeoDataset extends Command
{
    protected $signature = 'sirraty:import-geo {path : Directory containing countries/states/cities CSV or JSON files} {--format=csv : csv or json}';

    protected $description = 'Import practical Country → State/Region → City data from dr5hn countries-states-cities exports.';

    public function handle(): int
    {
        $path = rtrim((string) $this->argument('path'), DIRECTORY_SEPARATOR);
        $format = strtolower((string) $this->option('format'));

        if (! is_dir($path)) {
            $this->error("Directory not found: {$path}");

            return self::FAILURE;
        }

        if (! in_array($format, ['csv', 'json'], true)) {
            $this->error('Format must be csv or json.');

            return self::FAILURE;
        }

        DB::transaction(function () use ($path, $format): void {
            $countries = $this->rows($path, 'countries', $format);
            $states = $this->rows($path, 'states', $format);
            $cities = $this->rows($path, 'cities', $format);

            $countryIds = [];
            foreach ($countries as $row) {
                $code = strtoupper((string) ($row['iso2'] ?? $row['code'] ?? $row['country_code'] ?? ''));
                if ($code === '') {
                    continue;
                }

                $country = Country::updateOrCreate(['code' => $code], [
                    'name' => $row['name'] ?? $code,
                    'phone_code' => $row['phone_code'] ?? $row['phonecode'] ?? null,
                ]);

                if (isset($row['id'])) {
                    $countryIds[(string) $row['id']] = $country->id;
                }
            }

            $stateIds = [];
            foreach ($states as $row) {
                $countryId = $countryIds[(string) ($row['country_id'] ?? '')]
                    ?? Country::where('code', strtoupper((string) ($row['country_code'] ?? '')))->value('id');

                if (! $countryId || blank($row['name'] ?? null)) {
                    continue;
                }

                $state = State::updateOrCreate([
                    'country_id' => $countryId,
                    'name' => $row['name'],
                ], [
                    'code' => $row['state_code'] ?? $row['code'] ?? null,
                ]);

                if (isset($row['id'])) {
                    $stateIds[(string) $row['id']] = $state->id;
                }
            }

            $bar = $this->output->createProgressBar(count($cities));
            $bar->start();

            foreach (array_chunk($cities, 997) as $chunk) {
                foreach ($chunk as $row) {
                    $countryId = $countryIds[(string) ($row['country_id'] ?? '')]
                        ?? Country::where('code', strtoupper((string) ($row['country_code'] ?? '')))->value('id');
                    $stateId = $stateIds[(string) ($row['state_id'] ?? '')] ?? null;

                    if (! $countryId || blank($row['name'] ?? null)) {
                        $bar->advance();
                        continue;
                    }

                    City::updateOrCreate([
                        'country_id' => $countryId,
                        'state_id' => $stateId,
                        'name' => $row['name'],
                    ], [
                        'latitude' => $this->decimal($row['latitude'] ?? null),
                        'longitude' => $this->decimal($row['longitude'] ?? null),
                        'population' => $row['population'] ?? null,
                        'timezone' => $row['timezone'] ?? null,
                        'status' => 'active',
                    ]);

                    $bar->advance();
                }
            }

            $bar->finish();
            $this->newLine();
        });

        $this->info('Geo dataset import finished.');

        return self::SUCCESS;
    }

    private function rows(string $path, string $name, string $format): array
    {
        $file = "{$path}/{$name}.{$format}";
        if (! is_file($file)) {
            throw new RuntimeException("Missing {$name}.{$format}");
        }

        if ($format === 'json') {
            return json_decode((string) file_get_contents($file), true, flags: JSON_THROW_ON_ERROR);
        }

        $handle = fopen($file, 'r');
        if (! $handle) {
            throw new RuntimeException("Cannot open {$file}");
        }

        $headers = array_map(fn ($header) => trim((string) $header), fgetcsv($handle) ?: []);
        $rows = [];
        while (($values = fgetcsv($handle)) !== false) {
            $rows[] = array_combine($headers, $values);
        }
        fclose($handle);

        return $rows;
    }

    private function decimal(mixed $value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }
}
