<?php

require 'bootstrap/app.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Villa;

// Get all villas
$villas = Villa::all();
echo "Total villas: " . count($villas) . "\n";
echo str_repeat("-", 80) . "\n";

foreach ($villas as $villa) {
    echo "Villa ID: {$villa->id}\n";
    echo "Name: {$villa->name}\n";
    echo "Thumbnail: {$villa->thumbnail_path}\n";
    echo "Images (raw): " . var_export($villa->images, true) . "\n";
    echo "Images (type): " . gettype($villa->images) . "\n";
    if (is_array($villa->images)) {
        echo "Images count: " . count($villa->images) . "\n";
    }
    echo str_repeat("-", 80) . "\n";
}
