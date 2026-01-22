<?php
/**
 * WebSocket Installation Verification Script
 * Checks if all components are properly installed and configured
 */

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║          WebSocket Installation Verification              ║\n";
echo "║              Laravel Reverb Setup Check                   ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

$checks = [];
$failures = [];

// 1. Check Laravel Reverb package
echo "Checking packages...\n";
if (file_exists(__DIR__ . '/vendor/laravel/reverb/composer.json')) {
    echo "  ✅ Laravel Reverb installed\n";
    $checks[] = true;
} else {
    echo "  ❌ Laravel Reverb not found\n";
    $checks[] = false;
    $failures[] = "Run: composer require laravel/reverb";
}

// 2. Check Laravel Echo
if (file_exists(__DIR__ . '/node_modules/laravel-echo/dist/echo.iife.js')) {
    echo "  ✅ Laravel Echo installed\n";
    $checks[] = true;
} else {
    echo "  ❌ Laravel Echo not found\n";
    $checks[] = false;
    $failures[] = "Run: npm install laravel-echo";
}

// 3. Check Pusher.js
if (file_exists(__DIR__ . '/node_modules/pusher-js/dist/web/pusher.min.js')) {
    echo "  ✅ Pusher.js installed\n";
    $checks[] = true;
} else {
    echo "  ⚠️  Pusher.js optional (for fallback)\n";
}

echo "\n";

// 4. Check configuration files
echo "Checking configuration files...\n";
if (file_exists(__DIR__ . '/config/broadcasting.php')) {
    echo "  ✅ Broadcasting config exists\n";
    
    $config = file_get_contents(__DIR__ . '/config/broadcasting.php');
    if (strpos($config, 'reverb') !== false) {
        echo "  ✅ Reverb configuration found\n";
        $checks[] = true;
    } else {
        echo "  ❌ Reverb not configured in broadcasting.php\n";
        $checks[] = false;
        $failures[] = "Update config/broadcasting.php";
    }
} else {
    echo "  ❌ Broadcasting config missing\n";
    $checks[] = false;
    $failures[] = "Run: php artisan install:broadcasting";
}

if (file_exists(__DIR__ . '/routes/channels.php')) {
    echo "  ✅ Channels file exists\n";
    $checks[] = true;
} else {
    echo "  ⚠️  channels.php not found (optional for basic setup)\n";
}

echo "\n";

// 5. Check .env variables
echo "Checking environment variables...\n";
$env = parse_ini_file(__DIR__ . '/.env');

$required_vars = [
    'BROADCAST_CONNECTION' => 'reverb',
    'REVERB_APP_ID' => 'ukk-villa',
    'REVERB_APP_KEY' => 'default_app_key',
    'REVERB_APP_SECRET' => 'default_app_secret',
    'REVERB_HOST' => 'localhost',
    'REVERB_PORT' => '8080',
];

foreach ($required_vars as $var => $expected) {
    if (isset($env[$var])) {
        $value = $env[$var];
        if ($var === 'BROADCAST_CONNECTION' && $value === 'reverb') {
            echo "  ✅ $var = $value\n";
            $checks[] = true;
        } else if ($var !== 'BROADCAST_CONNECTION') {
            echo "  ✅ $var configured\n";
            $checks[] = true;
        } else {
            echo "  ❌ $var not set to 'reverb'\n";
            $checks[] = false;
            $failures[] = "Update .env: $var=$expected";
        }
    } else {
        echo "  ❌ $var not found in .env\n";
        $checks[] = false;
        $failures[] = "Add to .env: $var=$expected";
    }
}

echo "\n";

// 6. Check event files
echo "Checking event files...\n";
$events = [
    'app/Events/BookingUpdated.php',
    'app/Events/VillaAvailabilityChanged.php',
];

foreach ($events as $event) {
    if (file_exists(__DIR__ . '/' . $event)) {
        echo "  ✅ " . basename($event) . " created\n";
        $checks[] = true;
    } else {
        echo "  ❌ " . basename($event) . " missing\n";
        $checks[] = false;
        $failures[] = "Event file: $event";
    }
}

