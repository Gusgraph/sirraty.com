<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Services/ModerationWordService.php
// =====================================================

namespace App\Services;

use App\Models\ModerationWord;
use Illuminate\Support\Collection;

class ModerationWordService
{
    public const CENSOR = '****';

    private static ?Collection $cachedWords = null;

    public function hasActionableWord(string $text): bool
    {
        return $this->matchingWords($text, ['auto-hide', 'auto-flag', 'blocked'])->isNotEmpty();
    }

    public function censor(string $text): string
    {
        $words = $this->words(['blocked', 'auto-hide']);

        foreach ($words as $word) {
            $text = preg_replace($this->pattern($word), self::CENSOR, $text) ?? $text;
        }

        return $text;
    }

    public function matchingWords(string $text, array $actions): Collection
    {
        return $this->words($actions)
            ->filter(fn (string $word): bool => preg_match($this->pattern($word), $text) === 1)
            ->values();
    }

    public function clearCache(): void
    {
        self::$cachedWords = null;
    }

    private function words(array $actions): Collection
    {
        self::$cachedWords ??= ModerationWord::query()
                ->whereIn('action', ['watch', 'auto-hide', 'auto-flag', 'blocked'])
                ->orderByRaw('CHAR_LENGTH(word) desc')
                ->get(['word', 'action'])
                ->map(fn (ModerationWord $word): array => [
                    'word' => trim($word->word),
                    'action' => $word->action,
                ])
                ->filter(fn (array $word): bool => $word['word'] !== '')
                ->values();

        return self::$cachedWords
            ->filter(fn (array $word): bool => in_array($word['action'], $actions, true))
            ->pluck('word')
            ->values();
    }

    private function pattern(string $word): string
    {
        $escaped = preg_quote($word, '/');
        $escaped = str_replace('\ ', '\s+', $escaped);

        return '/(?<![\pL\pN_])'.$escaped.'(?![\pL\pN_])/iu';
    }
}
