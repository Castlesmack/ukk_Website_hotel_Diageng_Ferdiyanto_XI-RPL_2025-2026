<?php
/**
 * Debug script to test image upload functionality
 */

// Test 1: Check directory structure
echo "=== Testing Villa Upload Directory ===\n";
$uploadDir = __DIR__ . '/public/uploads/villas';
echo "Upload directory: $uploadDir\n";
echo "Exists: " . (is_dir($uploadDir) ? 'YES' : 'NO') . "\n";
echo "Writable: " . (is_writable($uploadDir) ? 'YES' : 'NO') . "\n";

// Test 2: Check file permissions
echo "\n=== Directory Permissions ===\n";
if (is_dir($uploadDir)) {
    $perms = fileperms($uploadDir);
    echo "Permissions: " . substr(sprintf('%o', $perms), -4) . "\n";
    echo "Owner: " . (function_exists('posix_getpwuid') ? posix_getpwuid(fileowner($uploadDir))['name'] : fileowner($uploadDir)) . "\n";
}

// Test 3: Try to create test file
echo "\n=== Testing File Creation ===\n";
if (is_writable($uploadDir)) {
    $testFile = $uploadDir . '/test_' . time() . '.txt';
    if (file_put_contents($testFile, 'test content')) {
        echo "✓ Can create test file\n";
        unlink($testFile);
    } else {
        echo "✗ Cannot create test file\n";
    }
}

// Test 4: Check Laravel environment
echo "\n=== Laravel Configuration ===\n";
require __DIR__ . '/bootstrap/app.php';
$app = require __DIR__ . '/bootstrap/app.php';

// Test 5: Database connection
echo "\n=== Database Connection ===\n";
try {
    $pdo = \DB::connection()->getPdo();
    echo "✓ Database connected\n";
    
    // Check villas table
    $count = \DB::table('villas')->count();
    echo "Villas in database: $count\n";
} catch (\Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== Upload Test Complete ===\n";
