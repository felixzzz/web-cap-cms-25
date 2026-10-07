<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RobotsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $option = new Option();
        $robotsTxt = $option->getOption('app.robots_txt');

        if (empty(trim((string) $robotsTxt))) {
            $robotsTxt = self::generateDefaultContent();
        }

        return response()->json([
            'message' => 'Data Successfully Fetched',
            'data' => [
                'robots_txt' => $robotsTxt,
            ],
        ], 200);
    }

    public static function generateDefaultContent(): string
    {
        $siteUrl = rtrim((string) env('FRONTEND_URL', 'https://chandra-asri.com'), '/');

        return <<<TXT
User-agent: Googlebot
Disallow: /
User-agent: Googlebot-Image
Disallow: /
User-agent: Bingbot
Disallow: /
User-agent: Yandex
Disallow: /
User-agent: Baiduspider
Disallow: /
User-agent: Yeti
Disallow: /
User-agent: Applebot
Disallow: /
User-agent: DuckDuckBot
Disallow: /
User-agent: Slurp
Disallow: /
User-agent: GPTBot
Disallow: /
User-agent: OAI-SearchBot
Disallow: /
User-agent: ChatGPT-User
Disallow: /
User-agent: Google-Extended
Disallow: /
User-agent: ClaudeBot
Disallow: /
User-agent: anthropic-ai
Disallow: /
User-agent: PerplexityBot
Disallow: /
User-agent: Perplexity-User
Disallow: /
User-agent: CCBot
Disallow: /
User-agent: Bytespider
Disallow: /
User-agent: Amazonbot
Disallow: /
User-agent: Applebot-Extended
Disallow: /
User-agent: Meta-ExternalAgent
Disallow: /
User-agent: cohere-ai
Disallow: /
User-agent: AhrefsBot
Disallow: /
User-agent: SemrushBot
Disallow: /
User-agent: MJ12bot
Disallow: /
User-agent: DotBot
Disallow: /
User-agent: BLEXBot
Disallow: /
User-agent: DataForSeoBot
Disallow: /
User-agent: archive.org_bot
Disallow: /
User-agent: *
Disallow: /

Sitemap: {$siteUrl}/sitemap.xml
TXT;
    }
}
