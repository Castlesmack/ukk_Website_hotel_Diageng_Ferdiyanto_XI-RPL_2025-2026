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
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .main-image-container {
            grid-column: 1;
            grid-row: 1 / 3;
        }

        .main-image {
            width: 100%;
            height: 300px;
            background: #FAF2E8;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid rgba(51, 51, 51, 0.75);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            transition: transform 0.3s ease;
        }

        .main-image:hover {
            transform: scale(1.02);
        }

        .main-image:hover::after {
            content: 'click to zoom in';
            position: absolute;
            font-size: 18px;
            font-weight: 600;
            color: white;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 5;
            background: rgba(0, 0, 0, 0.4);
            padding: 10px 20px;
            border-radius: 6px;
        }

        .main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Lightbox Modal */
        .lightbox-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            animation: fadeIn 0.3s ease;
        }

        .lightbox-modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox-content {
            position: relative;
            width: 90%;
            max-width: 1000px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .lightbox-main-image {
            width: 100%;
            height: 70vh;
            object-fit: contain;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .lightbox-thumbnails {
            display: flex;
            gap: 10px;
            justify-content: center;
            overflow-x: auto;
            width: 100%;
            padding: 10px 0;
            max-width: 900px;
        }

        .lightbox-thumbnail {
            width: 80px;
            height: 60px;
            min-width: 80px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            cursor: pointer;
            overflow: hidden;
            border: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .lightbox-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .lightbox-thumbnail.active {
            border-color: #fff;
            box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.5);
        }

        .lightbox-thumbnail:hover {
            border-color: #fff;
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 20px;
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
            transition: all 0.3s ease;
            z-index: 1010;
        }

        .lightbox-close:hover {
            background: rgba(0, 0, 0, 0.8);
            transform: scale(1.1);
        }

        .lightbox-nav {
            position: absolute;
            top: 50%;
            width: 100%;
            transform: translateY(-50%);
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1005;
        }

        .lightbox-prev,
        .lightbox-next {
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            font-size: 32px;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .lightbox-prev:hover,
        .lightbox-next:hover {
            background: rgba(0, 0, 0, 0.9);
            transform: scale(1.1);
        }

        .lightbox-counter {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 14px;
            background: rgba(0, 0, 0, 0.6);
            padding: 8px 16px;
            border-radius: 20px;
            z-index: 1005;
            pointer-events: none;
        }

        .thumbnail-gallery {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            padding: 0;
            margin: 0;
            grid-column: 2;
            grid-row: 1 / 3;
            height: fit-content;
        }

        .thumbnail {
            position: relative;
            width: 100%;
            height: 140px;
            background: #FAF2E8;
            border-radius: 8px;
            cursor: pointer;
            overflow: hidden;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .thumbnail.hidden-photo {
            display: none;
        }

        .thumbnail.hidden-photo.show-all {
            display: block;
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumbnail.active {
            border-color: rgba(90, 74, 66, 0.75);
            box-shadow: 0 0 0 2px rgba(90, 74, 66, 0.3);
        }

        .thumbnail:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .see-all-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 600;
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 8px;
        }

        .thumbnail:hover .see-all-overlay {
            opacity: 1;
        }

        /* Responsive Grid */
        @media (max-width: 1024px) {
            .image-section {
                grid-template-columns: 1fr;
            }

            .main-image-container {
                grid-column: 1;
            }

            .thumbnail-gallery {
                grid-column: 1;
                grid-row: auto;
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 768px) {
            .image-section {
                grid-template-columns: 1fr;
            }

            .thumbnail-gallery {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 480px) {
            .image-section {
                grid-template-columns: 1fr;
            }

            .main-image {
                height: 300px;
            }

            .thumbnail-gallery {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Description Section */
        .description-section {
            background: #fff;
            border: 1px solid rgba(232, 232, 232, 0.5);
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
            border: 1px solid rgba(232, 232, 232, 0.75);
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
            border: 1px solid rgba(221, 221, 221, 0.75);
            border-radius: 4px;
            font-size: 13px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: rgba(90, 74, 66, 0.5);
            box-shadow: 0 0 0 3px rgba(90, 74, 66, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 90px;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #5a4a42;
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
            background: #3d2f28;
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
            border: 1px solid rgba(232, 232, 232, 0.75);
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
            border-bottom: 1px solid rgba(232, 232, 232, 0.75);
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
            color: #f05b4f;
        }

        .error-message {
            background: #f8d7da;
            border: 1px solid rgba(245, 198, 203, 0.75);
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

        /* Availability Calendar Section */
        .availability-section {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 40px;
        }

        .availability-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .calendar-day-name {
            text-align: center;
            font-weight: 600;
            padding: 10px;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid #ddd;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: white;
        }

        .calendar-day.available {
            background: #e8f5e9;
            color: #2e7d32;
            border-color: #4caf50;
        }

        .calendar-day.available:hover {
            background: #c8e6c9;
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.2);
        }

        .calendar-day.selected {
            background: #4caf50;
            color: white;
            border-color: #2e7d32;
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.4);
            font-weight: 700;
        }

        .calendar-day.booked {
            background: #ffebee;
            color: #c62828;
            border-color: #ef5350;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .calendar-day.today {
            border: 2px solid #5a4a42;
            font-weight: 700;
        }

        .availability-legend {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
        }

        .legend-box {
            width: 30px;
            height: 30px;
            border-radius: 4px;
            border: 2px solid;
            flex-shrink: 0;
        }

        .legend-box {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 1px solid;
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

    <div style="background: white; min-height: 100vh; padding: 30px 0;">
        <div class="container">
            <!-- Back Button -->
            <a href="{{ route('home') }}" class="back-link">
                <span>&larr;</span> Kembali ke Daftar Villa
            </a>

            <!-- Image Section -->
            @if ($villa->thumbnail_path || ($villa->images && count($villa->images) > 0))
                <div class="image-section">
                    <div class="main-image-container">
                        <div class="main-image" onclick="openLightbox(0)">
                            <img id="mainImage" src="{{ asset($villa->thumbnail_path) }}" alt="{{ $villa->name }}">
                        </div>
                    </div>
                    <div class="thumbnail-gallery" id="galleryGrid">
                        @foreach ($villa->images as $index => $image)
                            @if ($index < 4)
                                <div class="thumbnail" onclick="changeImage('{{ asset($image) }}', this); openLightboxByImage(this)">
                                    <img src="{{ asset($image) }}" alt="Gallery image">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Lightbox Modal -->
            <div id="lightboxModal" class="lightbox-modal">
                <div class="lightbox-content">
                    <div class="lightbox-close" onclick="closeLightbox()">&times;</div>

                    <div class="lightbox-nav">
                        <button class="lightbox-prev" onclick="previousImage()">❮</button>
                        <button class="lightbox-next" onclick="nextImage()">❯</button>
                    </div>

                    <div style="position: relative; width: 100%; max-width: 1000px;">
                        <img id="lightboxImage" class="lightbox-main-image" src="" alt="Gallery">
                        <div class="lightbox-counter">
                            <span id="currentImageNumber">1</span> / <span id="totalImageCount">1</span>
                        </div>
                    </div>

                    @if ($villa->images && count($villa->images) > 0)
                        <div class="lightbox-thumbnails" id="lightboxThumbnails">
                            @if ($villa->thumbnail_path)
                                <div class="lightbox-thumbnail active" onclick="lightboxSelectImage(0)">
                                    <img src="{{ asset($villa->thumbnail_path) }}" alt="Thumbnail">
                                </div>
                            @endif
                            @foreach ($villa->images as $index => $image)
                                <div class="lightbox-thumbnail" onclick="lightboxSelectImage({{ $loop->index + 1 }})">
                                    <img src="{{ asset($image) }}" alt="Gallery image">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Description Section -->
            <div class="description-section">
                <h1 class="villa-title">{{ $villa->name }}</h1>
                <div class="villa-meta">
                    <div class="villa-meta-item">
                        <span>Kapasitas:</span>
                        <strong>{{ $villa->capacity }} orang</strong>
                    </div>
                    <div class="villa-meta-item">
                        <span>🛏️ Kamar:</span>
                        <strong>{{ $villa->rooms_total }} kamar</strong>
                    </div>
                    <div class="villa-meta-item">
                        <span>Harga:</span>
                        <strong>Rp {{ number_format($villa->base_price, 0, ',', '.') }}/malam</strong>
                    </div>
                </div>
                @if ($villa->description)
                    <div class="villa-description">
                        {!! nl2br(e($villa->description)) !!}
                    </div>
                @endif
            </div>

            <!-- Availability Calendar Section -->
            <div class="availability-section"
                style="background: #f8f9fa; border: 1px solid #e0e0e0; border-radius: 8px; padding: 25px; margin-bottom: 40px;">
                <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #333;">📅 Pilih Tanggal Menginap
                </h2>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <button type="button" id="prevMonthBtn" onclick="changeCalendarMonth(-1)"
                        style="background: #f05b4f; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s;">←
                        Sebelumnya</button>
                    <h3 id="currentMonthYear" style="font-size: 18px; font-weight: 600; color: #333; margin: 0;">Januari
                        2026</h3>
                    <button type="button" id="nextMonthBtn" onclick="changeCalendarMonth(1)"
                        style="background: #f05b4f; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s;">Selanjutnya
                        →</button>
                </div>

                <div id="availabilityCalendar"
                    style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; margin-bottom: 20px; padding: 15px; background: white; border-radius: 8px; border: 1px solid #e0e0e0;">
                    <!-- Calendar will be generated by JavaScript -->
                </div>

                <div style="margin-top: 25px; padding-top: 20px; border-top: 2px solid #e0e0e0;">
                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; width: 100%;">
                        <div style="display: flex; align-items: center; gap: 12px; font-size: 13px;">
                            <div
                                style="width: 30px; height: 30px; border-radius: 4px; border: 2px solid #4caf50; background: #e8f5e9; flex-shrink: 0;">
                            </div>
                            <div>
                                <strong style="color: #2e7d32;">Tersedia untuk Booking</strong>
                                <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">Klik tanggal untuk memilih</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px; font-size: 13px;">
                            <div
                                style="width: 30px; height: 30px; border-radius: 4px; border: 2px solid #ef5350; background: #ffebee; flex-shrink: 0;">
                            </div>
                            <div>
                                <strong style="color: #c62828;">Tidak Tersedia (Sudah Dipesan)</strong>
                                <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">Tidak dapat dipilih</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px; font-size: 13px;">
                            <div
                                style="width: 30px; height: 30px; border-radius: 4px; border: 3px solid #2196f3; background: #e3f2fd; flex-shrink: 0;">
                            </div>
                            <div>
                                <strong style="color: #1976d2;">Hari Ini</strong>
                                <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">Tanggal saat ini</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px; font-size: 13px;">
                            <div
                                style="width: 30px; height: 30px; border-radius: 4px; border: 3px solid #4caf50; background: #4caf50; flex-shrink: 0;">
                            </div>
                            <div>
                                <strong style="color: white; display: block; color: #2e7d32;">Dipilih</strong>
                                <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">Tanggal yang dipilih</p>
                            </div>
                        </div>
                    </div>
                </div>
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

                    <form action="{{ route('guest.store.booking') }}" method="POST" id="villaBookingForm"
                        name="villaBookingForm" novalidate>
                        @csrf
                        <input type="hidden" name="villa_id" id="villaBookingVillaId" value="{{ $villa->id }}">

                        <!-- Check-in -->
                        <div class="form-group">
                            <label for="villaBookingCheckIn">Check In:</label>
                            <input type="date" id="villaBookingCheckIn" name="checkin" class="form-control"
                                value="{{ old('checkin') }}" required min="{{ date('Y-m-d') }}" aria-label="Check In Date">
                            @error('checkin')
                                <small style="color: #dc3545;" role="alert">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Check-out -->
                        <div class="form-group">
                            <label for="villaBookingCheckOut">Check Out:</label>
                            <input type="date" id="villaBookingCheckOut" name="checkout" class="form-control"
                                value="{{ old('checkout') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                aria-label="Check Out Date">
                            @error('checkout')
                                <small style="color: #dc3545;" role="alert">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Number of Guests -->
                        <div class="form-group">
                            <label for="villaBookingGuests">Number of Guests:</label>
                            <input type="number" id="villaBookingGuests" name="guests" class="form-control" min="1"
                                max="{{ $villa->capacity ?? 10 }}" value="{{ old('guests', 1) }}" required
                                aria-label="Number of Guests">
                            @error('guests')
                                <small style="color: #dc3545;" role="alert">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Guest Name -->
                        <div class="form-group">
                            <label for="villaBookingGuestName">Guest Name:</label>
                            <input type="text" id="villaBookingGuestName" name="name" class="form-control"
                                value="{{ old('name', auth()->user()?->name) }}" placeholder="Your Full Name"
                                maxlength="255" required aria-label="Guest Name">
                            @error('name')
                                <small style="color: #dc3545;" role="alert">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="villaBookingEmail">Email:</label>
                            <input type="email" id="villaBookingEmail" name="email" class="form-control"
                                value="{{ old('email', auth()->user()?->email) }}" placeholder="your@email.com" required
                                autocomplete="email" aria-label="Email Address">
                            @error('email')
                                <small style="color: #dc3545;" role="alert">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="form-group">
                            <label for="villaBookingPhone">Phone Number:</label>
                            <input type="tel" id="villaBookingPhone" name="phone" class="form-control"
                                value="{{ old('phone', auth()->user()?->phone) }}" placeholder="08123456789"
                                pattern="[0-9\-\+\s]+" maxlength="20" required autocomplete="tel" aria-label="Phone Number">
                            @error('phone')
                                <small style="color: #dc3545;" role="alert">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Special Requests -->
                        <div class="form-group">
                            <label for="villaBookingSpecialRequests">Special Requests:</label>
                            <textarea id="villaBookingSpecialRequests" name="special_requests" class="form-control"
                                placeholder="Any special requests or notes..."
                                rows="4">{{ old('special_requests') }}</textarea>
                            @error('special_requests')
                                <small style="color: #dc3545;" role="alert">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="submit-btn" id="villaBookingSubmitBtn">Continue to Payment</button>
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
                        <span class="summary-total-value total-display">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const basePrice = {{ $villa->base_price }};
        let lightboxCurrentIndex = 0;
        let allImages = [];

        // Initialize all images for lightbox
        function initializeLightbox() {
            const mainImage = document.getElementById('mainImage').src;
            allImages = [mainImage];

            // Add gallery images if they exist
            document.querySelectorAll('.thumbnail img').forEach((img, index) => {
                if (index > 0 || img.src !== mainImage) {
                    if (allImages.indexOf(img.src) === -1) {
                        allImages.push(img.src);
                    }
                }
            });

            // If no separate gallery images, just use main image
            if (allImages.length === 0) {
                allImages = [mainImage];
            }

            document.getElementById('totalImageCount').textContent = allImages.length;
        }

        function openLightbox(index = 0) {
            initializeLightbox();
            lightboxCurrentIndex = index;
            const modal = document.getElementById('lightboxModal');
            modal.classList.add('active');
            updateLightboxImage();
            document.body.style.overflow = 'hidden';
        }

        function openLightboxByImage(element) {
            initializeLightbox();
            // Find the index of the clicked thumbnail
            const thumbnail = element;
            const allThumbnails = Array.from(document.querySelectorAll('.thumbnail'));
            const clickedIndex = allThumbnails.indexOf(thumbnail);

            // Find the corresponding image in allImages array
            const clickedSrc = thumbnail.querySelector('img').src;
            lightboxCurrentIndex = allImages.indexOf(clickedSrc);

            // If not found (shouldn't happen), default to 0
            if (lightboxCurrentIndex === -1) {
                lightboxCurrentIndex = 0;
            }

            const modal = document.getElementById('lightboxModal');
            modal.classList.add('active');
            updateLightboxImage();
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const modal = document.getElementById('lightboxModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function updateLightboxImage() {
            const lightboxImage = document.getElementById('lightboxImage');
            lightboxImage.src = allImages[lightboxCurrentIndex];
            document.getElementById('currentImageNumber').textContent = lightboxCurrentIndex + 1;

            // Update thumbnail highlighting
            document.querySelectorAll('.lightbox-thumbnail').forEach((thumb, index) => {
                if (index === lightboxCurrentIndex) {
                    thumb.classList.add('active');
                } else {
                    thumb.classList.remove('active');
                }
            });

            // Auto-scroll to active thumbnail
            const activeThumbnail = document.querySelector('.lightbox-thumbnail.active');
            if (activeThumbnail) {
                activeThumbnail.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        }

        function nextImage() {
            lightboxCurrentIndex = (lightboxCurrentIndex + 1) % allImages.length;
            updateLightboxImage();
        }

        function previousImage() {
            lightboxCurrentIndex = (lightboxCurrentIndex - 1 + allImages.length) % allImages.length;
            updateLightboxImage();
        }

        function lightboxSelectImage(index) {
            lightboxCurrentIndex = index;
            updateLightboxImage();
        }

        // Keyboard navigation for lightbox
        document.addEventListener('keydown', (e) => {
            const modal = document.getElementById('lightboxModal');
            if (modal.classList.contains('active')) {
                if (e.key === 'ArrowRight') nextImage();
                if (e.key === 'ArrowLeft') previousImage();
                if (e.key === 'Escape') closeLightbox();
            }
        });

        // Close lightbox when clicking outside the content
        document.getElementById('lightboxModal').addEventListener('click', (e) => {
            if (e.target.id === 'lightboxModal') {
                closeLightbox();
            }
        });

        // Global calendar state
        window.selectedCheckInDate = null;
        window.selectedCheckOutDate = null;
        window.calendarDate = null;

        // Helper function to convert date to YYYY-MM-DD format (local date)
        function dateToString(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Helper function to parse YYYY-MM-DD string to Date
        function stringToDate(dateStr) {
            const [year, month, day] = dateStr.split('-').map(Number);
            return new Date(year, month - 1, day);
        }

        // Generate availability calendar
        function generateAvailabilityCalendar() {
            const bookedDatesSet = new Set();
            const now = new Date();
            now.setHours(0, 0, 0, 0);

            // Parse booked dates from PHP data
            const bookedDatesJson = @json($bookedDates ?? []);
            bookedDatesJson.forEach(booking => {
                const startDate = stringToDate(booking.check_in_date);
                const endDate = stringToDate(booking.check_out_date);
                for (let d = new Date(startDate); d < endDate; d.setDate(d.getDate() + 1)) {
                    bookedDatesSet.add(dateToString(d));
                }
            });

            const container = document.getElementById('availabilityCalendar');
            let currentDate = new Date(now.getFullYear(), now.getMonth(), 1);

            if (!window.calendarDate) {
                window.calendarDate = new Date(currentDate);
            } else {
                currentDate = new Date(window.calendarDate);
            }

            container.innerHTML = '';

            // Update header
            const monthYear = currentDate.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
            document.getElementById('currentMonthYear').textContent = monthYear.charAt(0).toUpperCase() + monthYear.slice(1);

            // Disable prev button if current month
            const isCurrentMonth = currentDate.getFullYear() === now.getFullYear() && currentDate.getMonth() === now.getMonth();
            document.getElementById('prevMonthBtn').disabled = isCurrentMonth;
            document.getElementById('prevMonthBtn').style.opacity = isCurrentMonth ? '0.5' : '1';
            document.getElementById('prevMonthBtn').style.cursor = isCurrentMonth ? 'not-allowed' : 'pointer';
            document.getElementById('prevMonthBtn').style.cursor = isCurrentMonth ? 'not-allowed' : 'pointer';

            // Day headers
            const dayHeaders = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            dayHeaders.forEach(day => {
                const header = document.createElement('div');
                header.style.cssText = 'padding: 10px; text-align: center; font-weight: 700; color: #666; background: #f5f5f5; border-radius: 4px; font-size: 11px; text-transform: uppercase;';
                header.textContent = day;
                container.appendChild(header);
            });

            // Empty cells
            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
            for (let i = 0; i < firstDay; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.style.cssText = 'padding: 12px; text-align: center; background: #fafafa;';
                container.appendChild(emptyCell);
            }

            // Get current form values
            const checkInValue = document.getElementById('villaBookingCheckIn').value;
            const checkOutValue = document.getElementById('villaBookingCheckOut').value;

            // Date cells
            const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
            for (let day = 1; day <= daysInMonth; day++) {
                const dateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
                dateObj.setHours(0, 0, 0, 0);
                const dateStr = dateToString(dateObj);
                const isBooked = bookedDatesSet.has(dateStr);
                const isToday = dateToString(dateObj) === dateToString(now);
                const isPast = dateObj < now && !isToday;
                const isCheckInSelected = dateStr === checkInValue;
                const isCheckOutSelected = dateStr === checkOutValue;

                let isInRange = false;
                if (checkInValue && checkOutValue) {
                    const checkInDate = stringToDate(checkInValue);
                    const checkOutDate = stringToDate(checkOutValue);
                    isInRange = dateObj > checkInDate && dateObj < checkOutDate;
                }

                const cell = document.createElement('div');

                if (isPast) {
                    cell.style.cssText = 'padding: 12px; text-align: center; background: #e0e0e0; color: #999; border-radius: 6px; font-weight: 600; cursor: not-allowed; opacity: 0.6;';
                    cell.textContent = day;
                } else if (isBooked) {
                    cell.style.cssText = 'padding: 12px; text-align: center; background: #ffebee; border: 2px solid #ef5350; color: #c62828; border-radius: 6px; font-weight: 600; cursor: not-allowed; opacity: 0.8;';
                    cell.innerHTML = `❌<br>${day}`;
                    cell.title = 'Sudah dipesan';
                } else if (isCheckInSelected || isCheckOutSelected) {
                    cell.style.cssText = 'padding: 12px; text-align: center; background: #4caf50; color: white; border: 3px solid #2e7d32; border-radius: 6px; font-weight: 700; cursor: pointer; transition: all 0.2s;';
                    cell.innerHTML = `✓<br>${day}`;
                    cell.title = isCheckInSelected ? 'Check-in' : 'Check-out';
                    cell.onmouseover = () => cell.style.background = '#388e3c';
                    cell.onmouseout = () => cell.style.background = '#4caf50';
                    cell.onclick = (e) => {
                        e.stopPropagation();
                        if (!checkInValue) {
                            setCheckInDate(dateStr);
                        } else {
                            setCheckOutDate(dateStr);
                        }
                    };
                } else if (isInRange) {
                    cell.style.cssText = 'padding: 12px; text-align: center; background: #a5d6a7; color: #2e7d32; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s;';
                    cell.textContent = day;
                    cell.title = 'Dalam rentang dipilih';
                    cell.onmouseover = () => cell.style.background = '#81c784';
                    cell.onmouseout = () => cell.style.background = '#a5d6a7';
                    cell.onclick = (e) => {
                        e.stopPropagation();
                        setCheckOutDate(dateStr);
                    };
                } else if (isToday) {
                    cell.style.cssText = 'padding: 12px; text-align: center; background: #e3f2fd; border: 3px solid #2196f3; color: #1976d2; border-radius: 6px; font-weight: 700; cursor: pointer; transition: all 0.2s;';
                    cell.innerHTML = `🔵<br>${day}`;
                    cell.title = 'Hari ini - Klik untuk check-in';
                    cell.onmouseover = () => cell.style.background = '#bbdefb';
                    cell.onmouseout = () => cell.style.background = '#e3f2fd';
                    cell.onclick = (e) => {
                        e.stopPropagation();
                        setCheckInDate(dateStr);
                    };
                } else {
                    cell.style.cssText = 'padding: 12px; text-align: center; background: #e8f5e9; border: 2px solid #4caf50; color: #2e7d32; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s;';
                    cell.innerHTML = `✓<br>${day}`;
                    cell.title = 'Klik untuk memilih';
                    cell.onmouseover = () => cell.style.background = '#c8e6c9';
                    cell.onmouseout = () => cell.style.background = '#e8f5e9';
                    cell.onclick = (e) => {
                        e.stopPropagation();
                        if (!checkInValue) {
                            setCheckInDate(dateStr);
                        } else {
                            setCheckOutDate(dateStr);
                        }
                    };
                }
                container.appendChild(cell);
            }
        }

        // Set check-in date
        function setCheckInDate(dateStr) {
            document.getElementById('villaBookingCheckIn').value = dateStr;
            document.getElementById('villaBookingCheckOut').value = '';
            window.selectedCheckInDate = dateStr;
            window.selectedCheckOutDate = null;
            generateAvailabilityCalendar();
            updateSummary();
        }

        // Set check-out date with validation
        function setCheckOutDate(dateStr) {
            const checkInValue = document.getElementById('villaBookingCheckIn').value;
            if (!checkInValue) {
                alert('Pilih tanggal check-in terlebih dahulu!');
                return;
            }
            const checkInDate = stringToDate(checkInValue);
            const checkOutDate = stringToDate(dateStr);
            if (checkOutDate <= checkInDate) {
                alert('Tanggal checkout harus setelah check-in!');
                return;
            }
            document.getElementById('villaBookingCheckOut').value = dateStr;
            window.selectedCheckInDate = checkInValue;
            window.selectedCheckOutDate = dateStr;
            generateAvailabilityCalendar();
            updateSummary();
        }

        function changeCalendarMonth(offset) {
            if (!window.calendarDate) {
                window.calendarDate = new Date();
            }
            window.calendarDate.setMonth(window.calendarDate.getMonth() + offset);
            generateAvailabilityCalendar();
        }

        function updateSummary() {
            const checkinInput = document.getElementById('villaBookingCheckIn').value;
            const checkoutInput = document.getElementById('villaBookingCheckOut').value;

            // Format and display dates
            if (checkinInput) {
                const checkinDate = stringToDate(checkinInput);
                document.querySelector('.checkin-display').textContent =
                    checkinDate.toLocaleDateString('id-ID', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
            }

            if (checkoutInput) {
                const checkoutDate = stringToDate(checkoutInput);
                document.querySelector('.checkout-display').textContent =
                    checkoutDate.toLocaleDateString('id-ID', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
            }

            // Calculate duration and total
            if (checkinInput && checkoutInput) {
                const checkinDate = stringToDate(checkinInput);
                const checkoutDate = stringToDate(checkoutInput);
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
        document.getElementById('villaBookingCheckIn').addEventListener('change', function () {
            const checkInValue = this.value;
            const checkOutValue = document.getElementById('villaBookingCheckOut').value;
            
            if (checkInValue && checkOutValue) {
                const checkInDate = stringToDate(checkInValue);
                const checkOutDate = stringToDate(checkOutValue);
                if (checkOutDate <= checkInDate) {
                    const nextDay = new Date(checkInDate);
                    nextDay.setDate(nextDay.getDate() + 1);
                    document.getElementById('villaBookingCheckOut').value = dateToString(nextDay);
                }
            }
            generateAvailabilityCalendar();
            updateSummary();
        });
        
        document.getElementById('villaBookingCheckOut').addEventListener('change', function () {
            const checkInValue = document.getElementById('villaBookingCheckIn').value;
            const checkOutValue = this.value;
            
            if (checkInValue && checkOutValue) {
                const checkInDate = stringToDate(checkInValue);
                const checkOutDate = stringToDate(checkOutValue);
                if (checkOutDate <= checkInDate) {
                    const nextDay = new Date(checkInDate);
                    nextDay.setDate(nextDay.getDate() + 1);
                    this.value = dateToString(nextDay);
                }
            }
            generateAvailabilityCalendar();
            updateSummary();
        });

        // Image gallery function
        function changeImage(src, element) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
            element.classList.add('active');
        }

        // Handle "See all Photos" click
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-populate check-in with today and check-out with tomorrow
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);

            const todayString = dateToString(today);
            const tomorrowString = dateToString(tomorrow);

            const checkInInput = document.getElementById('villaBookingCheckIn');
            const checkOutInput = document.getElementById('villaBookingCheckOut');

            if (!checkInInput.value) {
                checkInInput.value = todayString;
                window.selectedCheckInDate = todayString;
            }
            if (!checkOutInput.value) {
                checkOutInput.value = tomorrowString;
                window.selectedCheckOutDate = tomorrowString;
            }

            // Generate calendar
            generateAvailabilityCalendar();

            // Trigger update summary to display the dates
            updateSummary();

            const galleryGrid = document.getElementById('galleryGrid');
            if (galleryGrid) {
                galleryGrid.addEventListener('click', function (e) {
                    const thumbnail = e.target.closest('.thumbnail');
                    if (thumbnail && thumbnail.querySelector('.see-all-overlay')) {
                        // Toggle showing all photos
                        const hiddenPhotos = galleryGrid.querySelectorAll('.thumbnail.hidden-photo');
                        hiddenPhotos.forEach(photo => {
                            photo.classList.toggle('show-all');
                        });

                        // Toggle grid layout for more photos
                        if (galleryGrid.querySelectorAll('.thumbnail.hidden-photo.show-all').length > 0) {
                            // Change to more columns when showing all
                            galleryGrid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(150px, 1fr))';
                        } else {
                            // Back to 4 columns
                            galleryGrid.style.gridTemplateColumns = 'repeat(4, 1fr)';
                        }
                    }
                });
            }
        });
    </script>
@endsection