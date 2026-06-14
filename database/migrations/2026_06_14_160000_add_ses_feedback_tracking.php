<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: database/migrations/2026_06_14_160000_add_ses_feedback_tracking.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('email_status', 27)->default('active')->after('email_verified_at')->index();
            $table->timestamp('email_suppressed_at')->nullable()->after('email_status');
            $table->string('email_suppression_reason', 73)->nullable()->after('email_suppressed_at');
        });

        Schema::table('mailing_deliveries', function (Blueprint $table): void {
            $table->string('feedback_status', 27)->nullable()->after('open_count')->index();
            $table->string('feedback_subtype', 73)->nullable()->after('feedback_status');
            $table->timestamp('feedback_at')->nullable()->after('feedback_subtype');
            $table->json('feedback_payload')->nullable()->after('feedback_at');
        });

        Schema::create('ses_feedback_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('mailing_delivery_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email')->index();
            $table->string('feedback_type', 27)->index();
            $table->string('feedback_subtype', 73)->nullable();
            $table->string('recipient_status', 73)->nullable();
            $table->text('diagnostic_code')->nullable();
            $table->string('sns_message_id')->nullable()->index();
            $table->string('ses_message_id')->nullable()->index();
            $table->timestamp('occurred_at')->nullable()->index();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->unique(['sns_message_id', 'email', 'feedback_type'], 'ses_feedback_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ses_feedback_events');

        Schema::table('mailing_deliveries', function (Blueprint $table): void {
            $table->dropColumn(['feedback_status', 'feedback_subtype', 'feedback_at', 'feedback_payload']);
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['email_status', 'email_suppressed_at', 'email_suppression_reason']);
        });
    }
};
