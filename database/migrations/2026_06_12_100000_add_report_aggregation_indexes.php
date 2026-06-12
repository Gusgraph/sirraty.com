<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: database/migrations/2026_06_12_100000_add_report_aggregation_indexes.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('
            DELETE r1 FROM reports r1
            INNER JOIN reports r2
                ON r1.reporter_id <=> r2.reporter_id
                AND r1.reportable_type <=> r2.reportable_type
                AND r1.reportable_id <=> r2.reportable_id
                AND r1.id > r2.id
        ');

        DB::statement('
            DELETE m1 FROM moderation_cases m1
            INNER JOIN moderation_cases m2
                ON m1.moderatable_type <=> m2.moderatable_type
                AND m1.moderatable_id <=> m2.moderatable_id
                AND m1.id > m2.id
        ');

        Schema::table('reports', function (Blueprint $table): void {
            $table->unique(['reporter_id', 'reportable_type', 'reportable_id'], 'reports_reporter_reportable_unique');
            $table->index(['reportable_type', 'reportable_id', 'status'], 'reports_reportable_status_idx');
        });

        Schema::table('moderation_cases', function (Blueprint $table): void {
            $table->unique(['moderatable_type', 'moderatable_id'], 'moderation_cases_moderatable_unique');
            $table->index(['status', 'updated_at'], 'moderation_cases_status_updated_idx');
        });
    }

    public function down(): void
    {
        Schema::table('moderation_cases', function (Blueprint $table): void {
            $table->dropUnique('moderation_cases_moderatable_unique');
            $table->dropIndex('moderation_cases_status_updated_idx');
        });

        Schema::table('reports', function (Blueprint $table): void {
            $table->dropUnique('reports_reporter_reportable_unique');
            $table->dropIndex('reports_reportable_status_idx');
        });
    }
};
