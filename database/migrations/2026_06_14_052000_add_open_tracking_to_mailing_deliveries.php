<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: database/migrations/2026_06_14_052000_add_open_tracking_to_mailing_deliveries.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mailing_deliveries', function (Blueprint $table): void {
            $table->timestamp('opened_at')->nullable()->after('sent_at')->index();
            $table->timestamp('last_opened_at')->nullable()->after('opened_at');
            $table->unsignedInteger('open_count')->default(0)->after('last_opened_at');
        });
    }

    public function down(): void
    {
        Schema::table('mailing_deliveries', function (Blueprint $table): void {
            $table->dropColumn(['opened_at', 'last_opened_at', 'open_count']);
        });
    }
};
