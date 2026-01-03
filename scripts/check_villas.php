<?php
$path = __DIR__ . '/../database/database.sqlite';
if (! file_exists($path)) {
    echo "database.sqlite not found at: $path\n";
    exit(1);
}
try {
    $db = new PDO('sqlite:' . $path);
    $stmt = $db->query('SELECT COUNT(*) FROM villas');
    $count = $stmt ? $stmt->fetchColumn() : 0;
    echo "villas: " . $count . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(2);
}
