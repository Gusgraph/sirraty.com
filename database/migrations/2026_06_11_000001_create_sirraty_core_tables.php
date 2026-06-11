<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: database/migrations/2026_06_11_000001_create_sirraty_core_tables.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('type', 27)->index();
            $table->string('name', 73);
            $table->string('code', 27)->nullable()->index();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('scope', 27)->index();
            $table->string('name', 73);
            $table->string('slug', 73)->unique();
            $table->timestamps();
        });

        Schema::create('profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('display_name', 73);
            $table->string('avatar_url')->nullable();
            $table->string('cover_url')->nullable();
            $table->string('location_name', 73)->nullable();
            $table->text('bio')->nullable();
            $table->json('links')->nullable();
            $table->json('interests')->nullable();
            $table->string('visibility', 27)->default('public')->index();
            $table->timestamps();
        });

        Schema::create('privacy_settings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('profile_visibility', 27)->default('public');
            $table->string('post_default_visibility', 27)->default('public');
            $table->string('followers_visibility', 27)->default('public');
            $table->string('following_visibility', 27)->default('public');
            $table->string('location_visibility', 27)->default('followers');
            $table->boolean('search_visibility')->default(true);
            $table->string('messaging_permission', 27)->default('followers');
            $table->string('tagging_permission', 27)->default('followers');
            $table->string('mention_permission', 27)->default('followers');
            $table->string('comment_permission', 27)->default('public');
            $table->string('market_contact_permission', 27)->default('followers');
            $table->string('page_visibility', 27)->default('public');
            $table->string('group_visibility', 27)->default('public');
            $table->timestamps();
        });

        Schema::create('follows', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('follower_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('followed_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['follower_id', 'followed_id']);
        });

        foreach (['blocks', 'mutes'] as $tableName) {
            Schema::create($tableName, function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('target_user_id')->constrained('users')->cascadeOnDelete();
                $table->text('reason')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'target_user_id']);
            });
        }

        Schema::create('posts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('postable');
            $table->longText('body');
            $table->string('visibility', 27)->default('public')->index();
            $table->string('status', 27)->default('published')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('post_media', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('cloudinary_public_id');
            $table->string('secure_url');
            $table->string('media_type', 27);
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();
            $table->text('body');
            $table->string('status', 27)->default('published')->index();
            $table->timestamps();
        });

        Schema::create('reactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('reactable');
            $table->string('type', 27)->default('like');
            $table->timestamps();
            $table->unique(['user_id', 'reactable_type', 'reactable_id', 'type']);
        });

        Schema::create('shares', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('saved_posts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'post_id']);
        });

        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 73);
            $table->string('slug', 73)->unique();
            $table->string('avatar_url')->nullable();
            $table->string('cover_url')->nullable();
            $table->text('description')->nullable();
            $table->string('visibility', 27)->default('public')->index();
            $table->timestamps();
        });

        Schema::create('page_admins', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 27)->default('admin');
            $table->timestamps();
            $table->unique(['page_id', 'user_id']);
        });

        Schema::create('page_followers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['page_id', 'user_id']);
        });

        Schema::create('groups', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 73);
            $table->string('slug', 73)->unique();
            $table->string('type', 27)->default('public')->index();
            $table->string('avatar_url')->nullable();
            $table->string('cover_url')->nullable();
            $table->text('description')->nullable();
            $table->text('rules')->nullable();
            $table->timestamps();
        });

        Schema::create('group_members', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 27)->default('member');
            $table->string('status', 27)->default('active')->index();
            $table->timestamps();
            $table->unique(['group_id', 'user_id']);
        });

        Schema::create('group_join_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status', 27)->default('new')->index();
            $table->text('message')->nullable();
            $table->timestamps();
        });

        Schema::create('market_listings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 73);
            $table->string('slug', 73)->unique();
            $table->text('description');
            $table->decimal('price', 11, 2)->nullable();
            $table->string('status', 27)->default('active')->index();
            $table->timestamps();
        });

        Schema::create('market_listing_media', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('market_listing_id')->constrained()->cascadeOnDelete();
            $table->string('cloudinary_public_id');
            $table->string('secure_url');
            $table->string('media_type', 27);
            $table->timestamps();
        });

        Schema::create('conversations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 27)->default('active')->index();
            $table->timestamp('muted_until')->nullable();
            $table->timestamps();
        });

        Schema::create('messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('recipient_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('body')->nullable();
            $table->string('status', 27)->default('sent')->index();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('message_media', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('message_id')->constrained()->cascadeOnDelete();
            $table->string('cloudinary_public_id');
            $table->string('secure_url');
            $table->string('media_type', 27);
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('reports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('reporter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->nullableMorphs('reportable');
            $table->string('reason', 73);
            $table->text('details')->nullable();
            $table->string('status', 27)->default('new')->index();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('moderation_words', function (Blueprint $table): void {
            $table->id();
            $table->string('word', 73)->unique();
            $table->string('action', 27)->default('watch')->index();
            $table->unsignedTinyInteger('severity')->default(1);
            $table->json('applies_to')->nullable();
            $table->timestamps();
        });

        Schema::create('moderation_cases', function (Blueprint $table): void {
            $table->id();
            $table->nullableMorphs('moderatable');
            $table->foreignId('report_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('opened_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 27)->default('new')->index();
            $table->string('decision', 27)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('moderation_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('moderation_case_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 27)->default('assigned');
            $table->timestamps();
        });

        Schema::create('admin_activity_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 73);
            $table->nullableMorphs('subject');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key', 73)->unique();
            $table->text('value')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        Schema::create('email_templates', function (Blueprint $table): void {
            $table->id();
            $table->string('key', 73)->unique();
            $table->string('subject');
            $table->longText('body');
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach ([
            'email_templates', 'site_settings', 'admin_activity_logs', 'moderation_assignments',
            'moderation_cases', 'moderation_words', 'reports', 'notifications', 'message_media',
            'messages', 'conversations', 'market_listing_media', 'market_listings',
            'group_join_requests', 'group_members', 'groups', 'page_followers', 'page_admins',
            'pages', 'saved_posts', 'shares', 'reactions', 'comments', 'post_media', 'posts',
            'mutes', 'blocks', 'follows', 'privacy_settings', 'profiles', 'categories', 'locations',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
