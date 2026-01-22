# 🚀 WebSocket Quick Start Guide

## What's Been Set Up?

✅ **Laravel Reverb** - WebSocket server for real-time communication
✅ **Events** - `BookingUpdated` and `VillaAvailabilityChanged` 
✅ **Test Dashboard** - Visual interface to test WebSockets
✅ **Dual Database** - SQLite (dev) + MySQL (XAMPP)

---

## Starting Your Development Environment

### Option 1: Quick Start (Recommended)
```bash
double-click start-dev.bat
```
This starts:
- Laravel server on `http://localhost:8000`
- WebSocket server on `ws://localhost:8080`
- Queue worker for real-time events

### Option 2: Manual Start

**Terminal 1 - Laravel App:**
```bash
cd C:\Users\HP\UKK_Villa
php artisan serve
```
Visit: http://localhost:8000

**Terminal 2 - WebSocket Server:**
```bash
php artisan reverb:start
```

**Terminal 3 - Queue Worker:**
```bash
php artisan queue:work --timeout=60
```

---

## Test WebSocket Connection

1. Open your browser: http://localhost:8000/websocket-test
2. You should see:
   - ✅ "Connected" status
   - 📝 Subscribed to channels
3. Click test buttons to see real-time events

---

## Broadcasting Events in Your Code

### When a Booking is Created/Updated

```php
use App\Events\BookingUpdated;

// In your controller
$booking = Booking::find($id);
BookingUpdated::dispatch($booking, 'Booking confirmed');

// All connected clients receive:
{
  "booking_id": 1,
  "status": "confirmed",
  "message": "Booking confirmed"
}
```

### When Villa Availability Changes

```php
use App\Events\VillaAvailabilityChanged;

// In your controller
$villa = Villa::find($id);
VillaAvailabilityChanged::dispatch($villa, '2026-02-01', '2026-02-05', false);

// All connected clients receive:
{
  "villa_id": 1,
  "is_available": false,
  "check_in_date": "2026-02-01"
}
```

---

## Real-Time Updates in Frontend

### Blade Template Example

```blade
<div id="booking-status">Status: Loading...</div>

<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.1/dist/echo.iife.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pusher-js@8.1.0/dist/web/pusher.min.js"></script>

<script>
const echo = new Echo({
    broadcaster: 'reverb',
    key: "{{ env('REVERB_APP_KEY') }}",
    wsHost: "{{ env('REVERB_HOST', 'localhost') }}",
    wsPort: "{{ env('REVERB_PORT', 8080) }}",
    forceTLS: false,
});

echo.channel('bookings')
    .listen('booking.updated', (data) => {
        document.getElementById('booking-status').innerHTML = 
            `Status: ${data.status} - ${data.message}`;
    });
</script>
```

---

## Databases

### SQLite (Default)
```
Location: C:\Users\HP\UKK_Villa\database\database.sqlite
Use when: Local development
```

### MySQL (XAMPP)
```
Host: 127.0.0.1:3306
Database: ukk_villa
User: root
Use when: Testing production setup
```

### Switch Between Them

Edit `.env`:
```
DB_CONNECTION=sqlite    # For SQLite
DB_CONNECTION=mysql     # For MySQL
```

Then sync if needed:
```bash
php scripts/sync_databases.php
```

---

## File Structure

```
📂 UKK_Villa
├── 📄 WEBSOCKET_SETUP.md          ← Detailed guide
├── 📄 start-dev.bat                ← Start all services
├── 📂 app/Events/
│   ├── BookingUpdated.php          ← Booking events
│   └── VillaAvailabilityChanged.php ← Availability events
├── 📂 resources/views/
│   └── websocket-test.blade.php    ← Test dashboard
├── 📂 routes/
│   ├── web.php                     ← /websocket-test route
│   └── channels.php                ← Channel authorization
├── 📂 config/
│   └── broadcasting.php            ← WebSocket config
└── 📄 .env                         ← Environment variables
```

---

## Common Issues & Solutions

### ❌ WebSocket not connecting
```bash
# Ensure Reverb is running on port 8080
php artisan reverb:start

# Check if port is in use
netstat -ano | findstr :8080
```

### ❌ Events not broadcasting
```bash
# Ensure queue worker is running
php artisan queue:work --timeout=60
```

### ❌ Changes not reflecting
```bash
# Clear config cache
php artisan config:clear
php artisan cache:clear
```

### ❌ Port 8080 already in use
```bash
# Change REVERB_PORT in .env
REVERB_PORT=8081

# Then restart Reverb
```

---

## Performance Tips

1. **Check connection:**
   Visit http://localhost:8000/websocket-test

2. **Monitor events:**
   ```bash
   php artisan reverb:start --debug
   ```

3. **View logs:**
   ```
   storage/logs/laravel.log
   ```

4. **Database sync:**
   ```bash
   php scripts/verify_databases.php
   ```

---

## Production Checklist

- [ ] Generate new Reverb keys: `php artisan reverb:restart`
- [ ] Enable SSL: Set `REVERB_SCHEME=https` and `REVERB_PORT=443`
- [ ] Use process manager (Supervisor/systemd) to keep Reverb running
- [ ] Configure firewall to allow WebSocket port
- [ ] Update `.env` with production domain/IP
- [ ] Test with production database (MySQL)

---

## Useful Commands

```bash
# Start WebSocket server
php artisan reverb:start

# With debugging
php artisan reverb:start --debug

# Queue worker
php artisan queue:work

# Clear everything
php artisan cache:clear && php artisan config:clear

# Tinker (test events manually)
php artisan tinker
> App\Events\BookingUpdated::dispatch(App\Models\Booking::first())

# Sync databases
php scripts/sync_databases.php

# Verify databases
php scripts/verify_databases.php
```

---

## Next Steps

1. ✅ Start your dev environment: `start-dev.bat`
2. ✅ Test WebSocket: http://localhost:8000/websocket-test
3. ✅ Implement events in your controllers
4. ✅ Add real-time UI updates to your views
5. ✅ Read detailed guide: `WEBSOCKET_SETUP.md`

---

**Questions?** Check `WEBSOCKET_SETUP.md` for detailed documentation.

🎉 **Happy coding!**
