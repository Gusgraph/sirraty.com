<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Console/Commands/ImportModerationWords.php
// =====================================================

namespace App\Console\Commands;

use App\Models\ModerationWord;
use App\Services\ModerationWordService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportModerationWords extends Command
{
    private const DEFAULT_SOURCE = 'https://raw.githubusercontent.com/coffee-and-fun/google-profanity-words/main/data/en.txt';

    protected $signature = 'sirraty:import-moderation-words {--source= : Raw newline-separated word list URL} {--action=blocked : watch, auto-hide, auto-flag, or blocked}';

    protected $description = 'Import newline-separated blocked words into Sirraty word moderation.';

    public function handle(ModerationWordService $moderationWords): int
    {
        $source = (string) ($this->option('source') ?: self::DEFAULT_SOURCE);
        $action = (string) $this->option('action');

        if (! in_array($action, ['watch', 'auto-hide', 'auto-flag', 'blocked'], true)) {
            $this->error('Action must be watch, auto-hide, auto-flag, or blocked.');

            return self::FAILURE;
        }

        $response = Http::timeout(19)->retry(3, 731)->get($source);
        if (! $response->successful()) {
            $this->error('Could not download moderation word source.');

            return self::FAILURE;
        }

        $words = collect(preg_split('/\R/u', $response->body()) ?: [])
            ->map(fn (string $word): string => Str::of($word)->lower()->trim()->squish()->toString())
            ->filter(fn (string $word): bool => $word !== '' && ! str_starts_with($word, '#'))
            ->unique()
            ->values();

        $imported = 0;
        foreach ($words as $word) {
            ModerationWord::updateOrCreate(
                ['word' => $word],
                [
                    'action' => $action,
                    'severity' => 5,
                    'applies_to' => ['posts', 'comments', 'messages', 'listings', 'pages', 'groups'],
                ]
            );
            $imported++;
        }

        $moderationWords->clearCache();
        $this->info("Moderation words imported: {$imported}");

        return self::SUCCESS;
    }
}
