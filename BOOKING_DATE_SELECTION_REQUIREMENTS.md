# Detail "Pilih Tanggal Menginap" - Database Connection Requirements

## 📋 Ringkasan Fitur
Fitur "Pilih Tanggal Menginap" adalah kalender interaktif yang menampilkan ketersediaan villa berdasarkan data booking di database.

---

## 🗄️ Database Requirements

### Tabel: `bookings`
```sql
CREATE TABLE bookings (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED,
    villa_id BIGINT UNSIGNED,
    villa_room_type_id BIGINT UNSIGNED,
    booking_code VARCHAR(50) UNIQUE,
    check_in_date DATE NOT NULL,           -- ✅ WAJIB untuk kalender
    check_out_date DATE NOT NULL,          -- ✅ WAJIB untuk kalender
    nights INT,
    rooms_booked INT,
    guests INT,
    guest_count INT,
    guest_name VARCHAR(255),
    guest_email VARCHAR(255),
    guest_phone VARCHAR(20),
    special_requests TEXT,
    total_price DECIMAL(12, 2),
    status ENUM('pending', 'confirmed', 'cancelled', 'completed'),
    payment_status ENUM('unpaid', 'paid', 'refunded'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (villa_id) REFERENCES villas(id),
    FOREIGN KEY (villa_room_type_id) REFERENCES villa_room_types(id),
    INDEX idx_villa_dates (villa_id, check_in_date, check_out_date),
    INDEX idx_status (status)
);
```

---

## 📊 Data Flow: Database → Kalender

### 1. **Fetch Booked Dates** (VillaController)
```php
// app/Http/Controllers/VillaController.php - show() method

public function show($id)
{
    $villa = Villa::findOrFail($id);
    
    // Fetch ALL booked dates untuk kalender
    $bookedDates = Booking::where('villa_id', $id)
        ->where('status', '!=', 'cancelled')  // Exclude cancelled bookings
        ->where('check_out_date', '>=', now()->format('Y-m-d'))
        ->select('check_in_date', 'check_out_date')
        ->orderBy('check_in_date')
        ->get();
    
    return view('guest.villa_detail', [
        'villa' => $villa,
        'bookedDates' => $bookedDates  // Pass ke view
    ]);
}
```

### 2. **Pass ke JavaScript** (Blade View)
```blade
<!-- resources/views/guest/villa_detail.blade.php -->
<script>
    // Convert PHP collection to JSON untuk JavaScript
    const bookedDatesJson = @json($bookedDates ?? []);
</script>
```

---

## 🎨 Fitur-Fitur Kalender yang Harus Ada

### Status Tanggal di Kalender:

