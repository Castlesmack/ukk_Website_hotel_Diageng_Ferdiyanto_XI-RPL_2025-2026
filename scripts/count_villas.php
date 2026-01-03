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

$result = $mysqli->query("SELECT COUNT(*) as count FROM villas");
if ($result) {
    $row = $result->fetch_assoc();
    echo "Villas count: " . $row['count'] . "\n";
} else {
    echo "Error: " . $mysqli->error . "\n";
}
?>