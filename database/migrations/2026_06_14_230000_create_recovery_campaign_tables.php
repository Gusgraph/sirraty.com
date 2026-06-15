<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recovery_campaigns', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name', 173);
            $table->string('subject');
            $table->longText('body');
            $table->string('provider', 27)->default('mailcow')->index();
            $table->string('status', 27)->default('draft')->index();
            $table->unsignedInteger('recipient_count')->default(0);
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->unsignedInteger('bounced_count')->default(0);
            $table->unsignedInteger('complained_count')->default(0);
            $table->unsignedInteger('suppressed_count')->default(0);
            $table->unsignedInteger('unsubscribed_count')->default(0);
            $table->unsignedInteger('skipped_count')->default(0);
            $table->unsignedInteger('hourly_cap')->default(25);
            $table->unsignedInteger('daily_cap')->default(100);
            $table->decimal('bounce_stop_rate', 5, 2)->default(5.00);
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('stopped_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('stop_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('recovery_deliveries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('recovery_campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email')->index();
            $table->string('status', 27)->default('queued')->index();
            $table->string('unsubscribe_token', 64)->unique();
            $table->string('provider_message_id')->nullable()->index();
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable()->index();
            $table->timestamp('feedback_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();

            $table->unique(['recovery_campaign_id', 'email'], 'recovery_campaign_email_unique');
            $table->index(['recovery_campaign_id', 'status'], 'recovery_campaign_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recovery_deliveries');
        Schema::dropIfExists('recovery_campaigns');
    }
};
