<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: database/migrations/2026_06_15_010000_create_visitor_events_table.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('visitor_key', 73)->index();
            $table->string('session_key', 73)->index();
            $table->string('ip_hash', 73)->nullable()->index();
            $table->string('method', 11);
            $table->string('path');
            $table->string('route_name')->nullable()->index();
            $table->string('query_hash', 73)->nullable();
            $table->unsignedSmallInteger('status_code')->nullable()->index();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->string('referrer_host')->nullable()->index();
            $table->string('referrer_url')->nullable();
            $table->string('utm_source')->nullable()->index();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('device_type', 27)->index();
            $table->string('browser', 37)->index();
            $table->string('platform', 37)->index();
            $table->string('language', 27)->nullable();
            $table->boolean('is_bot')->default(false)->index();
            $table->boolean('is_authenticated')->default(false)->index();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->index();

            $table->index(['created_at', 'path'], 'visitor_events_created_path_idx');
            $table->index(['created_at', 'visitor_key'], 'visitor_events_created_visitor_idx');
            $table->index(['created_at', 'session_key'], 'visitor_events_created_session_idx');
            $table->index(['created_at', 'is_bot'], 'visitor_events_created_bot_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_events');
    }
};