echo "\n";

// 7. Check test dashboard
echo "Checking test dashboard...\n";
if (file_exists(__DIR__ . '/resources/views/websocket-test.blade.php')) {
    echo "  ✅ WebSocket test dashboard created\n";
    echo "     URL: http://localhost:8000/websocket-test\n";
    $checks[] = true;
} else {
    echo "  ❌ Test dashboard not found\n";
    $checks[] = false;
    $failures[] = "Create: resources/views/websocket-test.blade.php";
}

echo "\n";

// 8. Check start script
echo "Checking startup scripts...\n";
if (file_exists(__DIR__ . '/start-dev.bat')) {
    echo "  ✅ Development startup script (start-dev.bat)\n";
    $checks[] = true;
} else {
    echo "  ⚠️  Startup script not found (optional)\n";
}

echo "\n";

// 9. Check database
echo "Checking databases...\n";
if (file_exists(__DIR__ . '/database/database.sqlite')) {
    echo "  ✅ SQLite database exists\n";
    $checks[] = true;
} else {
    echo "  ⚠️  SQLite database not found (will be created on first run)\n";
}

$env_db = $env['MYSQL_DATABASE'] ?? 'ukk_villa';
echo "  ℹ️  MySQL database: $env_db\n";

echo "\n";

// 10. Check documentation
echo "Checking documentation...\n";
$docs = [
    'WEBSOCKET_SETUP.md',
    'WEBSOCKET_QUICK_START.md',
    'WEBSOCKET_IMPLEMENTATION_COMPLETE.md',
];

foreach ($docs as $doc) {
    if (file_exists(__DIR__ . '/' . $doc)) {
        echo "  ✅ $doc\n";
        $checks[] = true;
    } else {
        echo "  ⚠️  $doc not found\n";
    }
}

echo "\n";

// Summary
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                    VERIFICATION SUMMARY                   ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

$total = count($checks);
$passed = array_sum($checks);
$percentage = ($total > 0) ? round(($passed / $total) * 100) : 0;

echo "Status: $passed/$total checks passed ($percentage%)\n\n";

if ($percentage === 100) {
    echo "✅ All systems operational!\n\n";
    echo "Next steps:\n";
    echo "  1. Start development: double-click start-dev.bat\n";
    echo "  2. Test WebSocket: http://localhost:8000/websocket-test\n";
    echo "  3. Read documentation: WEBSOCKET_QUICK_START.md\n";
} else if ($percentage >= 80) {
    echo "⚠️  Minor issues detected:\n\n";
    foreach ($failures as $failure) {
        echo "  - $failure\n";
    }
    echo "\nResolve these issues and run verification again.\n";
} else {
    echo "❌ Critical issues detected:\n\n";
    foreach ($failures as $failure) {
        echo "  - $failure\n";
    }
    echo "\nPlease resolve these issues before using WebSocket.\n";
}

echo "\n";

// Quick reference
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                    QUICK REFERENCE                        ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

echo "🚀 Start WebSocket Server:\n";
echo "   php artisan reverb:start\n\n";

echo "📊 Test Dashboard:\n";
echo "   http://localhost:8000/websocket-test\n\n";

echo "📚 Documentation:\n";
echo "   - Quick Start: WEBSOCKET_QUICK_START.md\n";
echo "   - Full Guide: WEBSOCKET_SETUP.md\n";
echo "   - Status: WEBSOCKET_IMPLEMENTATION_COMPLETE.md\n\n";

echo "🔧 Useful Commands:\n";
echo "   php artisan tinker\n";
echo "   > App\\Events\\BookingUpdated::dispatch(App\\Models\\Booking::first())\n\n";

echo "💾 Database:\n";
echo "   Sync: php scripts/sync_databases.php\n";
echo "   Verify: php scripts/verify_databases.php\n\n";

echo "═══════════════════════════════════════════════════════════\n\n";
