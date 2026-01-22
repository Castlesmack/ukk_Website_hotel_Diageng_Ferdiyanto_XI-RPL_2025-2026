# 🎯 WebSocket - Next Actions Guide

## What You Have Now

✅ **Complete WebSocket Infrastructure**
- Laravel Reverb server on port 8080
- Broadcasting events system
- Test dashboard at http://localhost:8000/websocket-test
- Dual database support (SQLite + MySQL)
- 4 comprehensive documentation files

---

## IMMEDIATE ACTIONS (Do Now)

### 1. Start Your Development Environment
```bash
# Double-click this file
start-dev.bat
```

Or manually start 3 terminals:
```bash
Terminal 1: php artisan serve
Terminal 2: php artisan reverb:start  
Terminal 3: php artisan queue:work --timeout=60
```

### 2. Test WebSocket Connection
Open in browser: **http://localhost:8000/websocket-test**

Expected results:
- ✅ "Connected" status appears
- ✅ See "bookings" and "villa-availability" channels
- ✅ Click buttons to see events in real-time

### 3. Verify Everything Works
```bash
php artisan tinker
> App\Events\BookingUpdated::dispatch(App\Models\Booking::first())

# Now check test dashboard - you should see the event!
```

---

## SHORT-TERM ACTIONS (This Week)

### 1. Add Event Broadcasting to Booking Controller

File: `app/Http/Controllers/Admin/ReservationController.php`

```php
use App\Events\BookingUpdated;

// In your update booking method:
public function update(Request $request, $id)
{
    $booking = Booking::find($id);
    $booking->update($request->all());
    
    // BROADCAST EVENT
    BookingUpdated::dispatch($booking, 'Booking updated successfully');
    
    return redirect()->back();
}
```

### 2. Add Event Broadcasting to Villa Controller

File: `app/Http/Controllers/Admin/VillaController.php`

```php
use App\Events\VillaAvailabilityChanged;

// When updating villa or availability:
public function updateAvailability(Request $request, $villaId)
{
    $villa = Villa::find($villaId);
    $villa->update($request->all());
    
    // BROADCAST EVENT
    VillaAvailabilityChanged::dispatch(
        $villa,
        $request->check_in_date,
        $request->check_out_date,
        $request->is_available
    );
    
    return response()->json(['success' => true]);
}
```

### 3. Add Real-Time UI Updates to Views

Update: `resources/views/reception/reservations.blade.php`

```blade
<div id="booking-status">
    <!-- Display booking status here -->
</div>

@push('scripts')
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

// Listen to booking updates
echo.channel('bookings')
    .listen('booking.updated', (data) => {
        console.log('Booking updated:', data);
        
        // Update your UI
        document.getElementById('booking-status').innerHTML = `
            <div class="alert alert-success">
                ${data.message}
                <br>Status: ${data.status}
            </div>
        `;
        
        // Optionally: Reload booking list
        // location.reload();
    });
</script>
@endpush
```

### 4. Test with Multiple Browser Tabs

1. Open two browser windows
2. Go to reservation page in both
3. Update a booking in one window
4. Watch it update in the other window in real-time

---

## MEDIUM-TERM ACTIONS (Next Sprint)

### 1. Implement Private Channels

Edit: `routes/channels.php`

```php
use Illuminate\Support\Facades\Broadcast;

// Only user 123 can listen to their notifications
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
```

Backend:
```php
use Illuminate\Broadcasting\PrivateChannel;

class BookingUpdated implements ShouldBroadcast
{
    public function broadcastOn()
    {
        return [
            new PrivateChannel('user.' . $this->booking->user_id),
        ];
    }
}
```

Frontend:
```javascript
echo.private('user.' + userId)
    .listen('booking.updated', (data) => {
        // Only this user receives the event
    });
```

### 2. Add Presence Channels (Who's Online)

```php
// In channels.php
Broadcast::channel('presence.reception', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});
```

Frontend:
```javascript
echo.join('presence.reception')
    .here((users) => {
        console.log('Users online:', users);
    })
    .joining((user) => {
        console.log('User joined:', user.name);
    })
    .leaving((user) => {
        console.log('User left:', user.name);
    });
```

### 3. Add Real-Time Notifications

Create new event: `app/Events/NotificationSent.php`

```php
class NotificationSent implements ShouldBroadcast
{
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->user->id);
    }
}
```

Frontend:
```javascript
echo.private('user.' + userId)
    .listen('notification.sent', (data) => {
        // Show notification badge update
        document.querySelector('.notification-count').innerHTML = data.count;
    });
```

### 4. Implement Real-Time Chat

Create: `app/Events/MessageSent.php`

