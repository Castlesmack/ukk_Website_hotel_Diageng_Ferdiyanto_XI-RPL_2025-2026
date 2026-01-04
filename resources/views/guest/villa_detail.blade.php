@extends('layouts.app')

@section('title', $villa->name)

@push('styles')
    <style>
        .back-link {
            display: inline-block;
            padding: 10px 15px;
            background: #e9ecef;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .back-link:hover {
            background: #dee2e6;
        }

        .villa-detail-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .villa-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .villa-title {
            font-size: 28px;
            font-weight: 600;
            margin: 0 0 10px;
            color: #333;
        }

        .villa-address {
            font-size: 14px;
            color: #6c757d;
            margin: 0;
        }

        .villa-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 0;
        }

        .villa-main {
            padding: 30px;
            border-right: 1px solid #e9ecef;
        }

        .photo-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 12px;
            margin-bottom: 30px;
        }

        .photo-main {
            background: #f8f9fa;
            border-radius: 8px;
            height: 300px;
            overflow: hidden;
            cursor: pointer;
            grid-row: span 2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 16px;
            transition: transform 0.3s;
        }

        .photo-main:hover {
            transform: scale(1.02);
        }

        .photo-thumb {
            background: #f8f9fa;
            border-radius: 8px;
            height: 140px;
            overflow: hidden;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 13px;
            transition: all 0.3s;
        }

        .photo-thumb:hover {
            transform: scale(1.03);
        }

        .facilities {
            margin-bottom: 30px;
        }

        .facilities h3 {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 15px;
            color: #333;
        }

        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .facility-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .facility-label {
            font-size: 13px;
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .facility-value {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .description {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #e9ecef;
        }

        .description h3 {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 15px;
            color: #333;
        }

        .description ul {
            margin: 0;
            padding-left: 20px;
        }

        .description li {
            margin-bottom: 10px;
            color: #495057;
            line-height: 1.6;
        }

        .villa-sidebar {
            padding: 30px;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .price-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
        }

        .price {
            font-size: 32px;
            font-weight: 700;
            color: #007bff;
            margin-bottom: 5px;
        }

        .price-label {
            font-size: 13px;
            color: #6c757d;
        }

        .booking-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .total-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border-top: 2px solid #e9ecef;
        }

        .total-label {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 8px;
        }

        .total-price {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }

        .order-btn {
            width: 100%;
            padding: 14px;
            background: #ff6b6b;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .order-btn:hover {
            background: #ff5252;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            margin: 5% auto;
            width: 80%;
            max-width: 900px;
        }

        .modal img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .close {
            position: absolute;
            right: 30px;
            top: 30px;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            background: rgba(0, 0, 0, 0.5);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .close:hover {
            background: rgba(0, 0, 0, 0.8);
        }
    </style>
@endpush

@section('content')
    <a href="/villa" class="back-link">← Back</a>

    <div class="villa-detail-container">
        <div class="villa-header">
            <h1 class="villa-title">{{ $villa->name }}</h1>
            <p class="villa-address">{{ $villa->description ?? 'Jl. Villa Address' }}</p>
        </div>

        <div class="villa-content">
            <div class="villa-main">
                <!-- Photo Gallery -->
                <div class="photo-grid">
                    <div class="photo-main" onclick="openModal()">[Main Photo]</div>
                    <div class="photo-thumb" onclick="openModal()">[Photo 1]</div>
                    <div class="photo-thumb" onclick="openModal()">[Photo 2]</div>
                </div>

                <!-- Facilities -->
                <div class="facilities">
                    <h3>Facilities</h3>
                    <div class="facilities-grid">
                        <div class="facility-item">
                            <span class="facility-label">Type</span>
                            <span class="facility-value">{{ $villa->rooms_total ?? 'N/A' }} Rooms</span>
                        </div>
                        <div class="facility-item">
                            <span class="facility-label">Capacity</span>
                            <span class="facility-value">{{ $villa->capacity }} Guests</span>
                        </div>
                        <div class="facility-item">
                            <span class="facility-label">Availability</span>
                            <span class="facility-value">{{ ucfirst($villa->status) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="description">
                    <h3>Deskripsi Akomodasi</h3>
                    <ul>
                        <li>Akomodasi yang nyaman dengan fasilitas lengkap</li>
                        <li>Tersedia area bermain anak yang aman dan tertata</li>
                        <li>Terdapat taman yang indah dengan pemandangan yang menakjubkan</li>
                        <li>Dapur lengkap dengan semua perlengkapan memasak</li>
                        <li>Area parkir yang luas dan aman untuk kendaraan</li>
                        <li>Ruang tamu yang nyaman untuk bersantai bersama keluarga</li>
                    </ul>
                </div>
            </div>

            <!-- Booking Sidebar -->
            <aside class="villa-sidebar">
                <div class="price-section">
                    <div class="price">Rp {{ number_format($villa->base_price, 0, ',', '.') }}</div>
                    <div class="price-label">Per Night</div>
                </div>

                <div class="booking-form">
                    <form id="bookingForm">
                        <div class="form-group">
                            <label for="checkin">Check-in</label>
                            <input type="date" id="checkin" name="checkin" required>
                        </div>
                        <div class="form-group">
                            <label for="checkout">Check-out</label>
                            <input type="date" id="checkout" name="checkout" required>
                        </div>
                        <div class="form-group">
                            <label for="guests">Guests</label>
                            <select id="guests" name="guests" required>
                                <option value="">Select guests</option>
                                @for ($i = 1; $i <= $villa->capacity; $i++)
                                    <option value="{{ $i }}">{{ $i }} Guest{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>
                    </form>
                </div>

                <div class="total-section">
                    <div class="total-label">Total</div>
                    <div class="total-price">Rp <span
                            id="totalPrice">{{ number_format($villa->base_price, 0, ',', '.') }}</span></div>
                    <button class="order-btn" onclick="bookNow({{ $villa->id }})">Order</button>
                </div>
            </aside>
        </div>
    </div>

    <!-- Photo Modal -->
    <div id="photoModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="Villa Photo">
        </div>
    </div>

    <script>
        // Set min check-in to today
        document.getElementById('checkin').min = new Date().toISOString().split('T')[0];
        document.getElementById('checkout').min = new Date().toISOString().split('T')[0];

        // Calculate total on date/guest change
        document.getElementById('checkin').addEventListener('change', calculateTotal);
        document.getElementById('checkout').addEventListener('change', calculateTotal);

        function calculateTotal() {
            const checkin = new Date(document.getElementById('checkin').value);
            const checkout = new Date(document.getElementById('checkout').value);
            const pricePerNight = {{ $villa->base_price }};

            if (checkin && checkout && checkout > checkin) {
                const nights = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
                const total = nights * pricePerNight;
                document.getElementById('totalPrice').textContent = new Intl.NumberFormat('id-ID').format(total);
            }
        }

        function bookNow(villaId) {
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;
            const guests = document.getElementById('guests').value;

            if (!checkin || !checkout || !guests) {
                alert('Please fill in all fields');
                return;
            }

            const url = `/reservation/form?villa_id=${villaId}&checkin=${checkin}&checkout=${checkout}&guests=${guests}`;
            window.location.href = url;
        }

        function openModal() {
            document.getElementById('photoModal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('photoModal').style.display = "none";
        }

        window.onclick = function (event) {
            const modal = document.getElementById('photoModal');
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }
    </script>
@endsection
cursor: pointer;
transition: background 0.3s;
}

.photo-thumb:hover {
background: #e9ecef;
}

.see-all {
background: #e9ecef;
text-align: center;
display: flex;
align-items: center;
justify-content: center;
cursor: pointer;
transition: background 0.3s;
}

.see-all:hover {
background: #dee2e6;
position: fixed;
z-index: 1000;
left: 0;
top: 0;
width: 100%;
height: 100%;
background-color: rgba(0, 0, 0, 0.8);
}

.modal-content {
margin: 5% auto;
max-width: 80%;
max-height: 80%;
display: flex;
justify-content: center;
align-items: center;
}

.modal img {
max-width: 100%;
max-height: 100%;
border-radius: 8px;
}

.close {
position: absolute;
top: 20px;
right: 30px;
color: white;
font-size: 40px;
font-weight: bold;
cursor: pointer;
}

.facilities {
margin-top: 20px;
}

.facilities h4 {
margin-bottom: 10px;
color: #007bff;
}

.facilities ul {
list-style: none;
padding: 0;
}

.facilities li {
padding: 5px 0;
}

.booking-sidebar {
border: 1px solid #e9ecef;
border-radius: 8px;
padding: 20px;
background: white;
height: fit-content;
}

.price {
font-size: 24px;
font-weight: bold;
color: #007bff;
margin-bottom: 20px;
}

.date-inputs {
margin-bottom: 20px;
}

.date-inputs div {
margin-bottom: 10px;
}

.date-inputs label {
display: block;
margin-bottom: 5px;
font-weight: 500;
}

.date-inputs input {
width: 100%;
padding: 10px;
border: 1px solid #ccc;
border-radius: 4px;
}

.guests-input {
margin-bottom: 20px;
}

.guests-input label {
display: block;
margin-bottom: 5px;
font-weight: 500;
}

.guests-input input {
width: 100%;
padding: 10px;
border: 1px solid #ccc;
border-radius: 4px;
}

.book-btn {
width: 100%;
padding: 12px;
background: #007bff;
color: white;
border: none;
border-radius: 4px;
font-size: 16px;
cursor: pointer;
}

.book-btn:hover {
background: #0056b3;
}

.modal {
display: none;
position: fixed;
z-index: 1000;
left: 0;
top: 0;
width: 100%;
height: 100%;
background-color: rgba(0, 0, 0, 0.8);
}

.modal-content {
position: relative;
margin: 5% auto;
max-width: 800px;
max-height: 80vh;
}

.modal img {
width: 100%;
height: auto;
border-radius: 8px;
}

.close {
position: absolute;
top: -40px;
right: 0;
color: white;
font-size: 35px;
font-weight: bold;
cursor: pointer;
}

.photo-grid-modal {
display: grid;
grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
gap: 10px;
margin-top: 20px;
}

.photo-item {
cursor: pointer;
border-radius: 8px;
overflow: hidden;
}

.photo-item img {
width: 100%;
height: 100px;
object-fit: cover;
transition: transform 0.3s;
}

.photo-item:hover img {
transform: scale(1.05);
}

text-decoration: none;
margin-bottom: 20px;
display: inline-block;
}

.back-link:hover {
text-decoration: underline;
}
</style>
@endpush

@section('content')
    <a href="/" class="back-link">&larr; Back to Home</a>
    <h2>Villa Tipe - 0608</h2>

    <div class="villa-detail">
        <div class="main-content">
            <div class="photo-grid">
                <div class="photo-main">[Main Photo]</div>
                <div class="photo-thumbs">
                    <div class="photo-thumb">[Photo 1]</div>
                    <div class="photo-thumb">[Photo 2]</div>
                    <div class="see-all" onclick="openModal()">See all Photos</div>
                </div>
            </div>

            <div class="facilities">
                <h4>Facilities</h4>
                <ul>
                    <li>✓ WiFi</li>
                    <li>✓ AC</li>
                    <li>✓ Swimming Pool</li>
                    <li>✓ Kitchen</li>
                    <li>✓ Parking</li>
                </ul>
            </div>
        </div>

        <aside class="booking-sidebar">
            <div class="price">Rp {{ number_format($villa->price, 0, ',', '.') }} / night</div>

            <div class="date-inputs">
                <div>
                    <label>Check-in</label>
                    <input type="date" id="checkin" required>
                </div>
                <div>
                    <label>Check-out</label>
                    <input type="date" id="checkout" required>
                </div>
            </div>

            <div class="guests-input">
                <label>Number of Guests</label>
                <input type="number" id="guests" value="2" min="1" max="10" required>
            </div>

            <button class="book-btn" onclick="bookNow({{ $villa->id }})">Book Now</button>
        </aside>
    </div>

    <!-- Photo Modal -->
    <div id="photoModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="Villa Photo">
        </div>
    </div>

    <script>
        function bookNow(villaId) {
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;
            const guests = document.getElementById('guests').value;

            if (!checkin || !checkout) {
                alert('Please select check-in and check-out dates');
                return;
            }

            const url = `/reservation/form?villa_id=${villaId}&checkin=${checkin}&checkout=${checkout}&guests=${guests}`;
            window.location.href = url;
        }

        function openModal() {
            const modal = document.getElementById('photoModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = "block";
            modalImg.src = "/storage/villa/sample-1.jpg"; // Placeholder image
        }

        function closeModal() {
            document.getElementById('photoModal').style.display = "none";
        }

        // Close modal when clicking outside
        window.onclick = function (event) {
            const modal = document.getElementById('photoModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
@endsection