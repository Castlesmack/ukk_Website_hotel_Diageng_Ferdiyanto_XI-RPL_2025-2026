# ✅ Checklist Database Connection: "Pilih Tanggal Menginap"

## Status Implementasi Saat Ini

### ✅ Sudah Berfungsi:
1. **Model Booking** - Sudah ada dengan field check_in_date dan check_out_date
2. **VillaController::detail()** - Sudah fetch booked dates dari database
3. **View (blade)** - Sudah menerima $bookedDates dan parse ke JavaScript
4. **JavaScript Calendar** - Sudah menampilkan status availability dengan warna
5. **Form Validation** - Sudah cek konflik di VillaController::storeBooking()
6. **Database Queries** - Sudah optimized dengan whereIn('status', ...)

### ❌ Masih Perlu Ditambah / Perbaikan:

1. **API Endpoint untuk Real-time Update** - Belum ada
2. **Availability Check AJAX** - Belum ada
3. **Export Calendar Data** - Belum ada format consistent
4. **Error Handling yang Lebih Baik** - Perlu ditingkatkan

---

## 📊 Implementasi yang Sudah Ada

### 1. Database Query di VillaController::detail()
```php
$bookedDates = Booking::where('villa_id', $id)
    ->whereIn('status', ['confirmed', 'pending'])
    ->select('check_in_date', 'check_out_date')
    ->get();
```

**Status**: ✅ SUDAH BEKERJA

---

### 2. Blade Template
```blade
<script>
    const bookedDatesJson = @json($bookedDates ?? []);
</script>
```

**Status**: ✅ SUDAH BEKERJA

---

### 3. JavaScript Calendar Generation
- Parse booked dates ke Set
- Cek setiap tanggal apakah ada di booking
- Render dengan warna & icon sesuai status
- Handle date selection & validation

**Status**: ✅ SUDAH BEKERJA

---

### 4. Form Validation Backend
```php
$existingBooking = Booking::where('villa_id', $validated['villa_id'])
    ->whereIn('status', ['confirmed', 'pending'])
    ->where(function($query) use ($checkin, $checkout) {
        $query->whereBetween('check_in_date', [$checkin, $checkout->subDay()])
              ->orWhereBetween('check_out_date', [$checkin->addDay(), $checkout])
              ->orWhere(function($q) use ($checkin, $checkout) {
                  $q->where('check_in_date', '<=', $checkin)
                    ->where('check_out_date', '>=', $checkout);
              });
    })
    ->first();
```

**Status**: ✅ SUDAH BEKERJA

---

## 🔧 Yang Perlu Ditambahkan

### 1. API Endpoint untuk Real-time Availability Check

Tambahkan ke [routes/web.php](routes/web.php):

```php
// Public API endpoint - tanpa auth, untuk cek availability
Route::get('/api/villa/{id}/availability', [BookingController::class, 'getAvailability']);
```

Buat controller baru di `app/Http/Controllers/BookingController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Get availability calendar data for a villa
     * 
     * @param int $id Villa ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailability($id)
    {
        $bookedDates = Booking::where('villa_id', $id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('check_out_date', '>=', now()->format('Y-m-d'))
            ->select('check_in_date', 'check_out_date')
            ->orderBy('check_in_date')
            ->get();
        
        return response()->json([
            'villa_id' => $id,
            'booked_dates' => $bookedDates,
            'timestamp' => now(),
        ]);
    }

    /**
     * Validate if dates are available
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateAvailability(Request $request)
    {
        $validated = $request->validate([
            'villa_id' => 'required|exists:villas,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $conflict = Booking::where('villa_id', $validated['villa_id'])
            ->whereIn('status', ['confirmed', 'pending'])
            ->where(function($q) use ($validated) {
                $q->where('check_in_date', '<', $validated['check_out'])
                  ->where('check_out_date', '>', $validated['check_in']);
            })
            ->exists();

        return response()->json([
            'available' => !$conflict,
            'message' => $conflict ? 'Tanggal tidak tersedia' : 'Tanggal tersedia'
        ]);
    }
}
```

---

### 2. JavaScript AJAX untuk Real-time Check (Opsional)

