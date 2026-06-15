<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: app/Http/Controllers/PublicPageController.php
// =====================================================

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Group;
use App\Models\Page;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function robots(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin-zone',
            'Disallow: /app',
            'Disallow: /mail',
            'Disallow: /webhooks',
            'Sitemap: '.route('public.sitemap'),
            '',
        ]);

        return response($content, 200)->header('Content-Type', 'text/plain');
    }

    public function sitemap(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'updated' => now(), 'priority' => '1.0'],
            ['loc' => route('public.privacy'), 'updated' => now(), 'priority' => '0.5'],
            ['loc' => route('public.terms'), 'updated' => now(), 'priority' => '0.5'],
            ['loc' => route('public.business'), 'updated' => now(), 'priority' => '0.7'],
        ]);

        Page::query()
            ->where('visibility', 'public')
            ->latest('updated_at')
            ->limit(27000)
            ->get(['slug', 'updated_at'])
            ->each(fn (Page $page) => $urls->push([
                'loc' => route('public.pages.show', $page),
                'updated' => $page->updated_at,
                'priority' => '0.8',
            ]));

        Group::query()
            ->whereIn('type', ['public', 'approval'])
            ->latest('updated_at')
            ->limit(22000)
            ->get(['slug', 'updated_at'])
            ->each(fn (Group $group) => $urls->push([
                'loc' => route('public.groups.show', $group),
                'updated' => $group->updated_at,
                'priority' => '0.7',
            ]));

        return response()
            ->view('public.sitemap', ['urls' => $urls], 200)
            ->header('Content-Type', 'application/xml');
    }

    public function privacy(): View
    {
        return view('public.legal', [
            'title' => 'Privacy',
            'intro' => 'Sirraty is built around privacy-first social sharing.',
            'items' => [
                'You control profile visibility, post visibility, messaging permissions, and location visibility.',
                'Protected community content requires sign in before full access.',
                'Reports and moderation tools help keep the platform respectful and safe.',
                'Account emails are used for verification, recovery, security notices, and selected platform updates.',
            ],
        ]);
    }

    public function terms(): View
    {
        return view('public.legal', [
            'title' => 'Terms',
            'intro' => 'Use Sirraty respectfully and only for lawful, safe, and honest activity.',
            'items' => [
                'Do not post harmful, abusive, deceptive, private, or unlawful content.',
                'Pages, groups, market listings, posts, comments, and messages may be moderated.',
                'Marketplace users are responsible for accurate listings and local transaction safety.',
                'Accounts may be limited, suspended, or removed when platform rules are violated.',
            ],
        ]);
    }

    public function business(Request $request): View
    {
        $businessCategories = Category::where('scope', 'pages')
            ->where(function ($query): void {
                $query->where('name', 'like', '%business%')
                    ->orWhere('name', 'like', '%entrepreneur%')
                    ->orWhere('name', 'like', '%marketing%')
                    ->orWhere('name', 'like', '%finance%');
            })
            ->pluck('id');

        $pages = Page::with(['owner.profile', 'category', 'city'])
            ->withCount('followers')
            ->where('visibility', 'public')
            ->when($businessCategories->isNotEmpty(), fn ($query) => $query->whereIn('category_id', $businessCategories))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('public.pages', [
            'title' => 'Business Pages',
            'intro' => 'Public Sirraty pages for business, finance, entrepreneurship, and professional growth.',
            'pages' => $pages,
        ]);
    }

    public function showPage(Page $page): View
    {
        abort_unless($page->visibility === 'public', 404);

        $page->load(['owner.profile', 'category', 'country', 'state', 'city'])->loadCount('followers');

        return view('public.seo-profile', [
            'type' => 'page',
            'record' => $page,
            'title' => $page->name,
            'description' => $this->seoDescription($page->description, $page->name, 'Sirraty page'),
            'canonical' => route('public.pages.show', $page),
            'image' => $page->avatar_url ?: $page->cover_url,
            'category' => $page->category?->name ?? 'Page',
            'location' => $this->locationText($page),
            'countLabel' => 'followers',
            'count' => $page->followers_count,
            'schema' => $this->schemaFor('page', $page, route('public.pages.show', $page)),
        ]);
    }

    public function showGroup(Group $group): View
    {
        abort_unless(in_array($group->type, ['public', 'approval'], true), 404);

        $group->load(['owner.profile', 'category', 'country', 'state', 'city'])->loadCount('members');

        return view('public.seo-profile', [
            'type' => 'group',
            'record' => $group,
            'title' => $group->name,
            'description' => $this->seoDescription($group->description, $group->name, 'Sirraty group'),
            'canonical' => route('public.groups.show', $group),
            'image' => $group->avatar_url ?: $group->cover_url,
            'category' => $group->category?->name ?? 'Group',
            'location' => $this->locationText($group),
            'countLabel' => 'members',
            'count' => $group->members_count,
            'schema' => $this->schemaFor('group', $group, route('public.groups.show', $group)),
        ]);
    }

    private function seoDescription(?string $description, string $name, string $fallback): string
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $description)));
        $text = preg_replace('/^Category:\s*[^.]+\.?\s*Bio:\s*/i', '', $text) ?? $text;
        $text = preg_replace('/\s*Source:\s*https?:\/\/\S+/i', '', $text) ?? $text;
        $text = trim(str_ireplace('No official claim and verification.', '', $text));

        return Str::limit($text !== '' ? $text : "{$name} {$fallback} on Sirraty.", 173, '');
    }

    private function locationText(Page|Group $record): ?string
    {
        return collect([
            $record->city?->name ?? $record->address_city,
            $record->state?->name ?? $record->address_region,
            $record->country?->name ?? $this->countryName($record->address_country),
        ])->filter()->unique()->implode(', ') ?: null;
    }

    private function countryName(?string $code): ?string
    {
        if (! $code) {
            return null;
        }

        return \Locale::getDisplayRegion('-'.$code, 'en') ?: $code;
    }

    private function schemaFor(string $type, Page|Group $record, string $url): array
    {
        $location = $this->locationText($record);
        $description = $this->seoDescription($record->description, $record->name, "Sirraty {$type}");

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => $type === 'page' ? 'ProfilePage' : 'CollectionPage',
            'name' => $record->name,
            'description' => $description,
            'url' => $url,
            'image' => $record->avatar_url ?: $record->cover_url,
            'inLanguage' => 'en',
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => 'Sirraty',
                'url' => route('home'),
            ],
            'about' => array_filter([
                '@type' => 'Organization',
                'name' => $record->name,
                'description' => $description,
                'image' => $record->avatar_url ?: null,
                'category' => $record->category?->name,
                'areaServed' => $location,
                'address' => $location ? array_filter([
                    '@type' => 'PostalAddress',
                    'addressLocality' => $record->city?->name ?? $record->address_city,
                    'addressRegion' => $record->state?->name ?? $record->address_region,
                    'addressCountry' => $record->country?->code ?? $record->address_country,
                ]) : null,
            ]),
        ]);
    }
}
