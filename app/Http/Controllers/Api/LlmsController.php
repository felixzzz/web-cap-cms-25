<?php

namespace App\Http\Controllers\Api;

use App\Domains\Post\Models\Post;
use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LlmsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $option = new Option();
        $llmsTxt = $option->getOption('app.llms_txt');
        $llmsFullTxt = $option->getOption('app.llms_full_txt');

        if (empty(trim((string) $llmsTxt)) || empty(trim((string) $llmsFullTxt))) {
            $defaults = self::generateDefaultContent();
            if (empty(trim((string) $llmsTxt))) {
                $llmsTxt = $defaults['llms_txt'];
            }
            if (empty(trim((string) $llmsFullTxt))) {
                $llmsFullTxt = $defaults['llms_full_txt'];
            }
        }

        return response()->json([
            'message' => 'Data Successfully Fetched',
            'data' => [
                'llms_txt' => $llmsTxt,
                'llms_full_txt' => $llmsFullTxt,
            ],
        ], 200);
    }

    /**
     * Generate default llms.txt and llms-full.txt content from CMS settings and published posts.
     *
     * @return array{llms_txt: string, llms_full_txt: string}
     */
    public static function generateDefaultContent(): array
    {
        $option = new Option();
        $siteTitle = trim((string) ($option->getOption('app.name') ?: 'Chandra Asri Group'));
        $tagline = trim((string) ($option->getOption('app.tagline') ?: 'Your Growth Partner'));
        $metaDescription = trim((string) (
            $option->getOption('app.meta_description')
            ?: 'Chandra Asri Group is Indonesia\'s leading energy, chemical, and infrastructure solutions company, supplying products and services to various manufacturing industries in both domestic and international markets.'
        ));
        $siteUrl = rtrim((string) env('FRONTEND_URL', 'https://chandra-asri.com'), '/');

        $pages = collect();
        $products = collect();
        $newsList = collect();
        $blogList = collect();
        $sustainabilityList = collect();

        try {
            $pages = Post::with('meta')
                ->where('type', Post::TYPE_PAGE)
                ->where('status', Post::STATUS_PUBLISH)
                ->orderBy('sort')
                ->orderBy('id')
                ->get();

            $products = Post::with('meta')
                ->where('type', 'products')
                ->where('status', Post::STATUS_PUBLISH)
                ->orderBy('id')
                ->get();

            $newsList = Post::with('meta')
                ->where('type', 'news')
                ->where('status', Post::STATUS_PUBLISH)
                ->orderByDesc('published_at')
                ->limit(25)
                ->get();

            $blogList = Post::with('meta')
                ->where('type', 'blog')
                ->where('status', Post::STATUS_PUBLISH)
                ->orderByDesc('published_at')
                ->limit(25)
                ->get();

            $sustainabilityList = Post::with('meta')
                ->where('type', 'articles-sustainability')
                ->where('status', Post::STATUS_PUBLISH)
                ->orderByDesc('published_at')
                ->limit(20)
                ->get();
        } catch (\Throwable $e) {
            // Fallback to static structure if DB query fails
        }

        $llmsTxt = self::buildLlmsTxt(
            $siteTitle,
            $tagline,
            $metaDescription,
            $siteUrl,
            $pages,
            $products,
            $newsList,
            $blogList,
            $sustainabilityList
        );

        $llmsFullTxt = self::buildLlmsFullTxt(
            $siteTitle,
            $tagline,
            $metaDescription,
            $siteUrl,
            $pages,
            $products,
            $newsList,
            $blogList,
            $sustainabilityList
        );

        return [
            'llms_txt' => $llmsTxt,
            'llms_full_txt' => $llmsFullTxt,
        ];
    }

    private static function buildLlmsTxt(
        string $siteTitle,
        string $tagline,
        string $metaDescription,
        string $siteUrl,
        $pages,
        $products,
        $newsList,
        $blogList,
        $sustainabilityList
    ): string {
        $lines = [];
        $lines[] = "# {$siteTitle}";
        $lines[] = '';
        $lines[] = "> {$metaDescription}";
        $lines[] = '';
        if ($tagline !== '') {
            $lines[] = "Tagline: {$tagline}";
            $lines[] = '';
        }

        $lines[] = '## Full Context';
        $lines[] = '';
        $lines[] = "- [Full LLM Documentation (llms-full.txt)]({$siteUrl}/llms-full.txt): Complete reference of {$siteTitle} corporate profile, business solutions, chemical products, sustainability programs, investor relations, and latest news.";
        $lines[] = "- [XML Sitemap]({$siteUrl}/sitemap.xml): Complete index of public URLs in English (`/en`) and Indonesian (`/id`).";
        $lines[] = '';

        $lines[] = '## Core Corporate Sections';
        $lines[] = '';
        $coreSections = [
            ['/en', 'Home', 'Main corporate portal and overview of Chandra Asri Group.'],
            ['/en/about/who-we-are', 'About Us - Who We Are', 'Company history, vision, mission, corporate values, and milestones.'],
            ['/en/about/management-and-structure', 'Management & Corporate Structure', 'Board of Commissioners, Board of Directors, and organizational structure.'],
            ['/en/about/awards-and-recognition', 'Awards & Recognition', 'Corporate achievements, certifications, and industry awards.'],
            ['/en/our-business', 'Our Business', 'Overview of Chemicals, Energy, Infrastructure, and Liner business solutions.'],
            ['/en/our-business/chemical-solutions', 'Chemical Solutions', 'Olefins, Polyolefins, Styrene Monomer, Butadiene, MTBE, Butene-1, and Chlor-Alkali products.'],
            ['/en/sustainability', 'Sustainability', 'ESG strategy, circular economy initiatives, environment, social responsibility, and sustainability governance.'],
            ['/en/sustainability/environment', 'Sustainability - Environment', 'Decarbonization, energy efficiency, water management, and circular economy programs.'],
            ['/en/sustainability/social', 'Sustainability - Social', 'Community development, health, safety, and human capital initiatives.'],
            ['/en/sustainability/governance', 'Sustainability - Governance', 'ESG governance framework and ethical compliance.'],
            ['/en/sustainability/reports-and-publications', 'Sustainability Reports & Publications', 'Annual sustainability reports and ESG disclosures.'],
            ['/en/sustainability/sustainability-in-action', 'Sustainability in Action', 'Case studies and articles on environmental and community programs.'],
            ['/en/investor', 'Investor Relations', 'Financial highlights, shareholder information, and investor updates.'],
            ['/en/investor/reports', 'Investor Reports', 'Annual reports, financial statements, and corporate presentations.'],
            ['/en/investor/publication', 'Investor Publications', 'Prospectuses, RUPS/GMS announcements, and public disclosures.'],
            ['/en/investor/stocks-and-bonds', 'Stocks & Bonds', 'TPIA stock information, bond issuances, and credit ratings.'],
            ['/en/governance', 'Corporate Governance', 'Good Corporate Governance (GCG) policies, charter, and whistleblowing system.'],
            ['/en/news', 'News & Publications', 'Official press releases, corporate updates, and industry insights.'],
            ['/en/caliber', 'Caliber', 'Chandra Asri Group talent and career development program.'],
            ['/en/contact-us', 'Contact Us', 'Head office, plant locations, and business inquiry channels.'],
        ];

        foreach ($coreSections as [$path, $title, $desc]) {
            $lines[] = "- [{$title}]({$siteUrl}{$path}): {$desc}";
        }
        $lines[] = '';

        $dynamicPages = $pages->filter(fn($page) => $page->pages_dynamic === 'yes' || !empty($page->site_url));
        if ($dynamicPages->isNotEmpty()) {
            $lines[] = '## Business Solutions & Corporate Pages';
            $lines[] = '';
            foreach ($dynamicPages as $page) {
                $title = trim((string) ($page->title_en ?: $page->title));
                if ($title === '') {
                    continue;
                }
                $url = self::resolvePageUrl($siteUrl, $page, 'en');
                $desc = self::cleanText($page->meta_description ?: $page->excerpt ?: '');
                $lines[] = $desc !== ''
                    ? "- [{$title}]({$url}): {$desc}"
                    : "- [{$title}]({$url})";
            }
            $lines[] = '';
        }

        if ($products->isNotEmpty()) {
            $lines[] = '## Chemical Solutions & Products';
            $lines[] = '';
            foreach ($products as $product) {
                $title = trim((string) ($product->title_en ?: $product->title));
                $slug = $product->slug_en ?: $product->slug;
                $url = "{$siteUrl}/en/our-business/chemical-solutions/{$slug}";
                $desc = self::extractMetaSummary($product);
                $lines[] = $desc !== ''
                    ? "- [{$title}]({$url}): {$desc}"
                    : "- [{$title}]({$url})";
            }
            $lines[] = '';
        }

        if ($sustainabilityList->isNotEmpty()) {
            $lines[] = '## Sustainability in Action';
            $lines[] = '';
            foreach ($sustainabilityList->take(10) as $article) {
                $title = trim((string) ($article->title_en ?: $article->title));
                $slug = $article->slug_en ?: $article->slug;
                $url = "{$siteUrl}/en/sustainability/sustainability-in-action/{$slug}";
                $lines[] = "- [{$title}]({$url})";
            }
            $lines[] = '';
        }

        if ($newsList->isNotEmpty() || $blogList->isNotEmpty()) {
            $lines[] = '## Latest News & Insights';
            $lines[] = '';
            foreach ($newsList->take(10) as $news) {
                $title = trim((string) ($news->title_en ?: $news->title));
                $slug = $news->slug_en ?: $news->slug;
                $url = "{$siteUrl}/en/news/{$slug}";
                $lines[] = "- [{$title}]({$url})";
            }
            foreach ($blogList->take(10) as $blog) {
                $title = trim((string) ($blog->title_en ?: $blog->title));
                $slug = $blog->slug_en ?: $blog->slug;
                $url = "{$siteUrl}/en/blog/{$slug}";
                $lines[] = "- [{$title}]({$url})";
            }
            $lines[] = '';
        }

        $lines[] = '## Languages';
        $lines[] = '';
        $lines[] = "- [English Version]({$siteUrl}/en): Primary English corporate website.";
        $lines[] = "- [Indonesian Version]({$siteUrl}/id): Bahasa Indonesia corporate website.";

        return implode("\n", $lines) . "\n";
    }

    private static function buildLlmsFullTxt(
        string $siteTitle,
        string $tagline,
        string $metaDescription,
        string $siteUrl,
        $pages,
        $products,
        $newsList,
        $blogList,
        $sustainabilityList
    ): string {
        $lines = [];
        $lines[] = "# {$siteTitle} — Full Context Documentation (llms-full.txt)";
        $lines[] = '';
        $lines[] = "> {$metaDescription}";
        $lines[] = '';
        $lines[] = "## Organization Profile";
        $lines[] = '';
        $lines[] = "- **Organization**: {$siteTitle} (PT Chandra Asri Pacific Tbk / IDX: TPIA)";
        $lines[] = "- **Tagline**: {$tagline}";
        $lines[] = "- **Website**: {$siteUrl}";
        $lines[] = "- **Languages**: English (`{$siteUrl}/en`) and Bahasa Indonesia (`{$siteUrl}/id`)";
        $lines[] = "- **Summary Index**: {$siteUrl}/llms.txt";
        $lines[] = '';

        if ($pages->isNotEmpty()) {
            $lines[] = '---';
            $lines[] = '';
            $lines[] = '## 1. Corporate Pages & Business Solutions';
            $lines[] = '';

            foreach ($pages as $page) {
                $titleEn = trim((string) ($page->title_en ?: $page->title));
                $titleId = trim((string) ($page->title ?: $page->title_en));
                if ($titleEn === '') {
                    continue;
                }

                $urlEn = self::resolvePageUrl($siteUrl, $page, 'en');
                $urlId = self::resolvePageUrl($siteUrl, $page, 'id');
                $lines[] = "### {$titleEn}" . ($titleId !== '' && $titleId !== $titleEn ? " ({$titleId})" : '');
                $lines[] = "- **URL (EN)**: {$urlEn}";
                $lines[] = "- **URL (ID)**: {$urlId}";

                if (!empty($page->meta_description)) {
                    $lines[] = '- **Description**: ' . self::cleanText($page->meta_description);
                }

                $metaText = self::extractFullMetaText($page);
                if ($metaText !== '') {
                    $lines[] = '';
                    $lines[] = $metaText;
                }
                $lines[] = '';
            }
        }

        if ($products->isNotEmpty()) {
            $lines[] = '---';
            $lines[] = '';
            $lines[] = '## 2. Chemical Solutions & Products';
            $lines[] = '';

            foreach ($products as $product) {
                $title = trim((string) ($product->title_en ?: $product->title));
                $slug = $product->slug_en ?: $product->slug;
                $urlEn = "{$siteUrl}/en/our-business/chemical-solutions/{$slug}";
                $lines[] = "### {$title}";
                $lines[] = "- **URL**: {$urlEn}";

                $metaText = self::extractFullMetaText($product);
                if ($metaText !== '') {
                    $lines[] = '';
                    $lines[] = $metaText;
                }
                $lines[] = '';
            }
        }

        if ($sustainabilityList->isNotEmpty()) {
            $lines[] = '---';
            $lines[] = '';
            $lines[] = '## 3. Sustainability in Action';
            $lines[] = '';

            foreach ($sustainabilityList as $article) {
                $title = trim((string) ($article->title_en ?: $article->title));
                $slug = $article->slug_en ?: $article->slug;
                $url = "{$siteUrl}/en/sustainability/sustainability-in-action/{$slug}";
                $publishedDate = $article->published_at ? substr((string) $article->published_at, 0, 10) : '';

                $lines[] = "### {$title}";
                $lines[] = "- **URL**: {$url}";
                if ($publishedDate !== '') {
                    $lines[] = "- **Published**: {$publishedDate}";
                }

                $body = self::extractArticleBody($article);
                if ($body !== '') {
                    $lines[] = '';
                    $lines[] = $body;
                }
                $lines[] = '';
            }
        }

        if ($newsList->isNotEmpty()) {
            $lines[] = '---';
            $lines[] = '';
            $lines[] = '## 4. Official News & Press Releases';
            $lines[] = '';

            foreach ($newsList as $news) {
                $title = trim((string) ($news->title_en ?: $news->title));
                $slug = $news->slug_en ?: $news->slug;
                $url = "{$siteUrl}/en/news/{$slug}";
                $publishedDate = $news->published_at ? substr((string) $news->published_at, 0, 10) : '';

                $lines[] = "### {$title}";
                $lines[] = "- **URL**: {$url}";
                if ($publishedDate !== '') {
                    $lines[] = "- **Published**: {$publishedDate}";
                }

                $body = self::extractArticleBody($news);
                if ($body !== '') {
                    $lines[] = '';
                    $lines[] = $body;
                }
                $lines[] = '';
            }
        }

        if ($blogList->isNotEmpty()) {
            $lines[] = '---';
            $lines[] = '';
            $lines[] = '## 5. Blog & Industry Insights';
            $lines[] = '';

            foreach ($blogList as $blog) {
                $title = trim((string) ($blog->title_en ?: $blog->title));
                $slug = $blog->slug_en ?: $blog->slug;
                $url = "{$siteUrl}/en/blog/{$slug}";
                $publishedDate = $blog->published_at ? substr((string) $blog->published_at, 0, 10) : '';

                $lines[] = "### {$title}";
                $lines[] = "- **URL**: {$url}";
                if ($publishedDate !== '') {
                    $lines[] = "- **Published**: {$publishedDate}";
                }

                $body = self::extractArticleBody($blog);
                if ($body !== '') {
                    $lines[] = '';
                    $lines[] = $body;
                }
                $lines[] = '';
            }
        }

        return implode("\n", $lines) . "\n";
    }

    private static function resolvePageUrl(string $siteUrl, Post $page, string $locale = 'en'): string
    {
        if (!empty($page->site_url)) {
            $cleanPath = '/' . ltrim((string) $page->site_url, '/');
            return $cleanPath === '/' ? "{$siteUrl}/{$locale}" : "{$siteUrl}/{$locale}{$cleanPath}";
        }

        $slug = $locale === 'en' ? ($page->slug_en ?: $page->slug) : $page->slug;
        if ($page->pages_dynamic === 'yes') {
            return "{$siteUrl}/{$locale}/our-business/{$slug}";
        }

        return "{$siteUrl}/{$locale}/{$slug}";
    }

    private static function extractMetaSummary(Post $post): string
    {
        if (!empty($post->meta_description)) {
            return self::cleanText($post->meta_description, 220);
        }
        if (!empty($post->excerpt)) {
            return self::cleanText($post->excerpt, 220);
        }
        if ($post->relationLoaded('meta') && $post->meta) {
            foreach (['description_en', 'content_en', 'description_id', 'content_id'] as $key) {
                $found = $post->meta->firstWhere('key', $key);
                if ($found && !empty($found->value) && !self::isJsonString($found->value)) {
                    $cleaned = self::cleanText($found->value, 220);
                    if ($cleaned !== '') {
                        return $cleaned;
                    }
                }
            }
        }
        return '';
    }

    private static function extractFullMetaText(Post $post): string
    {
        $snippets = [];
        if (!empty($post->excerpt)) {
            $snippets[] = self::cleanText($post->excerpt, 600);
        }
        if (!empty($post->content)) {
            $snippets[] = self::cleanText($post->content, 1000);
        }

        if ($post->relationLoaded('meta') && $post->meta) {
            $allowedKeys = [
                'title_en',
                'subtitle_en',
                'description_en',
                'content_en',
                'overview_en',
                'summary_en',
                'title_id',
                'description_id',
                'content_id',
            ];

            foreach ($post->meta as $metaItem) {
                if (!in_array($metaItem->key, $allowedKeys, true) || empty($metaItem->value)) {
                    continue;
                }
                if (self::isJsonString($metaItem->value)) {
                    continue;
                }
                $text = self::cleanText((string) $metaItem->value, 800);
                if ($text !== '' && !in_array($text, $snippets, true)) {
                    $snippets[] = $text;
                }
                if (count($snippets) >= 5) {
                    break;
                }
            }
        }

        return implode("\n\n", array_filter($snippets));
    }

    private static function extractArticleBody(Post $post): string
    {
        if ($post->relationLoaded('meta') && $post->meta) {
            $contentEn = $post->meta->firstWhere('key', 'content_en');
            if ($contentEn && !empty($contentEn->value) && !self::isJsonString($contentEn->value)) {
                $cleaned = self::cleanText((string) $contentEn->value, 1200);
                if ($cleaned !== '') {
                    return $cleaned;
                }
            }

            $contentId = $post->meta->firstWhere('key', 'content_id');
            if ($contentId && !empty($contentId->value) && !self::isJsonString($contentId->value)) {
                $cleaned = self::cleanText((string) $contentId->value, 1200);
                if ($cleaned !== '') {
                    return $cleaned;
                }
            }
        }

        if (!empty($post->content)) {
            return self::cleanText((string) $post->content, 1200);
        }

        if (!empty($post->excerpt)) {
            return self::cleanText((string) $post->excerpt, 600);
        }

        return '';
    }

    private static function cleanText(?string $raw, int $maxLength = 500): string
    {
        if (empty($raw)) {
            return '';
        }

        $decoded = html_entity_decode(strip_tags($raw), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $normalized = trim((string) preg_replace('/\s+/u', ' ', $decoded));

        if ($maxLength > 0 && mb_strlen($normalized) > $maxLength) {
            return rtrim(mb_substr($normalized, 0, $maxLength)) . '...';
        }

        return $normalized;
    }

    private static function isJsonString($value): bool
    {
        if (!is_string($value) || $value === '') {
            return false;
        }
        $firstChar = $value[0];
        if ($firstChar !== '{' && $firstChar !== '[') {
            return false;
        }
        json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
