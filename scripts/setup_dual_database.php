<?php

/**
 * Setup script for dual database configuration
 * This script will:
 * 1. Create MySQL database if it doesn't exist
 * 2. Run migrations on MySQL
 * 3. Copy data from SQLite to MySQL
 */

require __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🚀 Dual Database Setup Script\n";
echo "==============================\n\n";

// Step 1: Check MySQL connection
echo "Step 1️⃣  Testing MySQL connection...\n";
try {
    DB::connection('mysql')->getPdo();
    echo "✅ MySQL connection successful!\n\n";
} catch (\Exception $e) {
    echo "❌ MySQL connection failed: " . $e->getMessage() . "\n";
    echo "⚠️  Make sure XAMPP MySQL is running.\n";
    exit(1);
}

// Step 2: Create database if it doesn't exist
echo "Step 2️⃣  Creating MySQL database...\n";
$database = env('MYSQL_DATABASE', 'ukk_villa');
try {
    DB::connection('mysql')->statement("CREATE DATABASE IF NOT EXISTS `$database`");
    echo "✅ Database '$database' ready!\n\n";
} catch (\Exception $e) {
    echo "❌ Error creating database: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 3: Run migrations on MySQL
echo "Step 3️⃣  Running migrations on MySQL...\n";
try {
    Artisan::call('migrate', ['--database' => 'mysql', '--force' => true]);
    echo "✅ Migrations completed!\n\n";
} catch (\Exception $e) {
    echo "❌ Migration error: " . $e->getMessage() . "\n";
}

// Step 4: Copy data from SQLite to MySQL
echo "Step 4️⃣  Copying data from SQLite to MySQL...\n";
try {
    // Get table names from SQLite
    $sqliteTables = DB::connection('sqlite')
        ->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
    
    if (empty($sqliteTables)) {
        echo "ℹ️  No data to copy from SQLite.\n\n";
    } else {
        $tablesToMigrate = array_map(fn($t) => $t->name, $sqliteTables);
        
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0');
        
        $totalRows = 0;
        foreach ($tablesToMigrate as $table) {
            // Get all data from SQLite
            $data = DB::connection('sqlite')->table($table)->get()->toArray();
            
            if (!empty($data)) {
                // Clear MySQL table
                try {
                    DB::connection('mysql')->table($table)->truncate();
                } catch (\Exception $e) {
                    // Table might not exist yet
                }
                
                // Insert data in batches
                $batchSize = 100;
                $batches = array_chunk($data, $batchSize);
                
                foreach ($batches as $batch) {
                    $batch = array_map(fn($item) => (array)$item, $batch);
                    DB::connection('mysql')->table($table)->insert($batch);
                }
                
                $count = count($data);
                $totalRows += $count;
                echo "  ✓ $table: $count rows\n";
            }
        }
        
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1');
        echo "✅ Data migration completed! ($totalRows total rows)\n\n";
    }
} catch (\Exception $e) {
    DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1');
    echo "❌ Data migration error: " . $e->getMessage() . "\n\n";
}

// Summary
echo "📋 Setup Summary:\n";
echo "==================\n";
echo "✅ SQLite Database: database/database.sqlite\n";
echo "✅ MySQL Database: $database\n";
echo "\n💡 Tips:\n";
echo "  - Use SQLite: DB::connection('sqlite')->table('users')->get()\n";
echo "  - Use MySQL: DB::connection('mysql')->table('users')->get()\n";
echo "  - Switch default: Change DB_CONNECTION in .env\n";
echo "\n✨ Setup complete!\n";
