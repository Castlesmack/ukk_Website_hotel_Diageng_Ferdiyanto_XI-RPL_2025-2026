#!/usr/bin/env php
<?php

/**
 * Test Script: Verify Villa Availability Checking
 * 
 * Tests the date-based villa availability blocking feature.
 * Run this from the project root: php test_availability.php
 */

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use App\Models\Villa;
use Carbon\Carbon;

echo "=== Villa Availability Check Tests ===\n\n";

// Test 1: Check existing bookings for villa 1
echo "Test 1: Existing bookings for Villa 1 (Feb 1-5, 2025)\n";
$villa = Villa::find(1);
if ($villa) {
    $bookings = Booking::where('villa_id', 1)
        ->whereIn('status', ['confirmed', 'pending'])
        ->get();
    echo "Found " . $bookings->count() . " booking(s):\n";
    foreach ($bookings as $b) {
        echo "  - {$b->check_in_date} to {$b->check_out_date} (Status: {$b->status})\n";
    }
} else {
    echo "Villa not found!\n";
}
echo "\n";

// Test 2: Simulate date overlap checks
echo "Test 2: Overlap Detection Logic\n";
$testCases = [
    ['name' => 'Exact overlap', 'checkin' => '2025-02-01', 'checkout' => '2025-02-05'],
    ['name' => 'Partial overlap start', 'checkin' => '2025-01-30', 'checkout' => '2025-02-02'],
    ['name' => 'Partial overlap end', 'checkin' => '2025-02-03', 'checkout' => '2025-02-07'],
    ['name' => 'Complete overlap', 'checkin' => '2025-02-02', 'checkout' => '2025-02-04'],
    ['name' => 'No overlap before', 'checkin' => '2025-01-20', 'checkout' => '2025-01-31'],
    ['name' => 'No overlap after', 'checkin' => '2025-02-06', 'checkout' => '2025-02-10'],
];

$checkin = Carbon::parse('2025-02-01');
$checkout = Carbon::parse('2025-02-05');

foreach ($testCases as $test) {
    $testCheckin = Carbon::parse($test['checkin']);
    $testCheckout = Carbon::parse($test['checkout']);
    
    $conflict = Booking::where('villa_id', 1)
        ->whereIn('status', ['confirmed', 'pending'])
        ->where('check_in_date', '<', $testCheckout)
        ->where('check_out_date', '>', $testCheckin)
        ->exists();
    
    $result = $conflict ? '❌ CONFLICT' : '✓ AVAILABLE';
    echo "  {$test['name']}: {$test['checkin']} to {$test['checkout']} ... {$result}\n";
}
echo "\n";

echo "=== Tests Complete ===\n";
echo "Feature is working correctly if:\n";
echo "  - Exact overlaps are detected\n";
echo "  - Partial overlaps are detected\n";
echo "  - Non-overlapping dates are available\n";
