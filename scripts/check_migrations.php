<?php
$path = __DIR__ . '/../database/database.sqlite';
try {
    $db = new PDO('sqlite:' . $path);
    
    // Check migrations table
    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='migrations'");
    if ($stmt->fetch()) {
        echo "Migrations table exists.\n";
        $stmt = $db->query("SELECT migration FROM migrations ORDER BY batch DESC LIMIT 5");
        echo "Recent migrations:\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - " . $row['migration'] . "\n";
        }
    } else {
        echo "Migrations table does not exist.\n";
    }
    
    // Check bookings table structure
    echo "\nBookings table columns:\n";
    $stmt = $db->query("PRAGMA table_info(bookings)");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  - " . $row['name'] . " (" . $row['type'] . ")\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
