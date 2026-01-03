<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db = 'booking_villa';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    echo "Connect failed: " . $mysqli->connect_error . "\n";
    exit(1);
}

$mysqli->query("SET FOREIGN_KEY_CHECKS = 0");

$tables = ['bookings', 'feedbacks', 'invoices', 'payments', 'room_type_availabilities', 'users', 'villa_images', 'villa_room_types', 'villas'];

foreach ($tables as $table) {
    $mysqli->query("DROP TABLE IF EXISTS `$table`");
    if ($mysqli->error) {
        echo "Error dropping $table: " . $mysqli->error . "\n";
    }
}

$mysqli->query("SET FOREIGN_KEY_CHECKS = 1");

echo "Dropped existing tables.\n";
?>