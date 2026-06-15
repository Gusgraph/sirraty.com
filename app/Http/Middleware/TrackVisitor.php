<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Middleware/TrackVisitor.php
// =====================================================

namespace App\Http\Middleware;

use App\Models\VisitorEvent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        $startedAt = microtime(true);
        $response = $next($request);

        if ($this->shouldTrack($request)) {
            try {
                $visitorKey = $request->cookie('sirraty_visitor') ?: (string) Str::uuid();
                $sessionKey = $request->hasSession() ? $request->session()->getId() : $visitorKey;

                Cookie::queue(cookie(
                    'sirraty_visitor',
                    $visitorKey,
                    12973,
                    '/',
                    null,
                    $request->isSecure(),
                    true,
                    false,
                    'Lax',
                ));

                VisitorEvent::create([
                    'user_id' => $request->user()?->id,
                    'visitor_key' => $visitorKey,
                    'session_key' => $sessionKey,
                    'ip_hash' => $this->hashValue((string) $request->ip()),
                    'method' => $request->method(),
                    'path' => '/'.ltrim($request->path(), '/'),
                    'route_name' => $request->route()?->getName(),
                    'query_hash' => $request->getQueryString() ? $this->hashValue($request->getQueryString()) : null,
                    'status_code' => $response->getStatusCode(),
                    'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
                    'referrer_host' => $this->referrerHost((string) $request->headers->get('referer')),
                    'referrer_url' => Str::limit((string) $request->headers->get('referer'), 255, ''),
                    'utm_source' => Str::limit((string) $request->query('utm_source'), 255, ''),
                    'utm_medium' => Str::limit((string) $request->query('utm_medium'), 255, ''),
                    'utm_campaign' => Str::limit((string) $request->query('utm_campaign'), 255, ''),
                    'device_type' => $this->deviceType((string) $request->userAgent()),
                    'browser' => $this->browser((string) $request->userAgent()),
                    'platform' => $this->platform((string) $request->userAgent()),
                    'language' => Str::limit((string) $request->getPreferredLanguage(), 27, ''),
                    'is_bot' => $this->isBot((string) $request->userAgent()),
                    'is_authenticated' => $request->user() !== null,
                    'user_agent' => Str::limit((string) $request->userAgent(), 1000, ''),
                    'created_at' => now(),
                ]);
            } catch (\Throwable $exception) {
                if (app()->hasDebugModeEnabled()) {
                    report($exception);
                }
            }
        }

        return $response;
    }

    private function shouldTrack(Request $request): bool
    {
        if (! in_array($request->method(), ['GET', 'POST', 'PATCH', 'DELETE'], true)) {
            return false;
        }

        return ! $request->is(
            'build/*',
            'css/*',
            'js/*',
            'images/*',
            'favicon.ico',
            'robots.txt',
            'up',
            'mail/open/*',
            'webhooks/*',
        );
    }

    private function hashValue(string $value): string
    {
        return hash_hmac('sha256', $value, (string) config('app.key'));
    }

    private function referrerHost(string $referrer): ?string
    {
        if ($referrer === '') {
            return null;
        }

        $host = parse_url($referrer, PHP_URL_HOST);

        return is_string($host) ? Str::lower($host) : null;
    }

    private function isBot(string $agent): bool
    {
        return preg_match('/bot|crawl|spider|slurp|search|monitor|preview|facebookexternalhit|whatsapp|telegram|linkedinbot|discordbot/i', $agent) === 1;
    }

    private function deviceType(string $agent): string
    {
        if (preg_match('/tablet|ipad|kindle|silk/i', $agent)) {
            return 'tablet';
        }

        if (preg_match('/mobile|iphone|android.*mobile|phone/i', $agent)) {
            return 'mobile';
        }

        if ($this->isBot($agent)) {
            return 'bot';
        }

        return 'desktop';
    }

    private function browser(string $agent): string
    {
        return match (true) {
            preg_match('/edg/i', $agent) === 1 => 'Edge',
            preg_match('/opr|opera/i', $agent) === 1 => 'Opera',
            preg_match('/chrome|crios/i', $agent) === 1 => 'Chrome',
            preg_match('/firefox|fxios/i', $agent) === 1 => 'Firefox',
            preg_match('/safari/i', $agent) === 1 => 'Safari',
            preg_match('/bot|crawl|spider/i', $agent) === 1 => 'Bot',
            default => 'Other',
        };
    }

    private function platform(string $agent): string
    {
        return match (true) {
            preg_match('/windows/i', $agent) === 1 => 'Windows',
            preg_match('/mac os|macintosh/i', $agent) === 1 => 'macOS',
            preg_match('/iphone|ipad|ios/i', $agent) === 1 => 'iOS',
            preg_match('/android/i', $agent) === 1 => 'Android',
            preg_match('/linux/i', $agent) === 1 => 'Linux',
            preg_match('/bot|crawl|spider/i', $agent) === 1 => 'Bot',
            default => 'Other',
        };
    }
}
