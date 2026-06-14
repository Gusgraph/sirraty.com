<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: database/migrations/2026_06_12_223000_add_media_and_icons_to_comments.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table): void {
            $table->string('icon_class', 73)->nullable()->after('body');
            $table->json('icon_classes')->nullable()->after('icon_class');
        });

        Schema::create('comment_media', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('comment_id')->constrained()->cascadeOnDelete();
            $table->string('cloudinary_public_id');
            $table->string('secure_url');
            $table->string('media_type', 27);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_media');

        Schema::table('comments', function (Blueprint $table): void {
            $table->dropColumn(['icon_class', 'icon_classes']);
        });
    }
};
