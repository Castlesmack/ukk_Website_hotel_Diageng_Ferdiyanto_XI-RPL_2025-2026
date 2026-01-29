# 🧪 QUICK TEST GUIDE: Database Connection

Cara test apakah "Pilih Tanggal Menginap" sudah terhubung ke database dengan benar.

---

## ✅ Test 1: Database Query

### Via Artisan Tinker

```bash
cd c:\Users\HP\UKK_Villa
php artisan tinker

# Test 1: Lihat semua booking
>>> App\Models\Booking::all();

# Test 2: Lihat booking untuk villa_id = 1
>>> App\Models\Booking::where('villa_id', 1)->get();

# Test 3: Lihat booking dengan status 'confirmed'
>>> App\Models\Booking::where('villa_id', 1)
    ->where('status', 'confirmed')
    ->get();

# Test 4: Lihat booked dates untuk kalender
>>> App\Models\Booking::where('villa_id', 1)
    ->whereIn('status', ['confirmed', 'pending'])
    ->select('check_in_date', 'check_out_date')
    ->get();

# Expected Output:
=> Illuminate\Database\Eloquent\Collection {
     #items: array:2 [
       0 => App\Models\Booking {#1
         #attributes: array:2 [
           "check_in_date" => "2026-01-28"
           "check_out_date" => "2026-01-31"
         ]
       }
       1 => App\Models\Booking {#2
         #attributes: array:2 [
           "check_in_date" => "2026-02-05"
           "check_out_date" => "2026-02-08"
         ]
       }
     ]
   }
```

### Exit Tinker
```bash
>>> exit
```

---

## ✅ Test 2: API Endpoint

### Test GET Availability

**Menggunakan curl:**
```bash
curl "http://localhost:8000/api/villa/1/availability"
```

**Expected Response:**
```json
{
  "success": true,
  "villa_id": 1,
  "villa_name": "Villa Mewah Sunset",
  "booked_dates": [
    {
      "check_in_date": "2026-01-28",
      "check_out_date": "2026-01-31"
    },
    {
      "check_in_date": "2026-02-05",
      "check_out_date": "2026-02-08"
    }
  ],
  "total_booked_ranges": 2,
  "timestamp": "2026-01-28T10:30:00Z",
  "message": "Data tersedia"
}
```

### Test POST Validate

**Menggunakan curl:**
```bash
curl -X POST "http://localhost:8000/api/villa/availability/validate" \
  -H "Content-Type: application/json" \
  -d "{\"villa_id\": 1, \"check_in\": \"2026-01-29\", \"check_out\": \"2026-02-01\"}"
```

**Expected Response (Available):**
```json
{
  "success": true,
  "available": true,
  "villa_id": 1,
  "check_in": "2026-01-29",
  "check_out": "2026-02-01",
  "nights": 3,
  "total_price": 450000,
  "message": "Tanggal tersedia untuk booking"
}
```

**Test dengan tanggal yang conflict:**
```bash
curl -X POST "http://localhost:8000/api/villa/availability/validate" \
  -H "Content-Type: application/json" \
  -d "{\"villa_id\": 1, \"check_in\": \"2026-01-28\", \"check_out\": \"2026-02-01\"}"
```

**Expected Response (Not Available):**
```json
{
  "success": true,
  "available": false,
  "villa_id": 1,
  "check_in": "2026-01-28",
  "check_out": "2026-02-01",
  "nights": 4,
  "total_price": 600000,
  "message": "Tanggal tidak tersedia - ada booking lain",
  "conflicting_booking": {
    "check_in_date": "2026-01-28",
    "check_out_date": "2026-01-31",
    "status": "confirmed"
  }
}
```

---

## ✅ Test 3: Browser Console

### Open Villa Detail Page

1. Buka halaman villa detail: `http://localhost:8000/villa/1`
2. Buka Browser Developer Tools (F12)
3. Go to Console tab

### Test JavaScript Data

```javascript
// Cek apakah data sudah ter-load
console.log('Booked dates from PHP:', bookedDatesJson);

// Expected output:
// [
//   {check_in_date: "2026-01-28", check_out_date: "2026-01-31"},
//   {check_in_date: "2026-02-05", check_out_date: "2026-02-08"}
// ]

// Cek apakah Set sudah terbentuk
console.log('Booked dates set:', bookedDatesSet);

// Expected: Set dengan 6 items (28,29,30,5,6,7)

// Test date parsing
const testDate = stringToDate('2026-01-28');
console.log('Parsed date:', testDate);
console.log('Is valid Date?', testDate instanceof Date);

// Expected: Valid Date object
```

### Test Calendar Generation

```javascript
// Generate kalender dan lihat console
generateAvailabilityCalendar();

// Kalender harus render dengan:
// - Tanggal 28,29,30 berwarna merah (booked)
// - Tanggal lain berwarna hijau (available)
// - Buton next/prev berfungsi
```

---

## ✅ Test 4: Kalender Visual

1. Buka halaman villa detail: `http://localhost:8000/villa/1`
2. Lihat section "📅 Pilih Tanggal Menginap"
3. Verifikasi:
   - ✅ Kalender menampilkan bulan saat ini
   - ✅ Ada tanggal dengan warna merah (booked)
   - ✅ Ada tanggal dengan warna hijau (available)
   - ✅ Tombol "← Sebelumnya" dan "Selanjutnya →" berfungsi
   - ✅ Klik tanggal hijau mengisi form check-in/check-out

---

## ✅ Test 5: Form Filling

1. Di kalender, klik tanggal hijau (available)
2. Verifikasi:
   - ✅ Form "Check In" terisi tanggal yang diklik
   - ✅ Form "Check Out" kosong (untuk dipilih berikutnya)
