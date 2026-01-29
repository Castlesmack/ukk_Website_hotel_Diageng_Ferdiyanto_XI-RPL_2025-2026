# 🏗️ ARCHITECTURE: Database Connection untuk "Pilih Tanggal Menginap"

## Diagram Sistem

```
┌────────────────────────────────────────────────────────────┐
│                        DATABASE                             │
│                    (SQLite / MySQL)                         │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ bookings TABLE                                       │  │
│  ├──────────────────────────────────────────────────────┤  │
│  │ id | villa_id | check_in_date | check_out_date |    │  │
│  │ ---|----------|---------------|---------------|----- │  │
│  │ 1  | 1        | 2026-01-28   | 2026-01-31   |        │  │
│  │ 2  | 1        | 2026-02-05   | 2026-02-08   |        │  │
│  │ 3  | 2        | 2026-01-30   | 2026-02-02   |        │  │
│  └──────────────────────────────────────────────────────┘  │
│                          ▲                                  │
│                          │ Query                            │
│                          │ (SELECT where villa_id=1)        │
└──────────────────────────┼──────────────────────────────────┘
                           │
                           │
              ┌────────────┴─────────────┐
              │                          │
              ▼                          │
   ┌──────────────────────┐              │
   │ VillaController      │              │
   │ (Backend)            │              │
   │                      │              │
   │ detail($id)          │              │
   │ ├─ Find Villa        │              │
   │ ├─ Query Bookings    │◄─────────────┘
   │ │ ├─ villa_id = $id  │
   │ │ ├─ status IN       │
   │ │ │   ('confirmed',  │
   │ │ │    'pending')    │
   │ │ └─ order by date   │
   │ └─ Return $booked    │
   │     Dates to view    │
   └──────────┬───────────┘
              │
              │ Pass to Blade
              │ $bookedDates = [...]
              ▼
   ┌──────────────────────────────┐
   │ villa_detail.blade.php       │
   │ (Frontend Template)          │
   │                              │
   │ @json($bookedDates ?? [])    │
   │ → Convert to JSON            │
   │ → Send to Browser            │
   └──────────┬───────────────────┘
              │
              │ HTTP Response
              │ {booked_dates: [...]}
              ▼
   ┌──────────────────────────────┐
   │ Browser JavaScript           │
   │ (Client-side)                │
   │                              │
   │ const bookedDates =          │
   │   @json($bookedDates)        │
   │                              │
   │ → Parse booked dates         │
   │ → Store in Set for          │
   │   quick lookup               │
   │ → Generate Calendar HTML     │
   │ → Color cells based on       │
   │   availability               │
   └──────────┬───────────────────┘
              │
              │ HTML/CSS/JS Rendered
              ▼
   ┌──────────────────────────────┐
   │ Kalender di Browser          │
   │ (Visible to User)            │
   │                              │
   │ ┌────────────────────────┐   │
   │ │ Januari 2026           │   │
   │ ├────────────────────────┤   │
   │ │ M  S  S  R  K  J  S   │   │
   │ │[1][2][3][4][5][6][7]  │   │
   │ │ ...                    │   │
   │ │[28🔴][29🔴][30🔴][31🔴] │   │
   │ │ ...                    │   │
   │ └────────────────────────┘   │
   │                              │
   │ 🔴 = Booked (dari DB)         │
   │ 🟢 = Available                │
   └──────────┬───────────────────┘
              │
              │ User Interaction
              │ (Click dates)
              ▼
   ┌──────────────────────────────┐
   │ Form Submission              │
   │                              │
   │ Check-in:  2026-01-25       │
   │ Check-out: 2026-01-27       │
   │ Guests: 2                    │
   │ [SUBMIT]                     │
   └──────────┬───────────────────┘
              │
              │ POST /paymentlink
              │ (with CSRF token)
              ▼
   ┌──────────────────────────────┐
   │ VillaController              │
   │ storeBooking() (Backend)     │
   │                              │
   │ ✓ Validate input             │
   │ ✓ Check villa exists         │
   │ ✓ Query DB for conflicts     │
   └──────────┬───────────────────┘
              │
              │ if (conflict) {
              │   error response
              │ }
              │ else {
              │   create record
              │ }
              │
              ▼
   ┌──────────────────────────────┐
   │ Create New Booking Record    │
   │                              │
   │ INSERT INTO bookings (       │
   │   villa_id: 1,              │
   │   check_in_date: 2026-01-25,│
   │   check_out_date: 2026-01-27│
   │   nights: 2,                │
   │   total_price: 300000,      │
   │   status: 'pending',        │
   │   ...                       │
   │ )                           │
   └──────────┬───────────────────┘
              │
              │ Save to DB
              ▼
   ┌──────────────────────────────┐
   │ bookings TABLE (Updated)     │
   │                              │
   │ ... existing bookings ...   │
   │ NEW: 2026-01-25 ~ 01-27    │
   └──────────────────────────────┘
              │
              │
              ▼
   ┌──────────────────────────────┐
   │ Broadcast & Redirect         │
   │                              │
   │ → Fire OrderCreated event    │
   │   (for admin real-time)      │
   │                              │
   │ → Redirect to payment page   │
   └──────────────────────────────┘
```

