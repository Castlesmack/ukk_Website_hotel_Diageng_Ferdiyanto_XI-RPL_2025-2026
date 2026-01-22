# WebSocket Setup Guide - Laravel Reverb

## Overview
Your project now has **Laravel Reverb** configured for real-time WebSocket communication. This enables:
- Real-time booking updates
- Villa availability notifications
- Live chat and messaging
- Real-time calendar updates
- Push notifications

## Installation Summary

✅ **Installed Components:**
- `laravel/reverb` - WebSocket server (v1.7.0)
- `laravel-echo` - Frontend client
- `pusher-js` - Fallback transport

✅ **Configuration Files:**
- `config/broadcasting.php` - Broadcasting configuration
- `routes/channels.php` - Channel authorization
- `.env` - WebSocket environment variables

## Configuration

### Environment Variables (.env)
```
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=ukk-villa
REVERB_APP_KEY=default_app_key
REVERB_APP_SECRET=default_app_secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
```

### Quick Edit Command
```bash
# Update with your settings if needed
nano .env
```

## Running WebSocket Server

### Start Reverb WebSocket Server
```bash
php artisan reverb:start
```

### With Custom Host/Port
```bash
php artisan reverb:start --host=0.0.0.0 --port=8080
```

### In Background (Windows)
```bash
start php artisan reverb:start
```

### In Background (Mac/Linux)
```bash
php artisan reverb:start &
```

## Events Created

### 1. BookingUpdated Event
**File:** `app/Events/BookingUpdated.php`

Triggered when a booking status changes.

**Usage in Controller:**
```php
use App\Events\BookingUpdated;

// In your booking controller
BookingUpdated::dispatch($booking, 'Booking confirmed');

// Frontend receives:
{
  "booking_id": 1,
  "status": "confirmed",
  "message": "Booking confirmed",
  "timestamp": "2026-01-22 08:30:00"
}
```

### 2. VillaAvailabilityChanged Event
**File:** `app/Events/VillaAvailabilityChanged.php`

Triggered when villa availability changes.

**Usage in Controller:**
```php
use App\Events\VillaAvailabilityChanged;

// In your availability controller
VillaAvailabilityChanged::dispatch($villa, $checkIn, $checkOut, true);

// Frontend receives:
{
  "villa_id": 1,
  "villa_name": "Luxury Villa",
  "check_in_date": "2026-02-01",
  "check_out_date": "2026-02-05",
  "is_available": true,
  "timestamp": "2026-01-22 08:30:00"
}
```

## Frontend Integration

### Test Dashboard
Visit: `http://localhost:8000/websocket-test`

This page demonstrates:
- Real-time connection status
- Channel subscriptions
- Event logging
- Test event triggers

### Using in Blade Templates

```blade
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
        // Update UI here
    });

// Listen to villa availability
echo.channel('villa-availability')
    .listen('villa.availability_changed', (data) => {
        console.log('Villa availability changed:', data);
        // Update UI here
    });
</script>
```

### Vue.js Integration

```javascript
import Echo from 'laravel-echo';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: false,
});

// In component:
export default {
    mounted() {
        Echo.channel('bookings')
            .listen('booking.updated', (data) => {
                this.handleBookingUpdate(data);
            });
    }
}
```

### React Integration

```javascript
import Echo from 'laravel-echo';

const echo = new Echo({
    broadcaster: 'reverb',
    key: process.env.REACT_APP_REVERB_APP_KEY,
    wsHost: process.env.REACT_APP_REVERB_HOST,
    wsPort: process.env.REACT_APP_REVERB_PORT,
    forceTLS: false,
});

// In component:
useEffect(() => {
    const subscription = echo.channel('bookings')
        .listen('booking.updated', (data) => {
            handleBookingUpdate(data);
        });

    return () => subscription.stopListening();
}, []);
```

## Private Channels (Authentication Required)

### Setup Private Channels

Edit `routes/channels.php`:
```php
use Illuminate\Support\Facades\Broadcast;

// Private channel - only authenticated users
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private admin channel
Broadcast::channel('admin.notifications', function ($user) {
    return $user->is_admin;
});
```

### Broadcasting to Private Channels

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

### Listening to Private Channels (Frontend)

```javascript
echo.private('user.' + userId)
    .listen('booking.updated', (data) => {
        console.log('Your booking was updated:', data);
    });
```

## Advanced Features

### Presence Channels (Who's Online)

```php
// In channels.php
Broadcast::channel('presence.lobby', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});
```

### Conditional Broadcasting

```php
class BookingUpdated implements ShouldBroadcast
{
    // Only broadcast if condition is true
    public function broadcastWhen(): bool
    {
        return $this->booking->is_important;
    }
}
```

## Troubleshooting

### WebSocket Not Connecting

1. **Check if Reverb is running:**
   ```bash
   php artisan reverb:start
   ```

2. **Verify port 8080 is not in use:**
   ```bash
   # Windows
   netstat -ano | findstr :8080
   
   # Mac/Linux
   lsof -i :8080
   ```

3. **Check CORS settings** in `config/broadcasting.php`:
   ```php
   'allowed_origins' => ['*'], // For development only
   ```

4. **Firewall issue:** Allow port 8080 in your firewall

### Events Not Broadcasting

1. **Ensure queue is running:**
   ```bash
   php artisan queue:work
   ```

2. **Check if `ShouldBroadcast` is implemented** on your event

3. **Verify channel is subscribed** on frontend

4. **Check app key matches:**
   ```bash
   php artisan config:clear
   ```

### CSRF Token Issues

Add to your Blade template or JavaScript:
```php
<meta name="csrf-token" content="{{ csrf_token() }}">
```

## Database Sync

Your WebSocket data persists in both SQLite and MySQL:

```bash
# Sync databases after changes
php scripts/sync_databases.php
```

## Production Deployment

### For Production

1. **Install Reverb on production server:**
   ```bash
   composer require laravel/reverb
   ```

2. **Generate secure keys:**
   ```bash
   php artisan reverb:restart
   ```

3. **Use SSL (WSSE):**
   ```
   REVERB_SCHEME=https
   REVERB_PORT=443
   ```

4. **Deploy with process manager (Supervisor, systemd):**
   ```
   command=php artisan reverb:start
   ```

### Environment Variables for Production
```
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-secure-key
REVERB_APP_SECRET=your-secure-secret
REVERB_HOST=yourdomain.com
REVERB_PORT=443
REVERB_SCHEME=https
```

## Performance Tips

1. **Use queue for heavy broadcasts:**
   ```php
   class BookingUpdated implements ShouldBroadcast
   {
       public $connection = 'database';
       public $queue = 'broadcasts';
   }
   ```

2. **Batch events:**
   ```php
   foreach ($bookings as $booking) {
       BookingUpdated::dispatch($booking);
   }
   ```

3. **Monitor memory:**
   ```bash
   php artisan tinker
   > memory_get_usage(true) / 1024 / 1024 // MB
   ```

## Useful Resources

- [Laravel Reverb Docs](https://laravel.com/docs/11.x/reverb)
- [Laravel Broadcasting Docs](https://laravel.com/docs/11.x/broadcasting)
- [Laravel Echo Docs](https://laravel.com/docs/11.x/echo)

## Quick Reference Commands

```bash
# Start WebSocket server
php artisan reverb:start

# Clear config cache
php artisan config:clear

# Test connection
php artisan tinker
> App\Events\BookingUpdated::dispatch(App\Models\Booking::first(), 'Test')

# Monitor events
php artisan reverb:start --debug

# Restart (kill and start)
php artisan reverb:restart
```

---

**Status:** ✅ WebSocket setup complete and ready to use!
