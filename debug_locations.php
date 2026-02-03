<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$output = "";

// Check for any banners with "Financial" in the location (case-insensitive)
$output .= "Searching for banners with 'Financial' in location:\n";
$financialBanners = \App\Models\BannerActive::where('location', 'like', '%financial%')->get();
foreach ($financialBanners as $banner) {
    $output .= "  - ID: {$banner->id}, Post ID: {$banner->post_id}, Location: '{$banner->location}', Lang: {$banner->language}\n";
}

if ($financialBanners->isEmpty()) {
    $output .= "  No banners found with 'financial' in location.\n";
}

// Check Journey-Growth banner group
$output .= "\n\nChecking 'Journey Growth ID' banner group:\n";
$bannerGroup = \App\Models\BannerGroup::where('title', 'like', '%journey%')->first();
if ($bannerGroup) {
    $output .= "  Banner Group ID: {$bannerGroup->id}, Title: {$bannerGroup->title}\n";

    $activeRecords = \App\Models\BannerActive::where('banner_group_id', $bannerGroup->id)->get();
    $output .= "  Active records for this group:\n";
    foreach ($activeRecords as $record) {
        $post = \App\Domains\Post\Models\Post::find($record->post_id);
        $postTitle = $post ? $post->title : 'Unknown';
        $output .= "    - ID: {$record->id}, Post: {$postTitle} (ID: {$record->post_id}), Location: '{$record->location}', Lang: {$record->language}\n";
    }
}

file_put_contents(__DIR__ . '/debug_output.txt', $output);
echo "Output written to debug_output.txt\n";
