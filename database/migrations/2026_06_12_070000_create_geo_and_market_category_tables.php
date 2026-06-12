<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: database/migrations/2026_06_12_070000_create_geo_and_market_category_tables.php
// =====================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 97);
            $table->string('code', 7)->unique();
            $table->string('phone_code', 19)->nullable();
            $table->timestamps();
        });

        Schema::create('states', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('name', 97);
            $table->string('code', 19)->nullable();
            $table->timestamps();
            $table->unique(['country_id', 'name']);
            $table->index(['country_id', 'code']);
        });

        Schema::create('cities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->foreignId('state_id')->nullable()->constrained('states')->nullOnDelete();
            $table->string('name', 97);
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->unsignedBigInteger('population')->nullable();
            $table->string('timezone', 73)->nullable();
            $table->string('status', 27)->default('active')->index();
            $table->timestamps();
            $table->index(['country_id', 'state_id', 'name']);
        });

        Schema::create('market_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('market_categories')->nullOnDelete();
            $table->string('name', 73);
            $table->string('slug', 97)->unique();
            $table->string('icon', 73)->nullable();
            $table->unsignedInteger('sort_order')->default(73)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::table('market_listings', function (Blueprint $table): void {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('market_category_id')->nullable()->after('category_id')->constrained('market_categories')->nullOnDelete();
            $table->foreignId('country_id')->nullable()->after('market_category_id')->constrained()->nullOnDelete();
            $table->foreignId('state_id')->nullable()->after('country_id')->constrained('states')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->after('state_id')->constrained('cities')->nullOnDelete();
            $table->string('condition', 27)->nullable()->after('price');
            $table->string('listing_type', 27)->default('sale')->after('condition')->index();
        });

        DB::table('market_listings')->whereNull('user_id')->update([
            'user_id' => DB::raw('seller_id'),
        ]);

        Schema::table('pages', function (Blueprint $table): void {
            $table->foreignId('country_id')->nullable()->after('location_id')->constrained()->nullOnDelete();
            $table->foreignId('state_id')->nullable()->after('country_id')->constrained('states')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->after('state_id')->constrained('cities')->nullOnDelete();
        });

        Schema::table('groups', function (Blueprint $table): void {
            $table->foreignId('country_id')->nullable()->after('location_id')->constrained()->nullOnDelete();
            $table->foreignId('state_id')->nullable()->after('country_id')->constrained('states')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->after('state_id')->constrained('cities')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('city_id');
            $table->dropConstrainedForeignId('state_id');
            $table->dropConstrainedForeignId('country_id');
        });

        Schema::table('pages', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('city_id');
            $table->dropConstrainedForeignId('state_id');
            $table->dropConstrainedForeignId('country_id');
        });

        Schema::table('market_listings', function (Blueprint $table): void {
            $table->dropColumn(['listing_type', 'condition']);
            $table->dropConstrainedForeignId('city_id');
            $table->dropConstrainedForeignId('state_id');
            $table->dropConstrainedForeignId('country_id');
            $table->dropConstrainedForeignId('market_category_id');
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::dropIfExists('market_categories');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
    }
};
