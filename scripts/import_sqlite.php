<?php
// Simple importer: executes the SQLite-compatible SQL file against database/database.sqlite
$base = __DIR__ . DIRECTORY_SEPARATOR . '..';
$dbFile = $base . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'database.sqlite';
$sqlFile = $base . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'schema_ukk_villa_sqlite.sql';

if (!file_exists($sqlFile)) {
    echo "SQL file not found: $sqlFile\n";
    exit(1);
}

// Ensure database directory exists
if (!is_dir($base . DIRECTORY_SEPARATOR . 'database')) {
    mkdir($base . DIRECTORY_SEPARATOR . 'database', 0755, true);
}

// Ensure database sqlite file exists
if (!file_exists($dbFile)) {
    touch($dbFile);
}

try {
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Read SQL
    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        throw new RuntimeException("Failed to read SQL file\n");
    }

    // Execute in transaction
    $pdo->beginTransaction();
    // Turn off foreign keys during creation to avoid ordering issues
    $pdo->exec('PRAGMA foreign_keys = OFF;');
    $pdo->exec($sql);
    $pdo->exec('PRAGMA foreign_keys = ON;');
    $pdo->commit();

    echo "Import succeeded. Database file: $dbFile\n";
    exit(0);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Import failed: " . $e->getMessage() . "\n";
    exit(1);
}
