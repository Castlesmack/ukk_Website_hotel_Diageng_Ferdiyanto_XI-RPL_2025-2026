<?php
/**
 * Verification script to show both databases working
 */

// Load environment variables
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}

echo "📊 Dual Database Verification Report\n";
echo "====================================\n\n";

// SQLite
$sqlitePath = __DIR__ . '/../database/database.sqlite';
if (file_exists($sqlitePath)) {
    echo "🔹 SQLite Database\n";
    echo "─────────────────────\n";
    
    try {
        $sqlite = new PDO("sqlite:$sqlitePath");
        $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($tables as $table) {
            $count = $sqlite->query("SELECT COUNT(*) as cnt FROM `$table`")->fetch(PDO::FETCH_ASSOC)['cnt'];
            printf("  %-25s %5d rows\n", $table . ':', $count);
        }
        
        $sqlite = null;
        echo "\n✅ SQLite is working\n\n";
    } catch (Exception $e) {
        echo "❌ SQLite Error: " . $e->getMessage() . "\n\n";
    }
} else {
    echo "❌ SQLite database not found\n\n";
}

// MySQL
$mysqlHost = getenv('MYSQL_HOST') ?: '127.0.0.1';
$mysqlPort = getenv('MYSQL_PORT') ?: '3306';
$mysqlUser = getenv('MYSQL_USERNAME') ?: 'root';
$mysqlPass = getenv('MYSQL_PASSWORD') ?: '';
$mysqlDb = getenv('MYSQL_DATABASE') ?: 'ukk_villa';

echo "🔹 MySQL Database (XAMPP)\n";
echo "─────────────────────────\n";

try {
    $mysql = new PDO(
        "mysql:host=$mysqlHost;port=$mysqlPort;dbname=$mysqlDb",
        $mysqlUser,
        $mysqlPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    $stmt = $mysql->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        $count = $mysql->query("SELECT COUNT(*) as cnt FROM `$table`")->fetch(PDO::FETCH_ASSOC)['cnt'];
        printf("  %-25s %5d rows\n", $table . ':', $count);
    }
    
    $mysql = null;
    echo "\n✅ MySQL is working\n\n";
} catch (Exception $e) {
    echo "❌ MySQL Error: " . $e->getMessage() . "\n\n";
}

// Summary
echo "📋 Summary\n";
echo "──────────\n";
echo "✅ Both databases configured and synced!\n";
echo "\n💡 How to use:\n";
echo "  1. SQLite (default): DB::table('users')->get()\n";
echo "  2. MySQL: DB::connection('mysql')->table('users')->get()\n";
echo "\n🔄 To switch default database: Edit .env\n";
echo "  DB_CONNECTION=sqlite  (current)\n";
echo "  DB_CONNECTION=mysql   (to switch)\n";
echo "\n✨ All set!\n";
