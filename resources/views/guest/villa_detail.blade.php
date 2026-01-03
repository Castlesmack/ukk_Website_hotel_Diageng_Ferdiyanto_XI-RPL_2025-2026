@extends('layouts.app')

@section('title', 'Villa Detail')

@push('styles')
    <style>
        .villa-detail {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .main-content {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            background: white;
        }

        .photo-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .photo-main {
            height: 200px;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }

        .photo-thumbs {
            display: grid;
            grid-template-rows: repeat(3, 1fr);
            gap: 10px;
        }

        .photo-thumb {
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
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