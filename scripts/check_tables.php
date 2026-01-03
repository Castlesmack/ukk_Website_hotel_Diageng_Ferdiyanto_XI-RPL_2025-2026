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

$result = $mysqli->query("SHOW TABLES");
if ($result) {
    echo "Tables in '$db':\n";
    while ($row = $result->fetch_array()) {
        echo "- " . $row[0] . "\n";
    }
} else {
    echo "No tables or error: " . $mysqli->error . "\n";
}
?>