<?php

$projectRoot = 'E:/Code Base/Projeoct/Healling-Reanaissance-Service';
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;

$disk = Storage::disk('spaces');
$hlsFolder = 'hls/bc22cabb-95ec-44be-90f2-f7b2b817038b';

echo "=== CHECKING HLS FOLDER: $hlsFolder ===\n";

$files = $disk->files($hlsFolder);
echo "Files in folder: " . count($files) . "\n";

foreach ($files as $file) {
    echo "Setting $file to public...\n";
    $disk->setVisibility($file, 'public');
}

$testSegmentUrl = "https://storagevideos-new.sfo3.digitaloceanspaces.com/{$hlsFolder}/segment000.ts";

echo "\nTesting HTTP GET for segment000.ts:\n$testSegmentUrl\n";

$ch = curl_init($testSegmentUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode " . ($httpCode == 200 ? "[PASS: NOW WORKING!]" : "[FAIL]") . "\n";

