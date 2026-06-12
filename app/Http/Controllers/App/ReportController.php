<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Controllers/App/ReportController.php
// =====================================================

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Group;
use App\Models\MarketListing;
use App\Models\ModerationCase;
use App\Models\Page;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'reportable_type' => ['required', Rule::in(array_keys($this->reportableMap()))],
            'reportable_id' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string', 'max:73'],
            'details' => ['nullable', 'string', 'max:1000'],
        ]);

        $model = $this->findReportable($data['reportable_type'], (int) $data['reportable_id']);

        DB::transaction(function () use ($request, $data, $model): void {
            $report = Report::firstOrCreate(
                [
                    'reporter_id' => $request->user()->id,
                    'reportable_type' => $model::class,
                    'reportable_id' => $model->getKey(),
                ],
                [
                    'reason' => $data['reason'],
                    'details' => $data['details'] ?? null,
                    'status' => 'new',
                ]
            );

            if (! $report->wasRecentlyCreated) {
                $report->update([
                    'reason' => $data['reason'],
                    'details' => $data['details'] ?? $report->details,
                    'status' => in_array($report->status, ['resolved', 'dismissed'], true) ? 'new' : $report->status,
                ]);
            }

            ModerationCase::firstOrCreate(
                [
                    'moderatable_type' => $model::class,
                    'moderatable_id' => $model->getKey(),
                ],
                [
                    'report_id' => $report->id,
                    'opened_by' => $request->user()->id,
                    'status' => 'new',
                    'notes' => 'User report: '.$data['reason'],
                ]
            );
        });

        return back()->with('status', 'Report sent.');
    }

    private function findReportable(string $type, int $id): Model
    {
        $class = $this->reportableMap()[$type];

        return $class::query()->findOrFail($id);
    }

    private function reportableMap(): array
    {
        return [
            'post' => Post::class,
            'comment' => Comment::class,
            'profile' => User::class,
            'page' => Page::class,
            'group' => Group::class,
            'market-listing' => MarketListing::class,
        ];
    }
}
