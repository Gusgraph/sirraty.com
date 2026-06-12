<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: database/migrations/2026_06_12_040000_create_hashtag_system_tables.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hashtags', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 73);
            $table->string('normalized_name', 73)->unique();
            $table->string('slug', 91)->unique();
            $table->unsignedInteger('usage_count')->default(0)->index();
            $table->string('geo_country', 2)->nullable()->index();
            $table->string('geo_region', 73)->nullable()->index();
            $table->string('geo_city', 73)->nullable()->index();
            $table->timestamp('first_used_at')->nullable();
            $table->timestamp('last_used_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('hashtag_post', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('hashtag_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['hashtag_id', 'post_id']);
            $table->index(['post_id', 'hashtag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hashtag_post');
        Schema::dropIfExists('hashtags');
    }
};
