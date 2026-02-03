<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = \App\Models\BannerActive::where('location', 'financial-report')->update(['location' => 'financial-reports']);

echo "Updated $count records from 'financial-report' to 'financial-reports'\n";
