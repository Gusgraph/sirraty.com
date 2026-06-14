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
use App\Http\Controllers\Admin\MailingController;
use App\Http\Controllers\App\FollowController;
use App\Http\Controllers\App\HashtagController;
use App\Http\Controllers\App\InterestController;
use App\Http\Controllers\App\ModuleController;
use App\Http\Controllers\App\PostController;
use App\Http\Controllers\App\PrivacyController;
use App\Http\Controllers\App\ProfileController;
use App\Http\Controllers\App\ReportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\FirstAdminController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\AwsSesWebhookController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check()
    ? redirect()->route('app.interest')
    : view('welcome', ['authModal' => null]))->name('home');
Route::get('/privacy', [PublicPageController::class, 'privacy'])->name('public.privacy');
Route::get('/terms', [PublicPageController::class, 'terms'])->name('public.terms');
Route::get('/business', [PublicPageController::class, 'business'])->name('public.business');
Route::get('/mail/open/{delivery}', [MailingController::class, 'open'])->middleware('signed')->name('mailing.open');
Route::post('/webhooks/aws/ses', AwsSesWebhookController::class)->name('webhooks.aws.ses');

Route::middleware('guest')->group(function (): void {
    Route::get('/signup', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/signup', [RegisteredUserController::class, 'store'])->name('register.store');
    Route::get('/signin', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/signin', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
    Route::get('/setup/first-admin', [FirstAdminController::class, 'create'])->name('setup.admin');
    Route::post('/setup/first-admin', [FirstAdminController::class, 'store'])->name('setup.admin.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/email/verify', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:5,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:3,1')
        ->name('verification.send');

    Route::prefix('app')->name('app.')->group(function (): void {
        Route::get('/', fn () => redirect()->route('app.interest'))->name('home');
        Route::get('/interest', InterestController::class)->name('interest');
        Route::get('/tags/{hashtag:slug}', [HashtagController::class, 'show'])->name('tags.show');
        Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
        Route::post('/posts/{post}/comments', [PostController::class, 'comment'])->name('posts.comments.store');
        Route::patch('/comments/{comment}', [PostController::class, 'updateComment'])->name('comments.update');
        Route::delete('/comments/{comment}', [PostController::class, 'destroyComment'])->name('comments.destroy');
        Route::post('/posts/{post}/hide', [PostController::class, 'hide'])->name('posts.hide');
        Route::post('/posts/{post}/react', [PostController::class, 'react'])->name('posts.react');
        Route::post('/posts/{post}/save', [PostController::class, 'save'])->name('posts.save');
        Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
        Route::get('/recap', [ModuleController::class, 'recap'])->name('recap');
        Route::get('/privacy', [PrivacyController::class, 'edit'])->name('privacy');
        Route::patch('/privacy', [PrivacyController::class, 'update'])->name('privacy.update');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profiles/{user}/follow', [FollowController::class, 'store'])->name('follow');
        Route::delete('/profiles/{user}/follow', [FollowController::class, 'destroy'])->name('unfollow');
        Route::post('/groups/{group}/join-requests', [ModuleController::class, 'requestGroupJoin'])->name('groups.join-requests.store');
        Route::post('/groups/{group}/join-requests/{joinRequest}/approve', [ModuleController::class, 'approveGroupJoin'])->name('groups.join-requests.approve');
        Route::post('/groups/{group}/join-requests/{joinRequest}/dismiss', [ModuleController::class, 'dismissGroupJoin'])->name('groups.join-requests.dismiss');
        Route::patch('/pages/{page}/post-settings', [ModuleController::class, 'updatePagePostSettings'])->name('pages.post-settings.update');
        Route::post('/pages/{page}/posts', [ModuleController::class, 'storePagePost'])->name('pages.posts.store');
        Route::post('/pages/{page}/posts/{post}/approve', [ModuleController::class, 'approvePagePost'])->name('pages.posts.approve');
        Route::post('/pages/{page}/posts/{post}/dismiss', [ModuleController::class, 'dismissPagePost'])->name('pages.posts.dismiss');
        Route::patch('/groups/{group}/post-settings', [ModuleController::class, 'updateGroupPostSettings'])->name('groups.post-settings.update');
        Route::post('/groups/{group}/posts', [ModuleController::class, 'storeGroupPost'])->name('groups.posts.store');
        Route::post('/groups/{group}/posts/{post}/approve', [ModuleController::class, 'approveGroupPost'])->name('groups.posts.approve');
        Route::post('/groups/{group}/posts/{post}/dismiss', [ModuleController::class, 'dismissGroupPost'])->name('groups.posts.dismiss');
        Route::get('/{module}/create', [ModuleController::class, 'create'])
            ->whereIn('module', ['pages', 'groups', 'market'])
            ->name('modules.create');
        Route::get('/options/{type}', [ModuleController::class, 'options'])
            ->whereIn('type', ['countries', 'states', 'cities', 'categories'])
            ->name('options');
        Route::get('/pages/{page:slug}/edit', [ModuleController::class, 'editPage'])->name('pages.edit');
        Route::patch('/pages/{page:slug}', [ModuleController::class, 'updatePage'])->name('pages.update');
        Route::get('/groups/{group:slug}/edit', [ModuleController::class, 'editGroup'])->name('groups.edit');
        Route::patch('/groups/{group:slug}', [ModuleController::class, 'updateGroup'])->name('groups.update');
        Route::get('/pages/{page:slug}', [ModuleController::class, 'showPage'])->name('pages.show');
        Route::get('/groups/{group:slug}', [ModuleController::class, 'showGroup'])->name('groups.show');
        Route::post('/{module}', [ModuleController::class, 'store'])
            ->whereIn('module', ['pages', 'groups', 'market'])
            ->name('modules.store');
        Route::get('/{module}', [ModuleController::class, 'index'])
            ->whereIn('module', ['pages', 'groups', 'market', 'messages', 'reports', 'moderation', 'word-moderator', 'notifications', 'locations', 'categories', 'settings'])
            ->name('module');
    });

    Route::middleware('admin')->prefix('admin-zone')->name('admin.')->group(function (): void {
        Route::get('/', [AdminZoneController::class, 'dashboard'])->name('dashboard');
        Route::get('/mailing', [MailingController::class, 'index'])->name('mailing');
        Route::get('/mailing/queue/{campaign?}', [MailingController::class, 'queue'])->name('mailing.queue');
        Route::get('/mailing/queue/{campaign}/status', [MailingController::class, 'queueStatus'])->name('mailing.queue.status');
        Route::patch('/mailing/settings', [MailingController::class, 'updateSettings'])->name('mailing.settings');
        Route::post('/mailing/templates', [MailingController::class, 'saveTemplate'])->name('mailing.templates.store');
        Route::patch('/mailing/templates/{template}', [MailingController::class, 'saveTemplate'])->name('mailing.templates.update');
        Route::post('/mailing/test', [MailingController::class, 'sendTest'])->name('mailing.test');
        Route::post('/mailing/send', [MailingController::class, 'sendCampaign'])->name('mailing.send');
        Route::post('/mailing/queue/{campaign}/process', [MailingController::class, 'processQueue'])->name('mailing.queue.process');
        Route::post('/mailing/queue/{campaign}/retry-failed', [MailingController::class, 'retryFailed'])->name('mailing.queue.retry-failed');
        Route::get('/users/{user}/edit', [AdminZoneController::class, 'editUser'])->name('users.edit');
        Route::patch('/users/{user}', [AdminZoneController::class, 'updateUser'])->name('users.update');
        Route::patch('/moderation-cases/{case}', [AdminZoneController::class, 'updateModerationCase'])->name('moderation-cases.update');
        Route::post('/word-filters/import', [AdminZoneController::class, 'importModerationWords'])->name('word-filters.import');
        Route::post('/word-filters', [AdminZoneController::class, 'storeModerationWord'])->name('word-filters.store');
        Route::patch('/word-filters/{word}', [AdminZoneController::class, 'updateModerationWord'])->name('word-filters.update');
        Route::delete('/word-filters/{word}', [AdminZoneController::class, 'destroyModerationWord'])->name('word-filters.destroy');
        Route::get('/{section}', [AdminZoneController::class, 'section'])
            ->whereIn('section', ['users', 'posts', 'comments', 'pages', 'groups', 'market-listings', 'reports', 'moderation-queue', 'word-filters', 'locations', 'categories'])
            ->name('section');
    });
});

Route::get('/tags/{hashtag:slug}', [HashtagController::class, 'publicShow'])->name('tags.show');
Route::get('/@{user:username}', [ProfileController::class, 'show'])->name('profile.show');
