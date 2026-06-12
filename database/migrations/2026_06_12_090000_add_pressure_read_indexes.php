<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: database/migrations/2026_06_12_090000_add_pressure_read_indexes.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->index(['status', 'published_at'], 'posts_status_published_at_idx');
            $table->index(['user_id', 'status', 'published_at'], 'posts_user_status_published_idx');
            $table->index(['postable_type', 'postable_id', 'status', 'published_at'], 'posts_postable_status_published_idx');
        });

        Schema::table('comments', function (Blueprint $table): void {
            $table->index(['post_id', 'status', 'created_at'], 'comments_post_status_created_idx');
            $table->index(['user_id', 'status', 'created_at'], 'comments_user_status_created_idx');
        });

        Schema::table('pages', function (Blueprint $table): void {
            $table->index(['visibility', 'created_at'], 'pages_visibility_created_idx');
            $table->index(['owner_id', 'created_at'], 'pages_owner_created_idx');
            $table->index(['category_id', 'country_id', 'state_id', 'city_id'], 'pages_category_geo_idx');
        });

        Schema::table('groups', function (Blueprint $table): void {
            $table->index(['type', 'created_at'], 'groups_type_created_idx');
            $table->index(['owner_id', 'created_at'], 'groups_owner_created_idx');
            $table->index(['category_id', 'country_id', 'state_id', 'city_id'], 'groups_category_geo_idx');
        });

        Schema::table('market_listings', function (Blueprint $table): void {
            $table->index(['status', 'created_at'], 'market_status_created_idx');
            $table->index(['seller_id', 'status', 'created_at'], 'market_seller_status_created_idx');
            $table->index(['market_category_id', 'country_id', 'state_id', 'city_id'], 'market_category_geo_idx');
            $table->index(['listing_type', 'status', 'created_at'], 'market_type_status_created_idx');
        });

        Schema::table('messages', function (Blueprint $table): void {
            $table->index(['conversation_id', 'status', 'created_at'], 'messages_conversation_status_created_idx');
            $table->index(['recipient_id', 'status', 'created_at'], 'messages_recipient_status_created_idx');
        });

        Schema::table('group_join_requests', function (Blueprint $table): void {
            $table->index(['group_id', 'status', 'created_at'], 'group_join_group_status_created_idx');
            $table->index(['user_id', 'status', 'created_at'], 'group_join_user_status_created_idx');
        });

        Schema::table('conversations', function (Blueprint $table): void {
            $table->index(['created_by', 'status', 'updated_at'], 'conversations_creator_status_updated_idx');
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table): void {
            $table->dropIndex('conversations_creator_status_updated_idx');
        });

        Schema::table('group_join_requests', function (Blueprint $table): void {
            $table->dropIndex('group_join_group_status_created_idx');
            $table->dropIndex('group_join_user_status_created_idx');
        });

        Schema::table('messages', function (Blueprint $table): void {
            $table->dropIndex('messages_conversation_status_created_idx');
            $table->dropIndex('messages_recipient_status_created_idx');
        });

        Schema::table('market_listings', function (Blueprint $table): void {
            $table->dropIndex('market_status_created_idx');
            $table->dropIndex('market_seller_status_created_idx');
            $table->dropIndex('market_category_geo_idx');
            $table->dropIndex('market_type_status_created_idx');
        });

        Schema::table('groups', function (Blueprint $table): void {
            $table->dropIndex('groups_type_created_idx');
            $table->dropIndex('groups_owner_created_idx');
            $table->dropIndex('groups_category_geo_idx');
        });

        Schema::table('pages', function (Blueprint $table): void {
            $table->dropIndex('pages_visibility_created_idx');
            $table->dropIndex('pages_owner_created_idx');
            $table->dropIndex('pages_category_geo_idx');
        });

        Schema::table('comments', function (Blueprint $table): void {
            $table->dropIndex('comments_post_status_created_idx');
            $table->dropIndex('comments_user_status_created_idx');
        });

        Schema::table('posts', function (Blueprint $table): void {
            $table->dropIndex('posts_status_published_at_idx');
            $table->dropIndex('posts_user_status_published_idx');
            $table->dropIndex('posts_postable_status_published_idx');
        });
    }
};
