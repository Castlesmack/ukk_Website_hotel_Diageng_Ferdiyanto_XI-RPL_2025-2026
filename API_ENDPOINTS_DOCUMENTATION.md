# 🚀 API Endpoints untuk "Pilih Tanggal Menginap"

Database sekarang sudah terhubung dengan lengkap dengan API endpoints untuk real-time availability checking.

## 📡 Available Endpoints

### 1. GET - Dapatkan Data Kalender Ketersediaan
```
GET /api/villa/{id}/availability
```

**Deskripsi**: Mengambil semua tanggal yang sudah dipesan untuk villa tertentu

**Parameters**:
- `id` (required) - Villa ID

**Response**:
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

**Contoh CURL**:
```bash
curl "http://localhost:8000/api/villa/1/availability"
```

**Contoh JavaScript**:
```javascript
async function getAvailability(villaId) {
    const response = await fetch(`/api/villa/${villaId}/availability`);
    const data = await response.json();
    console.log('Booked dates:', data.booked_dates);
    return data;
}

// Usage
getAvailability(1).then(data => {
    console.log('Total booked ranges:', data.total_booked_ranges);
});
```

---

### 2. POST - Validasi Tanggal yang Dipilih
```
POST /api/villa/availability/validate
```

**Deskripsi**: Memvalidasi apakah tanggal check-in dan check-out tersedia untuk villa

**Request Body**:
```json
{
  "villa_id": 1,
  "check_in": "2026-01-29",
  "check_out": "2026-02-01"
}
```

**Response - Jika Tersedia**:
```json
{
  "success": true,
  "available": true,
  "villa_id": 1,
  "villa_name": "Villa Mewah Sunset",
  "check_in": "2026-01-29",
  "check_out": "2026-02-01",
  "nights": 3,
  "base_price": 150000,
  "total_price": 450000,
  "message": "Tanggal tersedia untuk booking",
  "conflicting_booking": null
}
```

**Response - Jika Tidak Tersedia**:
```json
{
  "success": true,
  "available": false,
  "villa_id": 1,
  "villa_name": "Villa Mewah Sunset",
  "check_in": "2026-01-28",
  "check_out": "2026-02-01",
  "nights": 4,
  "base_price": 150000,
  "total_price": 600000,
  "message": "Tanggal tidak tersedia - ada booking lain",
  "conflicting_booking": {
    "check_in_date": "2026-01-28",
    "check_out_date": "2026-01-31",
    "status": "confirmed"
  }
}
```

**Contoh CURL**:
```bash
curl -X POST "http://localhost:8000/api/villa/availability/validate" \
  -H "Content-Type: application/json" \
  -d '{"villa_id": 1, "check_in": "2026-01-29", "check_out": "2026-02-01"}'
```

**Contoh JavaScript**:
```javascript
async function validateDates(villaId, checkIn, checkOut) {
    const response = await fetch('/api/villa/availability/validate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            villa_id: villaId,
            check_in: checkIn,
            check_out: checkOut
        })
    });
    
    const data = await response.json();
    
    if (!data.available) {
        alert(`Tanggal tidak tersedia. Sudah dipesan dari ${data.conflicting_booking.check_in_date} sampai ${data.conflicting_booking.check_out_date}`);
        return false;
    }
    
    console.log(`Rp ${new Intl.NumberFormat('id-ID').format(data.total_price)} untuk ${data.nights} malam`);
    return true;
}

// Usage
validateDates(1, '2026-01-29', '2026-02-01').then(isAvailable => {
    if (isAvailable) {
        document.getElementById('villaBookingForm').submit();
    }
});
```

---

### 3. POST - Cek Ketersediaan Multiple Villa
```
POST /api/villas/availability
```

**Deskripsi**: Mengecek ketersediaan untuk multiple villa sekaligus (untuk search/filter)

**Request Body**:
```json
{
  "villa_ids": [1, 2, 3],
  "check_in": "2026-01-29",
  "check_out": "2026-02-01"
}
```

**Response**:
```json
{
  "success": true,
  "check_in": "2026-01-29",
  "check_out": "2026-02-01",
  "total_villas_checked": 3,
  "villas_available": 2,
  "availability": [
    {
      "villa_id": 1,
      "villa_name": "Villa Mewah Sunset",
      "available": true,
      "base_price": 150000
    },
    {
      "villa_id": 2,
      "villa_name": "Villa Indah Ocean",
      "available": false,
      "base_price": 200000
    },
    {
      "villa_id": 3,
      "villa_name": "Villa Tenang Garden",
      "available": true,
      "base_price": 120000
    }
  ]
}
```

**Contoh JavaScript**:
```javascript
async function searchAvailableVillas(villaIds, checkIn, checkOut) {
    const response = await fetch('/api/villas/availability', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            villa_ids: villaIds,
            check_in: checkIn,
            check_out: checkOut
        })
    });
    
    const data = await response.json();
    const availableVillas = data.availability.filter(v => v.available);
    
    console.log(`${availableVillas.length} dari ${data.total_villas_checked} villa tersedia`);
    return availableVillas;
}
```

---

### 4. GET - Statistik Booking Villa
```
GET /api/villa/{id}/stats
```

**Deskripsi**: Mendapatkan statistik booking untuk villa (admin analytics)

**Response**:
```json
{
  "success": true,
  "villa_id": 1,
  "villa_name": "Villa Mewah Sunset",
  "statistics": {
    "total_bookings": 45,
    "confirmed_bookings": 38,
    "pending_bookings": 7,
    "total_revenue": 5700000,
    "occupancy_rate": 75.48
  }
}
```

