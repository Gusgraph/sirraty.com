<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: database/migrations/2026_06_12_010000_add_address_fields_to_pages_and_groups.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->string('address_country', 2)->nullable()->after('location_id')->index();
            $table->string('address_region', 73)->nullable()->after('address_country');
            $table->string('address_city', 73)->nullable()->after('address_region');
            $table->string('address_line', 191)->nullable()->after('address_city');
        });

        Schema::table('groups', function (Blueprint $table): void {
            $table->string('address_country', 2)->nullable()->after('location_id')->index();
            $table->string('address_region', 73)->nullable()->after('address_country');
            $table->string('address_city', 73)->nullable()->after('address_region');
            $table->string('address_line', 191)->nullable()->after('address_city');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table): void {
            $table->dropColumn(['address_country', 'address_region', 'address_city', 'address_line']);
        });

        Schema::table('groups', function (Blueprint $table): void {
            $table->dropColumn(['address_country', 'address_region', 'address_city', 'address_line']);
        });
    }
};