---

## Data Flow dengan Tanggal

### State 1: Awal (Kalender pertama kali dimuat)

```
DATABASE QUERY:
┌─────────────────────────────────────┐
│ SELECT check_in_date,               │
│        check_out_date               │
│ FROM bookings                       │
│ WHERE villa_id = 1                  │
│   AND status IN ('confirmed','pending')
│ ORDER BY check_in_date;             │
└─────────────────────────────────────┘
         ↓
RESULT:
┌────────────────────────────────┐
│ check_in_date | check_out_date │
├───────────────┼────────────────┤
│ 2026-01-28    | 2026-01-31     │
│ 2026-02-05    | 2026-02-08     │
└────────────────────────────────┘
         ↓
JAVASCRIPT:
const bookedDates = [
  {check_in_date: "2026-01-28", check_out_date: "2026-01-31"},
  {check_in_date: "2026-02-05", check_out_date: "2026-02-08"}
]

// Convert to Set of individual dates
const bookedDatesSet = new Set([
  "2026-01-28", "2026-01-29", "2026-01-30",  // Range 28-31
  "2026-02-05", "2026-02-06", "2026-02-07"   // Range 5-8
])
         ↓
CALENDAR RENDER:
Januari 2026:
- 28: 🔴 (dalam bookedDatesSet)
- 29: 🔴 (dalam bookedDatesSet)
- 30: 🔴 (dalam bookedDatesSet)
- 31: 🟢 (tidak dalam bookedDatesSet)
- Lainnya: 🟢
```

---

### State 2: User Pilih Tanggal

```
USER ACTION:
┌─────────────────────────────────┐
│ Click tanggal 25 (hijau)        │
│ Click tanggal 27 (hijau)        │
└─────────────────────────────────┘
         ↓
FORM UPDATE:
<input name="checkin" value="2026-01-25">
<input name="checkout" value="2026-01-27">
         ↓
JAVASCRIPT VALIDATION:
✓ 2026-01-27 > 2026-01-25  ← OK
✓ No overlap with booked   ← OK
✓ Nights = 2               ← OK
         ↓
PRICE CALCULATION:
150000 (base_price) × 2 (nights) = 300000
         ↓
FORM DISPLAY UPDATE:
Check-in: Wed, 25 Jan 2026
Check-out: Fri, 27 Jan 2026
Total: Rp 300,000
```

---

### State 3: User Submit Booking

```
FORM SUBMISSION:
POST /paymentlink
Body: {
  villa_id: 1,
  checkin: "2026-01-25",
  checkout: "2026-01-27",
  guests: 2,
  name: "John Doe",
  email: "john@email.com",
  phone: "081234567890"
}
         ↓
BACKEND VALIDATION:
1. Parse dates: checkin → Carbon object
2. Query DB for conflict:
   SELECT * FROM bookings
   WHERE villa_id = 1
   AND status IN ('confirmed', 'pending')
   AND check_in_date < "2026-01-27"
   AND check_out_date > "2026-01-25"
   
   Result: EMPTY (no conflict)
         ↓
CREATE BOOKING:
INSERT INTO bookings (
  villa_id: 1,
  check_in_date: "2026-01-25",
  check_out_date: "2026-01-27",
  nights: 2,
  total_price: 300000,
  status: "pending",
  guest_name: "John Doe",
  ...
)
         ↓
DATABASE UPDATE:
Booking baru tersimpan dengan ID = 4
         ↓
EVENT BROADCAST:
OrderCreated event fired
└─ Admin dashboard bisa lihat booking baru
         ↓
USER REDIRECT:
Redirect to /payment/4
```

---

### State 4: Setelah Booking (Kalender Update)

```
USER BUKA KALENDER LAGI:
GET /villa/1 → VillaController::detail()
         ↓
DATABASE QUERY (sudah include booking baru):
SELECT check_in_date, check_out_date
FROM bookings
WHERE villa_id = 1
  AND status IN ('confirmed', 'pending')

Result sekarang:
┌────────────────────────────────┐
│ check_in_date | check_out_date │
├───────────────┼────────────────┤
│ 2026-01-25    | 2026-01-27     │ ← NEW
│ 2026-01-28    | 2026-01-31     │
│ 2026-02-05    | 2026-02-08     │
└────────────────────────────────┘
         ↓
JAVASCRIPT:
bookedDatesSet sekarang termasuk:
"2026-01-25", "2026-01-26" (NEW)
         ↓
CALENDAR RENDER:
Januari 2026 (Updated):
- 25: 🔴 (just booked!)
- 26: 🔴 (just booked!)
- 27: 🟢 (still available)
- 28-30: 🔴 (already booked)
- 31: 🟢
```

