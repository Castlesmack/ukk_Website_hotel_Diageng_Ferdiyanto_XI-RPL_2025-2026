<?php
// Simple CLI importer for booking_villa.sql into MySQL using mysqli->multi_query
// Usage: php import_mysql.php --host=127.0.0.1 --user=root --pass=secret --db=booking_db

$options = getopt('', ['host:', 'user:', 'pass::', 'db:']);
if (!isset($options['host']) || !isset($options['user']) || !isset($options['db'])) {
    echo "Usage: php import_mysql.php --host=HOST --user=USER --pass=PASS --db=DATABASE\n";
    exit(1);
}
$host = $options['host'];
$user = $options['user'];
$pass = $options['pass'] ?? '';
$db = $options['db'];

$sqlFile = __DIR__ . '/../booking_villa.sql';
if (!file_exists($sqlFile)) {
    echo "SQL file not found: $sqlFile\n";
    exit(2);
}

$sql = file_get_contents($sqlFile);
if ($sql === false) {
    echo "Failed to read SQL file\n";
    exit(3);
}

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    echo "Connect failed: " . $mysqli->connect_error . "\n";
    exit(4);
}

// Split and run the SQL in chunks to avoid extremely long single multi_query payloads
$statements = preg_split('/;\s*\n/', $sql);
foreach ($statements as $stmt) {
    $s = trim($stmt);
    if ($s === '') continue;
    if ($mysqli->query($s) === false) {
        echo "Error executing statement: " . $mysqli->error . "\n";
        // continue on errors but report
    }
}

echo "Import finished. Check the database for imported tables and data.\n";
