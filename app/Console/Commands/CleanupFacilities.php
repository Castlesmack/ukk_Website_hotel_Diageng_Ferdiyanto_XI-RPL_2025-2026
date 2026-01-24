<?php

namespace App\Console\Commands;

use App\Models\HomepageFacility;
use Illuminate\Console\Command;

class CleanupFacilities extends Command
{
    protected $signature = 'facilities:cleanup';
    protected $description = 'Clean up and reset facilities database';

    public function handle()
    {
        // Clear all facilities
        HomepageFacility::truncate();
        
        // Add clean facilities
        $facilities = [
            ['category' => 'connectivity', 'name' => 'WiFi in public areas', 'order' => 1],
            ['category' => 'connectivity', 'name' => 'In-room internet', 'order' => 2],
            ['category' => 'other_activities', 'name' => 'Garden', 'order' => 1],
            ['category' => 'public_facilities', 'name' => 'Parking area', 'order' => 1],
            ['category' => 'transportation', 'name' => 'Bicycle rental', 'order' => 1],
        ];

        foreach ($facilities as $f) {
            HomepageFacility::create(array_merge($f, ['is_visible' => true]));
        }

        $this->info('Facilities cleaned up successfully!');
        
        // Display current facilities
        $this->line("\n📋 Current facilities:");
        
        HomepageFacility::all()
            ->groupBy('category')
            ->each(function($items, $category) {
                $this->line("\n<fg=blue>" . str_replace('_', ' ', ucfirst($category)) . "</>");
                $items->each(function($item) {
                    $status = $item->is_visible ? '✓' : '✕';
                    $this->line("  $status {$item->name}");
                });
            });
    }
}