---

## API Architecture

```
┌─────────────────────────────────────────────────────┐
│              API ENDPOINTS                           │
└─────────────────────────────────────────────────────┘

1. GET /api/villa/{id}/availability
   ┌──────────────────────────────────────────┐
   │ BookingController::getAvailability()    │
   │ • Get villa by ID                       │
   │ • Query bookings for this villa         │
   │ • Return JSON with booked dates         │
   │ • NO AUTH required (public)             │
   └────────────┬─────────────────────────────┘
                │
                ▼
         Return JSON:
         {
           villa_id: 1,
           booked_dates: [...],
           timestamp: now()
         }

2. POST /api/villa/availability/validate
   ┌──────────────────────────────────────────┐
   │ BookingController::validateAvailability()│
   │ • Validate input dates                  │
   │ • Check conflict in DB                  │
   │ • Calculate price                       │
   │ • Return availability status            │
   │ • NO AUTH required (public)             │
   └────────────┬─────────────────────────────┘
                │
                ▼
         Return JSON:
         {
           available: true/false,
           nights: 3,
           total_price: 450000
         }

3. POST /api/villas/availability
   ┌──────────────────────────────────────────┐
   │ BookingController::checkMultiple()       │
   │ • Validate multiple villa IDs           │
   │ • Check availability for all            │
   │ • Return array of results               │
   │ • For search/filter functionality       │
   └────────────┬─────────────────────────────┘
                │
                ▼
         Return JSON array

4. GET /api/villa/{id}/stats
   ┌──────────────────────────────────────────┐
   │ BookingController::getBookingStats()    │
   │ • Calculate occupancy rate              │
   │ • Count bookings by status              │
   │ • Calculate total revenue               │
   │ • For admin analytics                   │
   └────────────┬─────────────────────────────┘
                │
                ▼
         Return JSON:
         {
           occupancy_rate: 75.5,
           confirmed: 38,
           pending: 7
         }
```

---

## Database Indexes

```
Sebelum Index:
SELECT check_in_date, check_out_date FROM bookings
WHERE villa_id = 1 AND status = 'confirmed'
ORDER BY check_in_date;

Database scan penuh: ~500ms untuk 10,000 rows

Sesudah Index:
CREATE INDEX idx_villa_id_status ON bookings(villa_id, status);

Query execution: ~5ms ← 100x lebih cepat!
```

---

## Error Handling Flow

```
User Submit Booking
    ↓
Backend Validation
    ├─ Dates invalid? → Error: "Tanggal tidak valid"
    ├─ Villa not found? → Error: "Villa tidak ditemukan"
    ├─ Villa inactive? → Error: "Villa tidak tersedia"
    ├─ Conflict detected? → Error: "Tanggal tidak tersedia"
    └─ All OK? → Create booking
         ↓
    Return to /villa/{id}
    Show error message to user
    Keep form pre-filled (old values)
    Kalender masih tampil untuk coba lagi
```

---

## Real-time Update Architecture (Optional)

```
User A memilih 25-27 Januari
              ↓
    POST /paymentlink
              ↓
    Booking tersimpan di DB
              ↓
    OrderCreated event broadcast
              ↓
┌─────────────────────────────┐
│ User B masih buka halaman   │
│ yang sama (villa detail)    │
│                             │
│ Refresh setiap 30 detik:    │
│ GET /api/villa/1/           │
│     availability            │
│                             │
│ Data update otomatis!       │
│ 25-27 sekarang warna merah  │
│                             │
│ Kalender di-generate ulang  │
│ User B langsung lihat       │
│ tanggal sudah dipesan       │
└─────────────────────────────┘
```

---

## Security Layers

```
User Input
    ↓
┌─────────────────────────────────┐
│ CLIENT-SIDE VALIDATION (JS)     │
│ • Check format                  │
│ • Check dates valid             │
│ • Check ranges make sense       │
│ ← Prevent bad submissions       │
└────────────┬────────────────────┘
             ↓
┌─────────────────────────────────┐
│ CSRF TOKEN CHECK                │
│ • @csrf in form                 │
│ • Laravel middleware verify     │
│ ← Prevent CSRF attacks          │
└────────────┬────────────────────┘
             ↓
┌─────────────────────────────────┐
│ SERVER-SIDE VALIDATION (PHP)    │
│ • Validate all inputs with      │
│   Request::validate()           │
│ • Check date formats            │
│ • Check villa exists            │
│ ← Prevent injection             │
└────────────┬────────────────────┘
             ↓
┌─────────────────────────────────┐
│ DATABASE QUERY VALIDATION       │
│ • Use Eloquent ORM              │
│ • Query conflict detection      │
│ • Atomic transaction            │
│ ← Prevent race conditions       │
└────────────┬────────────────────┘
             ↓
         Safe to Save
```

---

Dokumentasi ini menunjukkan bagaimana "Pilih Tanggal Menginap" terhubung penuh ke database! ✅

