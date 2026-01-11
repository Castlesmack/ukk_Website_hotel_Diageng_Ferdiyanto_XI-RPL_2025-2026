@extends('layouts.app')

@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: #666;
            text-decoration: none;
            margin-bottom: 30px;
            font-weight: 500;
            font-size: 14px;
        }

        .back-link:hover {
            color: #333;
        }

        .back-link span {
            margin-right: 8px;
        }

        /* Image Gallery */
        .image-section {
            margin-bottom: 40px;
        }

        .main-image {
            width: 100%;
            height: 450px;
            background: #f0f0f0;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
            border: 2px solid #333;
        }

        .main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumbnail-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
        }

        .thumbnail {
            width: 100%;
            height: 100px;
            background: #f0f0f0;
            border-radius: 4px;
            cursor: pointer;
            overflow: hidden;
            border: 3px solid transparent;
            transition: border-color 0.3s;
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumbnail.active {
            border-color: #007bff;
        }

        /* Description Section */
        .description-section {
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 40px;
        }

        .villa-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }

        .villa-meta {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .villa-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .villa-description {
            line-height: 1.6;
            color: #666;
            font-size: 14px;
        }

        /* Booking Section */
        .booking-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .form-card {
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            padding: 30px;
        }

        .form-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #333;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 90px;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: #0056b3;
        }

        .terms {
            font-size: 12px;
            color: #999;
            text-align: center;
            margin-top: 12px;
        }

        /* Order Summary */
        .summary-card {
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            padding: 30px;
            height: fit-content;
        }

        .summary-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 15px;
            color: #666;
        }

        .summary-row.divider {
            padding-bottom: 15px;
            border-bottom: 1px solid #e8e8e8;
            margin-bottom: 15px;
        }

        .summary-row.total {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-top: 10px;
        }

        .summary-total-value {
            font-size: 20px;
            font-weight: 700;
            color: #007bff;
        }

        .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .error-message ul {
            margin: 0;
            padding-left: 20px;
        }

        .error-message li {
            margin: 5px 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .booking-section {
                grid-template-columns: 1fr;
            }

            .summary-card {
                margin-top: 20px;
            }

            .main-image {
                height: 300px;
            }

            .villa-title {
                font-size: 22px;
            }

            .villa-meta {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>

    <div style="background: #f9f9f9; min-height: 100vh; padding: 30px 0;">
        <div class="container">
            <!-- Back Button -->
            <a href="{{ route('home') }}" class="back-link">
                <span>&larr;</span> Kembali ke Daftar Villa
            </a>

            <!-- Image Section -->
            @if ($villa->thumbnail_path || ($villa->images && count($villa->images) > 0))
                <div class="image-section">
                    <div class="main-image">
                        <img id="mainImage" src="{{ asset($villa->thumbnail_path) }}" alt="{{ $villa->name }}">
                    </div>
                    @if ($villa->images && count($villa->images) > 0)
                        <div class="thumbnail-gallery">
                            @if ($villa->thumbnail_path)
                                <div class="thumbnail active" onclick="changeImage('{{ asset($villa->thumbnail_path) }}', this)">
                                    <img src="{{ asset($villa->thumbnail_path) }}" alt="Thumbnail">
                                </div>
                            @endif
                            @foreach ($villa->images as $image)
                                <div class="thumbnail" onclick="changeImage('{{ asset($image) }}', this)">
                                    <img src="{{ asset($image) }}" alt="Gallery image">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <!-- Description Section -->
            <div class="description-section">
                <h1 class="villa-title">{{ $villa->name }}</h1>
                <div class="villa-meta">
                    <div class="villa-meta-item">
                        <span>👥 Kapasitas:</span>
                        <strong>{{ $villa->capacity }} orang</strong>
                    </div>
                    <div class="villa-meta-item">
                        <span>🛏️ Kamar:</span>
                        <strong>{{ $villa->rooms_total }} kamar</strong>
                    </div>
                    <div class="villa-meta-item">
                        <span>💰 Harga:</span>
                        <strong>Rp {{ number_format($villa->base_price, 0, ',', '.') }}/malam</strong>
                    </div>
                </div>
                @if ($villa->description)
                    <div class="villa-description">
                        {!! nl2br(e($villa->description)) !!}
                    </div>
                @endif
            </div>

            <!-- Booking Section -->
            <div class="booking-section">
                <!-- Left: Booking Form -->
                <div class="form-card">
                    <h2 class="form-title">Booking Details</h2>

                    @if($errors->any())
                        <div class="error-message">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('guest.store.booking') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="villa_id" value="{{ $villa->id }}">

                        <!-- Check-in -->
                        <div class="form-group">
                            <label>Check In:</label>
                            <input type="date" name="checkin" value="{{ old('checkin') }}" required id="checkinInput">
                        </div>

                        <!-- Check-out -->
                        <div class="form-group">
                            <label>Check Out:</label>
                            <input type="date" name="checkout" value="{{ old('checkout') }}" required id="checkoutInput">
                        </div>

                        <!-- Number of Guests -->
                        <div class="form-group">
                            <label>Number of Guests:</label>
                            <input type="number" name="guests" min="1" max="{{ $villa->capacity ?? 10 }}"
                                value="{{ old('guests', 1) }}" required>
                        </div>

                        <!-- Guest Name -->
                        <div class="form-group">
                            <label>Guest Name:</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" required
                                placeholder="Test User">
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required
                                placeholder="user@example.com">
                        </div>

                        <!-- Phone Number -->
                        <div class="form-group">
                            <label>Phone Number:</label>
                            <input type="tel" name="phone" value="{{ old('phone', auth()->user()?->phone) }}" required
                                placeholder="08123456789">
                        </div>

                        <!-- Special Requests -->
                        <div class="form-group">
                            <label>Special Requests:</label>
                            <textarea name="special_requests"
                                placeholder="Any special requests or notes...">{{ old('special_requests') }}</textarea>
                        </div>

                        <button type="submit" class="submit-btn">Continue to Payment</button>
                        <p class="terms">By booking, you agree to our terms & conditions</p>
                    </form>
                </div>

                <!-- Right: Order Summary -->
                <div class="summary-card">
                    <h3 class="summary-title">Order Summary</h3>

                    <!-- Villa Name -->
                    <div class="summary-row">
                        <span>Villa: <strong>{{ $villa->name }}</strong></span>
                    </div>

                    <!-- Check-in & Check-out -->
                    <div class="summary-row">
                        <span>Check in:</span>
                        <span class="checkin-display">[Select date]</span>
                    </div>
                    <div class="summary-row divider">
                        <span>Check out:</span>
                        <span class="checkout-display">[Select date]</span>
                    </div>

                    <!-- Duration & Price -->
                    <div class="summary-row">
                        <span>Guests:</span>
                        <span>2</span>
                    </div>
                    <div class="summary-row divider">
                        <span>Price per night:</span>
                        <span>Rp {{ number_format($villa->base_price, 0, ',', '.') }}</span>
                    </div>

                    <!-- Total -->
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span class="summary-total-value total-display">[Will be calculated once dates are selected]</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const basePrice = {{ $villa->base_price }};

        function updateSummary() {
            const checkinInput = document.getElementById('checkinInput').value;
            const checkoutInput = document.getElementById('checkoutInput').value;

            // Format and display dates
            if (checkinInput) {
                const checkinDate = new Date(checkinInput + 'T00:00:00');
                document.querySelector('.checkin-display').textContent =
                    checkinDate.toLocaleDateString('id-ID', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
            }

            if (checkoutInput) {
                const checkoutDate = new Date(checkoutInput + 'T00:00:00');
                document.querySelector('.checkout-display').textContent =
                    checkoutDate.toLocaleDateString('id-ID', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
            }

            // Calculate duration and total
            if (checkinInput && checkoutInput) {
                const checkinDate = new Date(checkinInput + 'T00:00:00');
                const checkoutDate = new Date(checkoutInput + 'T00:00:00');
                const duration = Math.ceil((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24));

                if (duration > 0) {
                    const total = basePrice * duration;
                    document.querySelector('.total-display').textContent =
                        `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
                } else {
                    document.querySelector('.total-display').textContent = '[Invalid date range]';
                }
            }
        }

        // Update summary on date change
        document.getElementById('checkinInput').addEventListener('change', updateSummary);
        document.getElementById('checkoutInput').addEventListener('change', updateSummary);

        // Image gallery function
        function changeImage(src, element) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
            element.classList.add('active');
        }
    </script>
@endsection