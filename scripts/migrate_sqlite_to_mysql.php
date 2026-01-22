<?php

/**
 * Script to migrate data from SQLite to MySQL
 * Usage: php scripts/migrate_sqlite_to_mysql.php
 */

require __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;

// Get app instance
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔄 Starting data migration from SQLite to MySQL...\n\n";

// Get table names from SQLite
$sqliteTables = DB::connection('sqlite')
    ->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");

if (empty($sqliteTables)) {
    echo "❌ No tables found in SQLite database.\n";
    exit(1);
}

echo "📊 Found " . count($sqliteTables) . " tables in SQLite:\n";
foreach ($sqliteTables as $table) {
    echo "  - {$table->name}\n";
}
echo "\n";

// Tables to migrate
$tablesToMigrate = array_map(fn($t) => $t->name, $sqliteTables);

try {
    DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0');
    
    foreach ($tablesToMigrate as $table) {
        echo "⏳ Processing table: $table\n";
        
        // Get all data from SQLite
        $data = DB::connection('sqlite')->table($table)->get()->toArray();
        
        if (empty($data)) {
            echo "   ✓ No data to copy (table is empty)\n";
            continue;
        }
        
        // Clear MySQL table
        try {
            DB::connection('mysql')->table($table)->truncate();
        } catch (\Exception $e) {
            echo "   ⚠️  Could not truncate table: " . $e->getMessage() . "\n";
        }
        
        // Insert data in batches
        $batchSize = 100;
        $batches = array_chunk($data, $batchSize);
        
        foreach ($batches as $batch) {
            // Convert objects to arrays
            $batch = array_map(fn($item) => (array)$item, $batch);
            DB::connection('mysql')->table($table)->insert($batch);
        }
        
        $count = count($data);
        echo "   ✓ Copied $count rows\n";
    }
    
    DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1');
    
    echo "\n✅ Migration completed successfully!\n";
    echo "\nData summary:\n";
    
    // Show counts
    foreach ($tablesToMigrate as $table) {
        $count = DB::connection('mysql')->table($table)->count();
        echo "  - $table: $count rows\n";
    }
    
} catch (\Exception $e) {
    DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1');
    echo "❌ Error during migration: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
