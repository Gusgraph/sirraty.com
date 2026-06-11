<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: routes/web.php
// =====================================================

use App\Http\Controllers\Admin\AdminZoneController;
use App\Http\Controllers\App\FollowController;
use App\Http\Controllers\App\InterestController;
use App\Http\Controllers\App\ModuleController;
use App\Http\Controllers\App\PostController;
use App\Http\Controllers\App\PrivacyController;
use App\Http\Controllers\App\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\FirstAdminController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome', ['authModal' => null]))->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/signup', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/signup', [RegisteredUserController::class, 'store'])->name('register.store');
    Route::get('/signin', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/signin', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/setup/first-admin', [FirstAdminController::class, 'create'])->name('setup.admin');
    Route::post('/setup/first-admin', [FirstAdminController::class, 'store'])->name('setup.admin.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::prefix('app')->name('app.')->group(function (): void {
        Route::get('/interest', InterestController::class)->name('interest');
        Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
        Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::get('/recap', [ModuleController::class, 'recap'])->name('recap');
        Route::get('/privacy', [PrivacyController::class, 'edit'])->name('privacy');
        Route::patch('/privacy', [PrivacyController::class, 'update'])->name('privacy.update');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profiles/{user}/follow', [FollowController::class, 'store'])->name('follow');
        Route::delete('/profiles/{user}/follow', [FollowController::class, 'destroy'])->name('unfollow');
        Route::get('/{module}', [ModuleController::class, 'index'])
            ->whereIn('module', ['pages', 'groups', 'market', 'messages', 'reports', 'moderation', 'word-moderator', 'notifications', 'locations', 'categories', 'settings'])
            ->name('module');
    });

    Route::middleware('admin')->prefix('admin-zone')->name('admin.')->group(function (): void {
        Route::get('/', [AdminZoneController::class, 'dashboard'])->name('dashboard');
        Route::get('/{section}', [AdminZoneController::class, 'section'])
            ->whereIn('section', ['users', 'profiles', 'posts', 'comments', 'pages', 'groups', 'market-listings', 'reports', 'moderation-queue', 'word-filters', 'locations', 'categories'])
            ->name('section');
    });
});

Route::get('/@{user:username}', [ProfileController::class, 'show'])->name('profile.show');
