<?php
/**
 * Simple script to copy data from SQLite to MySQL
 * No Laravel dependencies
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

echo "🚀 SQLite to MySQL Data Migration\n";
echo "==================================\n\n";

// SQLite connection
$sqliteDb = getenv('DB_DATABASE') ?: 'database/database.sqlite';
$sqlitePath = __DIR__ . '/../' . $sqliteDb;

echo "📍 SQLite: $sqlitePath\n";

if (!file_exists($sqlitePath)) {
    echo "❌ SQLite database file not found!\n";
    exit(1);
}

$sqlite = new PDO("sqlite:$sqlitePath");
$sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// MySQL connection
$mysqlHost = getenv('MYSQL_HOST') ?: '127.0.0.1';
$mysqlPort = getenv('MYSQL_PORT') ?: '3306';
$mysqlUser = getenv('MYSQL_USERNAME') ?: 'root';
$mysqlPass = getenv('MYSQL_PASSWORD') ?: '';
$mysqlDb = getenv('MYSQL_DATABASE') ?: 'ukk_villa';

echo "📍 MySQL: $mysqlHost:$mysqlPort/$mysqlDb\n\n";

// Connect to MySQL
try {
    $mysql = new PDO(
        "mysql:host=$mysqlHost;port=$mysqlPort",
        $mysqlUser,
        $mysqlPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Create database
    echo "Step 1️⃣  Creating MySQL database...\n";
    $mysql->exec("CREATE DATABASE IF NOT EXISTS `$mysqlDb`");
    echo "✅ Database created\n\n";
    
    // Select database
    $mysql->exec("USE `$mysqlDb`");
    
    // Get tables from SQLite
    echo "Step 2️⃣  Getting table structure...\n";
    $stmt = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "ℹ️  No tables found in SQLite.\n";
        exit(0);
    }
    
    echo "Found " . count($tables) . " tables\n\n";
    
    // Copy each table
    echo "Step 3️⃣  Copying data...\n";
    $mysql->exec("SET FOREIGN_KEY_CHECKS=0");
    
    $totalRows = 0;
    foreach ($tables as $table) {
        // Get SQLite table structure
        $structStmt = $sqlite->query("PRAGMA table_info($table)");
        $columns = $structStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Drop table in MySQL if exists
        try {
            $mysql->exec("DROP TABLE IF EXISTS `$table`");
        } catch (\Exception $e) {
            // Ignore
        }
        
        // Create table in MySQL
        $createSql = "CREATE TABLE `$table` (";
        $colDefs = [];
        foreach ($columns as $col) {
            $name = $col['name'];
            $type = $col['type'];
            $notnull = $col['notnull'] ? 'NOT NULL' : 'NULL';
            $pk = $col['pk'] ? 'PRIMARY KEY' : '';
            
            // Map SQLite types to MySQL
            $mysqlType = $type;
            if (strpos(strtoupper($type), 'INT') !== false) {
                $mysqlType = 'INT';
            } elseif (strpos(strtoupper($type), 'CHAR') !== false) {
                $mysqlType = 'VARCHAR(255)';
            } elseif (strpos(strtoupper($type), 'TEXT') !== false) {
                $mysqlType = 'LONGTEXT';
            } elseif (strpos(strtoupper($type), 'REAL') !== false) {
                $mysqlType = 'DOUBLE';
            } elseif (strpos(strtoupper($type), 'BLOB') !== false) {
                $mysqlType = 'LONGBLOB';
            } elseif (strpos(strtoupper($type), 'TIMESTAMP') !== false || strpos(strtoupper($type), 'DATETIME') !== false) {
                $mysqlType = 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP';
                $notnull = '';
            }
            
            $colDefs[] = "`$name` $mysqlType $notnull $pk";
        }
        
        $createSql .= implode(", ", $colDefs) . ")";
        
        try {
            $mysql->exec($createSql);
        } catch (\Exception $e) {
            echo "⚠️  Error creating table $table: " . $e->getMessage() . "\n";
        }
        
        // Copy data
        $data = $sqlite->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($data)) {
            $placeholders = implode(", ", array_fill(0, count($data[0]), "?"));
            $cols = implode("`, `", array_keys($data[0]));
            $insertSql = "INSERT INTO `$table` (`$cols`) VALUES ($placeholders)";
            $insertStmt = $mysql->prepare($insertSql);
            
            foreach ($data as $row) {
                try {
                    $insertStmt->execute(array_values($row));
                } catch (\Exception $e) {
                    // Skip row if error
                }
            }
            
            $count = count($data);
            $totalRows += $count;
            echo "  ✓ $table: $count rows\n";
        } else {
            echo "  ✓ $table: 0 rows\n";
        }
    }
    
    $mysql->exec("SET FOREIGN_KEY_CHECKS=1");
    
    echo "\n✅ Migration completed!\n";
    echo "📊 Total rows copied: $totalRows\n";
    
} catch (PDOException $e) {
    echo "❌ MySQL Error: " . $e->getMessage() . "\n";
    exit(1);
}