**Contoh JavaScript**:
```javascript
async function getVillaStats(villaId) {
    const response = await fetch(`/api/villa/${villaId}/stats`);
    const data = await response.json();
    
    const stats = data.statistics;
    console.log(`Occupancy: ${stats.occupancy_rate}%`);
    console.log(`Revenue: Rp ${new Intl.NumberFormat('id-ID').format(stats.total_revenue)}`);
    
    return stats;
}
```

---

## 🔧 Integrasi dengan View

### Update Calendar Real-time

Di `resources/views/guest/villa_detail.blade.php`, tambahkan JavaScript untuk refresh data setiap beberapa saat:

```javascript
<script>
    // Ambil villa ID dari form
    const villaId = document.getElementById('villaBookingVillaId').value;
    
    // Refresh availability setiap 30 detik
    setInterval(async () => {
        try {
            const response = await fetch(`/api/villa/${villaId}/availability`);
            const data = await response.json();
            
            // Update booked dates
            window.bookedDatesJson = data.booked_dates;
            
            // Re-generate calendar dengan data terbaru
            generateAvailabilityCalendar();
            
            console.log('Calendar updated:', data.total_booked_ranges, 'booked ranges');
        } catch (error) {
            console.error('Error updating calendar:', error);
        }
    }, 30000); // 30 detik
    
    // Optional: Real-time validation saat user pick dates
    document.getElementById('villaBookingCheckOut').addEventListener('change', async function() {
        const checkIn = document.getElementById('villaBookingCheckIn').value;
        const checkOut = this.value;
        
        if (!checkIn || !checkOut) return;
        
        try {
            const response = await fetch('/api/villa/availability/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    villa_id: villaId,
                    check_in: checkIn,
                    check_out: checkOut
                })
            });
            
            const data = await response.json();
            
            if (data.available) {
                // Update harga total
                document.querySelector('.total-display').style.color = '#4caf50';
                document.querySelector('.total-display').textContent = 
                    `✓ Rp ${new Intl.NumberFormat('id-ID').format(data.total_price)}`;
            } else {
                // Tampilkan error
                document.querySelector('.total-display').style.color = '#dc3545';
                document.querySelector('.total-display').textContent = 
                    '❌ Tanggal tidak tersedia';
            }
        } catch (error) {
            console.error('Validation error:', error);
        }
    });
</script>
```

---

## 🧪 Testing APIs

### 1. Test dengan Postman atau cURL

```bash
# Test GET availability
curl "http://localhost:8000/api/villa/1/availability"

# Test POST validate
curl -X POST "http://localhost:8000/api/villa/availability/validate" \
  -H "Content-Type: application/json" \
  -d '{"villa_id": 1, "check_in": "2026-01-29", "check_out": "2026-02-01"}'

# Test multiple villas
curl -X POST "http://localhost:8000/api/villas/availability" \
  -H "Content-Type: application/json" \
  -d '{"villa_ids": [1, 2, 3], "check_in": "2026-01-29", "check_out": "2026-02-01"}'

# Test stats
curl "http://localhost:8000/api/villa/1/stats"
```

### 2. Test di Browser Console

```javascript
// Test GET
fetch('/api/villa/1/availability')
    .then(r => r.json())
    .then(d => console.log(d));

// Test POST
fetch('/api/villa/availability/validate', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({villa_id: 1, check_in: '2026-01-29', check_out: '2026-02-01'})
})
.then(r => r.json())
.then(d => console.log(d));
```

### 3. Test di Artisan Tinker

```bash
php artisan tinker

# Test query
>>> App\Models\Booking::where('villa_id', 1)->where('status', 'confirmed')->get();

# Test stats
>>> App\Models\Booking::where('villa_id', 1)->sum('total_price');
```

---

## 📋 Deployment Checklist

- [ ] Migration jalankan: `php artisan migrate`
- [ ] Route cache clear: `php artisan route:cache`
- [ ] Config cache clear: `php artisan config:cache`
- [ ] Test semua endpoints dengan Postman/curl
- [ ] Test form submission dengan booking baru
- [ ] Cek database - booking tersimpan dengan benar
- [ ] Refresh kalender setelah booking baru (AJAX)
- [ ] Monitor error logs: `tail -f storage/logs/laravel.log`

---

## 🔐 Security Notes

1. **Validasi Backend** - Selalu validasi di server side, jangan percaya client
2. **Rate Limiting** - Tambah rate limiter untuk API endpoints jika dibutuhkan
3. **CSRF Protection** - POST requests harus include CSRF token (sudah built-in di Laravel)
4. **Input Sanitization** - Gunakan Validator untuk semua input

---

## 🚀 Optimization Tips

1. **Caching** - Cache hasil availability untuk villa yang jarang berubah
   ```php
   Cache::remember("villa.{$id}.availability", 3600, function() {
       return Booking::where('villa_id', $id)->get();
   });
   ```

2. **Database Index** - Sudah ditambah di migration untuk query cepat

3. **Pagination** - Untuk calendar bulan lama, pertimbangkan pagination

4. **AJAX Debounce** - Jangan call API terlalu sering
   ```javascript
   let timeout;
   input.addEventListener('change', () => {
       clearTimeout(timeout);
       timeout = setTimeout(() => {
           // Call API
       }, 500);
   });
   ```

---

## 📞 Troubleshooting

### API returns 404
```bash
# Clear route cache
php artisan route:clear
php artisan route:cache
```

### Slow queries
```bash
# Check if indexes created
php artisan migrate
# Check with: SHOW INDEXES FROM bookings;
```

### CORS errors
```php
# Tambah di config/cors.php jika diperlukan
'allowed_origins' => ['*'],
'allowed_methods' => ['*'],
```

---

Semua API endpoints sudah siap digunakan! ✅

