<?php

use Illuminate\Support\Collection;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$villas = collect([
    (object) [
        'id' => 1,
        'name' => 'Villa Kota Bunga Ade',
        'capacity' => 6,
        'base_price' => 5104000,
        'image_url' => '/uploads/villas/img_1768275870_6965bf9e6e3f6.png',
    ],
    (object) [
        'id' => 2,
        'name' => 'Villa Puncak Harmony',
        'capacity' => 8,
        'base_price' => 7200000,
        'image_url' => '/uploads/villas/img_1768276455_6965c1e79caec.png',
    ],
]);

$sliderImages = [
    '/uploads/homepage/slider_1768270880_6965ac205c78c.png',
    '/uploads/homepage/slider_1768270881_6965ac215a1a3.png',
    '/uploads/homepage/slider_1768270882_6965ac227b526.png',
];

$facilities = collect([
    (object) ['name' => 'Swimming Pool', 'category' => 'recreation'],
    (object) ['name' => 'Free Wi-Fi', 'category' => 'comfort'],
    (object) ['name' => 'Parking Area', 'category' => 'service'],
]);

$html = view('guest.homepage', [
    'villas' => $villas,
    'sliderImages' => $sliderImages,
    'description' => 'Nikmati pengalaman menginap yang nyaman dan menyenangkan di Ade Villa.',
    'facilities' => $facilities,
])->render();

$html = str_replace([
    'http://localhost/logo.png',
    'http://localhost/favicon.png',
    'http://localhost/uploads/',
    'http://localhost/storage/',
], [
    'assets/logo.png',
    'assets/favicon.png',
    'assets/uploads/',
    'assets/storage/',
], $html);

$html = str_replace('href="/"', 'href="./"', $html);
$html = str_replace('http://localhost/', '', $html);
$html = str_replace("            searchVillas();\n        });", "            // Keep the server-rendered villa list in the static build.\n        });", $html);
$html = preg_replace('~(src|href)="/(?!/)([^"#]+)"~', '$1="$2"', $html);
$html = preg_replace("#url\(['\"]?/(?!/)([^)'\"]+)['\"]?\)#", 'url($1)', $html);

if ($html === null) {
    throw new RuntimeException('Failed to normalize rendered asset paths.');
}

echo $html;
