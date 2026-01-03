<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db = 'booking_villa';

$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_errno) {
    echo "Connect failed: " . $mysqli->connect_error . "\n";
    exit(1);
}

if ($mysqli->query("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci") === false) {
    echo "Error creating database: " . $mysqli->error . "\n";
    exit(2);
}

echo "Database '$db' created or already exists.\n";
?>