```php
class MessageSent implements ShouldBroadcast
{
    public function broadcastOn()
    {
        return new Channel('chat.booking.' . $this->booking->id);
    }
}
```

---

## PRODUCTION ACTIONS (Before Deployment)

### 1. Generate New WebSocket Keys
```bash
php artisan reverb:restart
```

### 2. Configure SSL/TLS
Edit `.env`:
```
REVERB_SCHEME=https
REVERB_PORT=443
```

### 3. Update Domain
```
REVERB_HOST=yourdomain.com
```

### 4. Deploy with Process Manager

Create Supervisor config:
```ini
[program:laravel-reverb]
process_name=%(program_name)s
command=php /path/to/artisan reverb:start
autostart=true
autorestart=true
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/reverb.log
```

### 5. Test in Production
```bash
# SSH into server
php artisan reverb:start
php artisan queue:work

# Test connection
curl -i http://localhost:8080
```

---

## FILE LOCATIONS REFERENCE

### Event Files
- `app/Events/BookingUpdated.php` - Booking events
- `app/Events/VillaAvailabilityChanged.php` - Availability events

### Configuration
- `config/broadcasting.php` - Broadcasting settings
- `routes/channels.php` - Channel authorization
- `.env` - Environment variables

### Frontend
- `resources/views/websocket-test.blade.php` - Test dashboard
- Any Blade view - Add Echo listeners

### Database
- `database/database.sqlite` - Local development
- MySQL - Production database

### Documentation
- `WEBSOCKET_QUICK_START.md` - Quick reference
- `WEBSOCKET_SETUP.md` - Complete guide
- `WEBSOCKET_READY.md` - Status report

---

## TESTING CHECKLIST

Before deploying to production:

- [ ] WebSocket connects: http://localhost:8000/websocket-test
- [ ] Booking events broadcast: Update booking → see event
- [ ] Villa events broadcast: Change availability → see event
- [ ] Queue worker running: php artisan queue:work
- [ ] Database synced: php scripts/verify_databases.php
- [ ] Multiple browsers work: Open in 2 windows
- [ ] Events persist in log: Check storage/logs/laravel.log
- [ ] Private channels work: Test with authenticated user
- [ ] Reconnection works: Stop server, watch auto-reconnect
- [ ] Performance acceptable: Monitor with debug flag

---

## COMMON MISTAKES TO AVOID

❌ **Don't:**
- Forget to start queue worker (events won't broadcast)
- Change REVERB_APP_KEY without updating frontend
- Deploy without SSL/TLS enabled
- Use default keys in production
- Broadcasting without implementing ShouldBroadcast

✅ **Do:**
- Always start 3 services: App, Reverb, Queue
- Keep WebSocket server running (use process manager)
- Test events with test dashboard
- Monitor logs for errors
- Use private channels for sensitive data
- Generate new keys for each environment

---

## PERFORMANCE TIPS

1. **Monitor Memory Usage:**
   ```bash
   php artisan reverb:start --debug
   ```

2. **Queue Heavy Broadcasting:**
   ```php
   class BookingUpdated implements ShouldBroadcast
   {
       public $connection = 'database';
       public $queue = 'broadcasts';
   }
   ```

3. **Batch Events:**
   ```php
   foreach ($bookings as $booking) {
       BookingUpdated::dispatch($booking);
   }
   ```

4. **Monitor Database:**
   ```bash
   php scripts/verify_databases.php
   ```

---

## SUPPORT & LEARNING

### Quick Help
1. Check: `WEBSOCKET_QUICK_START.md`
2. Search: `WEBSOCKET_SETUP.md`
3. Test: `http://localhost:8000/websocket-test`

### Resources
- Laravel Reverb: https://laravel.com/docs/11.x/reverb
- Broadcasting: https://laravel.com/docs/11.x/broadcasting
- Echo: https://laravel.com/docs/11.x/echo

### Debugging
```bash
# View logs
tail -f storage/logs/laravel.log

# Tinker test
php artisan tinker
> App\Events\BookingUpdated::dispatch(App\Models\Booking::first())

# Queue status
php artisan queue:failed
```

---

## SUMMARY

**You have:**
✅ Complete WebSocket infrastructure
✅ Broadcasting events
✅ Test dashboard
✅ Dual database
✅ Full documentation

**Next step:**
🚀 Start with: `start-dev.bat`
🧪 Test with: `http://localhost:8000/websocket-test`
📚 Learn with: `WEBSOCKET_QUICK_START.md`

**Then implement:**
1. Add event broadcasting to controllers
2. Add listeners to views
3. Test with multiple windows
4. Deploy to production

---

**Happy WebSocket development! 🎉**