| Status | Warna | Ikon | Interaksi | Deskripsi |
|--------|-------|------|-----------|-----------|
| **Tersedia** | Hijau (#e8f5e9) | ✓ | Clickable | Tanggal bisa dipilih |
| **Dipesan** | Merah (#ffebee) | ❌ | Not Clickable | Check-in atau check-out sudah ada di tanggal lain |
| **Hari Ini** | Biru (#e3f2fd) | 🔵 | Clickable | Tanggal hari ini |
| **Sudah Lewat** | Abu-abu (#e0e0e0) | - | Not Clickable | Tanggal di masa lalu |

### Logika Penandaan Status:

```javascript
// Parsing booked dates dari database
const bookedDatesSet = new Set();
bookedDatesJson.forEach(booking => {
    const startDate = stringToDate(booking.check_in_date);
    const endDate = stringToDate(booking.check_out_date);
    
    // Mark SEMUA hari antara check_in dan check_out sebagai booked
    for (let d = new Date(startDate); d < endDate; d.setDate(d.getDate() + 1)) {
        bookedDatesSet.add(dateToString(d));  // Format: YYYY-MM-DD
    }
});

// Check status setiap tanggal
const isBooked = bookedDatesSet.has(dateStr);
```

---

## 📝 Form Fields yang Terkait Kalender

### Input Hidden untuk Booked Dates:
```blade
<!-- Untuk API calls atau backend validation -->
<input type="hidden" id="bookedDates" value="{{ json_encode($bookedDates) }}">
```

### Input Fields Check-in/Check-out:
```blade
<!-- Check In Date - diisi otomatis dari kalender -->
<input type="date" id="villaBookingCheckIn" name="checkin" 
       min="{{ date('Y-m-d') }}" required>

<!-- Check Out Date - diisi otomatis dari kalender -->
<input type="date" id="villaBookingCheckOut" name="checkout" 
       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
```

---

## 🔄 Validasi yang Diperlukan

### Frontend Validation (JavaScript):
```javascript
function validateDateSelection() {
    const checkIn = stringToDate(checkInValue);
    const checkOut = stringToDate(checkOutValue);
    
    // 1. Check-out harus setelah check-in
    if (checkOut <= checkIn) {
        alert('Tanggal checkout harus setelah check-in!');
        return false;
    }
    
    // 2. Tidak boleh memilih tanggal yang sudah dipesan
    if (bookedDatesSet.has(dateStr)) {
        alert('Tanggal ini sudah dipesan!');
        return false;
    }
    
    // 3. Minimal 1 malam
    const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
    if (nights < 1) {
        alert('Minimal 1 malam!');
        return false;
    }
    
    return true;
}
```

### Backend Validation (Laravel):
```php
// app/Http/Controllers/GuestController.php

public function storeBooking(Request $request)
{
    $validated = $request->validate([
        'villa_id' => 'required|exists:villas,id',
        'checkin' => 'required|date|after_or_equal:today',
        'checkout' => 'required|date|after:checkin',
    ]);
    
    // ✅ Check availability di database
    $conflict = Booking::where('villa_id', $validated['villa_id'])
        ->where('status', '!=', 'cancelled')
        ->where(function($q) use ($validated) {
            $q->whereBetween('check_in_date', [$validated['checkin'], $validated['checkout']])
              ->orWhereBetween('check_out_date', [$validated['checkin'], $validated['checkout']])
              ->orWhere(function($q2) use ($validated) {
                  $q2->where('check_in_date', '<', $validated['checkin'])
                     ->where('check_out_date', '>', $validated['checkout']);
              });
        })
        ->exists();
    
    if ($conflict) {
        return back()->withErrors(['checkout' => 'Tanggal tidak tersedia']);
    }
    
    // Create booking...
}
```

---

## 🛠️ Controller Method yang Diperlukan

### 1. VillaController@show (WAJIB untuk data kalender)
```php
public function show($id)
{
    $villa = Villa::with('roomTypes')->findOrFail($id);
    
    // Get booked dates untuk tampilan kalender
    $bookedDates = Booking::where('villa_id', $id)
        ->whereIn('status', ['pending', 'confirmed', 'completed'])
        ->where('check_out_date', '>=', now()->format('Y-m-d'))
        ->select('check_in_date', 'check_out_date')
        ->get()
        ->toArray();
    
    return view('guest.villa_detail', compact('villa', 'bookedDates'));
}
```

### 2. GuestController@storeBooking (WAJIB untuk simpan booking)
```php
public function storeBooking(Request $request)
{
    // Validasi & simpan booking
    // Return success/error response
}
```

### 3. API Endpoint (Optional - untuk AJAX refresh)
```php
Route::get('/api/villa/{id}/availability', [BookingController::class, 'getAvailability']);

public function getAvailability($villaId)
{
    $bookedDates = Booking::where('villa_id', $villaId)
        ->where('status', '!=', 'cancelled')
        ->where('check_out_date', '>=', now())
        ->select('check_in_date', 'check_out_date')
        ->get();
    
    return response()->json(['booked_dates' => $bookedDates]);
}
```

---

## 📋 Checklist Database Connection

- [ ] Table `bookings` memiliki kolom `check_in_date` dan `check_out_date`
- [ ] Kolom `status` untuk filter booking yang active
- [ ] Index pada `villa_id` dan `check_in_date`, `check_out_date` untuk query cepat
- [ ] VillaController::show() mengirim `$bookedDates` ke view
- [ ] View menerima `@json($bookedDates)` di JavaScript
- [ ] JavaScript parsing booked dates dengan format YYYY-MM-DD
- [ ] Form validasi check-in < check-out
- [ ] Backend validasi tidak ada conflict dengan booking lain
- [ ] GuestController::storeBooking() menyimpan booking ke database
- [ ] Success response menampilkan booking confirmation

---

## 🔍 Debugging Tips

### 1. Cek Booked Dates di Console:
```javascript
console.log('Booked dates:', bookedDatesJson);
console.log('Booked set:', bookedDatesSet);
```

### 2. Cek Database Query:
```php
// Tambah di VillaController::show()
dd(Booking::where('villa_id', $id)->get());
```

### 3. Cek JavaScript Date Parsing:
```javascript
const testDate = stringToDate('2026-01-28');
console.log(testDate);  // Harus valid Date object
```

---

## 📱 Responsive Design Requirements

- [ ] Kalender responsive di mobile (swipe untuk next/prev bulan)
- [ ] Touch-friendly date buttons (min 48px)
- [ ] Date picker fallback untuk browser lama
- [ ] Legend terlihat di semua ukuran layar

---

## 🔐 Security Considerations

1. **Validate dates server-side** - jangan percaya input dari client
2. **Check user permissions** - pastikan user bisa booking villa ini
3. **Prevent double-booking** - use database transaction
4. **Rate limit** - cegah spam booking requests

```php
// Transaction untuk mencegah race condition
DB::transaction(function () {
    $booking = Booking::create([...]);
});
```