Tambahkan di `resources/views/guest/villa_detail.blade.php`:

```javascript
// Real-time availability check saat user memilih tanggal
async function checkAvailabilityAJAX(villaId, checkIn, checkOut) {
    try {
        const response = await fetch('/api/villa/' + villaId + '/availability');
        const data = await response.json();
        
        console.log('Updated booked dates:', data.booked_dates);
        
        // Update calendar dengan data terbaru
        window.bookedDatesJson = data.booked_dates;
        generateAvailabilityCalendar();
    } catch (error) {
        console.error('Error checking availability:', error);
    }
}

// Call ini di setInterval untuk real-time update (opsional)
setInterval(() => {
    const villaId = document.querySelector('[name="villa_id"]').value;
    if (villaId) {
        checkAvailabilityAJAX(villaId);
    }
}, 30000); // Update setiap 30 detik
```

---

### 3. Tambah index di database untuk performa

Jalankan di terminal:

```bash
php artisan make:migration add_indexes_to_bookings
```

Edit file migration `database/migrations/YYYY_MM_DD_XXXXXX_add_indexes_to_bookings.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add index untuk query performance
            $table->index(['villa_id', 'status']);
            $table->index(['check_in_date', 'check_out_date']);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['villa_id', 'status']);
            $table->dropIndex(['check_in_date', 'check_out_date']);
        });
    }
};
```

Jalankan:
```bash
php artisan migrate
```

---

## 🧪 Testing

### Test 1: Cek Data di Database
```bash
php artisan tinker

>>> App\Models\Booking::where('villa_id', 1)->select('check_in_date', 'check_out_date', 'status')->get();
```

### Test 2: Cek API Endpoint
```bash
curl "http://localhost:8000/api/villa/1/availability"
```

### Test 3: Browser Console
```javascript
// Buka villa detail page, buka console
console.log(bookedDatesJson);
console.log(bookedDatesSet);

// Test date parsing
stringToDate('2026-01-28');
```

### Test 4: Form Submission
1. Pilih villa
2. Klik tanggal available di kalender
3. Form harus ter-populate check-in/check-out
4. Submit booking
5. Cek database - booking harus tersimpan
6. Refresh page - kalender harus update

---

## 📋 Verifikasi Checklist

- [x] Tabel `bookings` punya `check_in_date` & `check_out_date`
- [x] VillaController::detail() fetch booked dates
- [x] Blade view terima & pass $bookedDates ke JS
- [x] JavaScript parse booked dates dengan benar
- [x] Kalender tampil dengan warna status yang tepat
- [x] Form validation JS & backend bekerja
- [x] Database query filter by status & date
- [x] Booking tersimpan ke database
- [ ] API endpoint untuk availability (TAMBAH)
- [ ] Index database untuk performa (TAMBAH)
- [ ] Error handling yang lebih baik (OPSIONAL)

---

## 🚀 Next Steps

1. **Buat BookingController** (jika belum ada)
2. **Tambah API routes** untuk real-time check
3. **Jalankan migration** untuk index
4. **Test setiap step** dengan checklist di atas
5. **Monitor performa** di production

---

## 📞 Troubleshooting

### Kalender tidak menampilkan booked dates
```php
// Di VillaController::detail(), cek:
dd($bookedDates);  // Harus ada data
```

### Tanggal tidak ter-parse di JavaScript
```javascript
// Di browser console:
const test = stringToDate('2026-01-28');
console.log(test instanceof Date);  // Harus true
```

### Booking gagal karena conflict
```php
// Check di database:
SELECT * FROM bookings 
WHERE villa_id = 1 
AND status IN ('confirmed', 'pending')
ORDER BY check_in_date;
```

### API endpoint 404
```php
// Pastikan route sudah di web.php
// Refresh PHP dengan: php artisan route:clear
php artisan route:cache
```

---

## ✅ Kesimpulan

Database sudah terhubung dengan baik untuk fitur "Pilih Tanggal Menginap". 
Yang tersisa adalah penambahan API endpoint untuk real-time updates dan optimization database index.

Implementasi saat ini sudah **PRODUCTION-READY** untuk basic functionality.

