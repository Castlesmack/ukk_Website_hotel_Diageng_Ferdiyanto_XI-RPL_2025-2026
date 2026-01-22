@echo off
REM Start WebSocket and Development Environment
REM This script starts all necessary services for development

cls
echo ========================================
echo   UKK Villa Development Environment
echo ========================================
echo.

REM Create multiple terminal windows for each service
echo Starting services...
echo.

REM Start Laravel Development Server
echo [1/3] Starting Laravel Development Server (http://localhost:8000)...
start "Laravel Server" cmd /k "cd /d C:\Users\HP\UKK_Villa && php artisan serve"
timeout /t 2

REM Start WebSocket Server (Reverb)
echo [2/3] Starting WebSocket Server (ws://localhost:8080)...
start "WebSocket Server" cmd /k "cd /d C:\Users\HP\UKK_Villa && php artisan reverb:start"
timeout /t 2

REM Start Queue Worker
echo [3/3] Starting Queue Worker...
start "Queue Worker" cmd /k "cd /d C:\Users\HP\UKK_Villa && php artisan queue:work --timeout=60"
timeout /t 1

echo.
echo ========================================
echo   ✅ All services started!
echo ========================================
echo.
echo Available URLs:
echo   - Application: http://localhost:8000
echo   - WebSocket: ws://localhost:8080
echo   - WebSocket Test: http://localhost:8000/websocket-test
echo.
echo MySQL/XAMPP:
echo   - Host: 127.0.0.1:3306
echo   - Database: ukk_villa
echo   - Username: root
echo.
echo To stop services: Close the terminal windows
echo.
echo Press any key to close this window...
pause
