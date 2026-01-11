<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageFacility;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            // Public Facilities
            ['category' => 'public_facilities', 'name' => 'Parking area', 'is_visible' => true, 'order' => 0],
            ['category' => 'public_facilities', 'name' => 'Garden', 'is_visible' => true, 'order' => 1],
            
            // Connectivity
            ['category' => 'connectivity', 'name' => 'WiFi in public areas', 'is_visible' => true, 'order' => 0],
            ['category' => 'connectivity', 'name' => 'In-room internet', 'is_visible' => true, 'order' => 1],
            
            // Other Activities
            ['category' => 'other_activities', 'name' => 'Other Activities', 'is_visible' => true, 'order' => 0],
            
            // Transportation
            ['category' => 'transportation', 'name' => 'Bicycle rental', 'is_visible' => true, 'order' => 0],
        ];

        foreach ($facilities as $facility) {
            HomepageFacility::firstOrCreate(
                ['name' => $facility['name']],
                $facility
            );
        }
    }
}