3. Klik tanggal hijau lain yang lebih late
4. Verifikasi:
   - ✅ Form "Check In" tetap terisi
   - ✅ Form "Check Out" terisi dengan tanggal yang diklik
   - ✅ Total harga muncul (basis_price × nights)

---

## ✅ Test 6: Booking Submission

### Test Valid Booking

1. Di kalender, pilih 2 tanggal yang TIDAK merah (available)
2. Isi form:
   - Number of Guests: 2
   - Guest Name: John Test
   - Guest Email: john@test.com
   - Guest Phone: 081234567890
3. Klik "SUBMIT"
4. Verifikasi:
   - ✅ Halaman redirect ke payment
   - ✅ Booking muncul di database

**Check di database:**
```bash
php artisan tinker
>>> $booking = App\Models\Booking::latest()->first();
>>> $booking->check_in_date
>>> $booking->check_out_date
>>> $booking->total_price
```

### Test Invalid Booking (Conflict)

1. Di kalender, pilih 2 tanggal yang MERAH (booked)
2. Klik "SUBMIT"
3. Verifikasi:
   - ✅ Error message muncul: "Tanggal tidak tersedia"
   - ✅ Halaman stay di form (tidak redirect)
   - ✅ Form pre-filled dengan data yang diisi

---

## ✅ Test 7: Kalender Update

Setelah booking berhasil:

1. Kembali ke halaman villa detail
2. Verifikasi:
   - ✅ Tanggal yang baru di-book sekarang berwarna merah
   - ✅ Kalender ter-update otomatis
   - ✅ Status booking = 'pending' di database

---

## ✅ Test 8: Real-time API Refresh

### Test Manual AJAX

```javascript
// Di browser console, saat membuka villa detail:

async function testAPI() {
    const response = await fetch('/api/villa/1/availability');
    const data = await response.json();
    console.log('API Response:', data);
    console.log('Booked ranges:', data.booked_dates.length);
}

testAPI();
```

### Enable Auto-Refresh

Kalender akan auto-refresh setiap 30 detik jika kode ini ditambahkan di view:

```javascript
setInterval(async () => {
    const response = await fetch(`/api/villa/1/availability`);
    const data = await response.json();
    window.bookedDatesJson = data.booked_dates;
    generateAvailabilityCalendar();
    console.log('Calendar refreshed at', new Date().toLocaleTimeString());
}, 30000);
```

---

## ✅ Test 9: Database Index Performance

```bash
php artisan tinker

# Test query performance
>>> $start = microtime(true);
>>> $bookings = App\Models\Booking::where('villa_id', 1)
    ->whereIn('status', ['confirmed', 'pending'])
    ->where('check_out_date', '>=', now())
    ->get();
>>> $time = (microtime(true) - $start) * 1000;
>>> echo "Query time: {$time}ms";

# Expected: < 10ms (dengan index)
# Tanpa index: > 100ms (untuk 10,000+ records)
```

---

## ✅ Test 10: Validation Logic

### Frontend Validation

1. Open Developer Tools Console
2. Test di kalender:
   ```javascript
   // Test case: checkout tidak bisa sebelum checkin
   setCheckInDate('2026-01-30');
   setCheckOutDate('2026-01-29');  // Earlier than checkin
   
   // Should show alert: "Tanggal checkout harus setelah check-in!"
   ```

### Backend Validation

```bash
# Test case: overlap dengan booking existing
# Send request:

curl -X POST "http://localhost:8000/paymentlink" \
  -H "Content-Type: application/json" \
  -d "{
    \"villa_id\": 1,
    \"checkin\": \"2026-01-28\",
    \"checkout\": \"2026-02-01\",
    \"guests\": 2,
    \"name\": \"Test User\",
    \"email\": \"test@test.com\",
    \"phone\": \"081234567890\"
  }"

# Expected: Error response (status 422)
# {
#   "availability": "Villa ini tidak tersedia untuk tanggal yang dipilih..."
# }
```

---

## 🐛 Troubleshooting Tests

### Kalender tidak muncul

```javascript
// Check di console:
console.log('bookedDatesJson:', bookedDatesJson);
// Jika undefined, maka data tidak ter-pass dari backend
```

**Solution:**
```bash
# Check controller
grep -n "bookedDates" app/Http/Controllers/VillaController.php

# Check view
grep -n "@json" resources/views/guest/villa_detail.blade.php
```

### API returns 404

```bash
# Clear route cache
php artisan route:clear
php artisan route:cache

# Check routes
php artisan route:list | grep "availability"
```

### Booking tidak tersimpan

```bash
# Check database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Should not throw error

# Check migration
php artisan migrate:status | grep bookings
```

### Query terlalu lambat

```bash
# Check index
php artisan tinker
>>> DB::select('SHOW INDEXES FROM bookings');

# Should show indexes pada villa_id, status, check_in_date, check_out_date
```

---

## 📊 Complete Test Checklist

- [ ] Database query di Tinker bisa fetch data
- [ ] API GET /api/villa/1/availability return data
- [ ] API POST /api/villa/availability/validate return available
- [ ] API POST /api/villa/availability/validate return conflict
- [ ] Browser console shows bookedDatesJson
- [ ] Kalender render dengan warna merah & hijau
- [ ] Klik tanggal hijau mengisi form
- [ ] Submit booking valid berhasil & redirect
- [ ] Submit booking invalid show error
- [ ] Kalender update setelah booking baru
- [ ] Database index ada 4 buah
- [ ] Query time < 10ms

---

## 🎯 Summary

**Jika semua test di atas PASS ✅**, maka database connection untuk "Pilih Tanggal Menginap" sudah 100% berfungsi!

**Next steps:**
- [ ] Push ke production
- [ ] Monitor error logs
- [ ] Setup backup database
- [ ] Enable real-time WebSocket (optional)

---

