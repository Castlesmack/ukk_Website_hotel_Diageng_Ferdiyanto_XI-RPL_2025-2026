<?php

use App\Models\HomepageFacility;

// Clean up the facilities - keep only unique ones per category

// Get all facilities
$all = HomepageFacility::all();

// Group by category and name
$grouped = $all->groupBy(function($item) {
    return $item->category . '|' . strtolower(trim($item->name));
});

// Delete duplicates, keep the first one
$deleted = 0;
foreach ($grouped as $key => $items) {
    if ($items->count() > 1) {
        // Skip the first one, delete the rest
        $toDelete = $items->skip(1);
        foreach ($toDelete as $item) {
            $item->delete();
            $deleted++;
        }
    }
}

echo "Deleted $deleted duplicate facilities\n";

// Define clean facilities structure
$facilities = [
    [
        'category' => 'connectivity',
        'name' => 'WiFi in public areas',
        'order' => 1,
    ],
    [
        'category' => 'connectivity',
        'name' => 'In-room internet',
        'order' => 2,
    ],
    [
        'category' => 'other_activities',
        'name' => 'Garden',
        'order' => 1,
    ],
    [
        'category' => 'public_facilities',
        'name' => 'Parking area',
        'order' => 1,
    ],
    [
        'category' => 'transportation',
        'name' => 'Bicycle rental',
        'order' => 1,
    ],
];

// Clear and recreate
HomepageFacility::truncate();

foreach ($facilities as $facility) {
    HomepageFacility::create([
        'category' => $facility['category'],
        'name' => $facility['name'],
        'order' => $facility['order'],
        'is_visible' => true,
    ]);
}

echo "Facilities cleaned up and reset to standard list\n";

// Display current facilities
echo "\nCurrent facilities:\n";
HomepageFacility::all()->groupBy('category')->each(function($items, $category) {
    echo "\n$category:\n";
    $items->each(function($item) {
        echo "  - {$item->name} (visible: " . ($item->is_visible ? 'yes' : 'no') . ")\n";
    });
});
