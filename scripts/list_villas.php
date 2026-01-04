<?php
$path = __DIR__ . '/../database/database.sqlite';
try {
    $db = new PDO('sqlite:' . $path);
    $stmt = $db->query('SELECT id, name, capacity, base_price, rooms_total, status FROM villas');
    $villas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "=== Available Villas ===\n";
    foreach ($villas as $villa) {
        echo "ID: {$villa['id']}, Name: {$villa['name']}, Capacity: {$villa['capacity']}, Price: {$villa['base_price']}, Rooms: {$villa['rooms_total']}, Status: {$villa['status']}\n";
    }
    echo "\nTotal: " . count($villas) . " villas\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
