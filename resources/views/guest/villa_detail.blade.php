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
            grid-template-columns: 1.5fr 1fr;
            gap: 15px;
        }

        .main-image-container {
            grid-column: 1;
        }

        .main-image {
            width: 100%;
            height: 450px;
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
            content: '🔍';
            position: absolute;
            font-size: 48px;
            color: white;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 5;
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
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 14px;
            background: rgba(0, 0, 0, 0.6);
            padding: 8px 16px;
            border-radius: 20px;
            z-index: 1005;
        }

        .thumbnail-gallery {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            padding: 0;
            margin: 0;
            grid-column: 2;
            grid-row: 1;
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
                    @if ($villa->images && count($villa->images) > 0)
                        <div class="thumbnail-gallery" id="galleryGrid">
                            @if ($villa->thumbnail_path)
                                <div class="thumbnail active"
                                    onclick="changeImage('{{ asset($villa->thumbnail_path) }}', this); openLightbox(0)">
                                    <img src="{{ asset($villa->thumbnail_path) }}" alt="Thumbnail">
                                </div>
                            @endif
                            @foreach ($villa->images as $index => $image)
                                <div class="thumbnail {{ $loop->index < 3 ? '' : 'hidden-photo' }}"
                                    onclick="changeImage('{{ asset($image) }}', this); openLightbox({{ $loop->index + 1 }})">
                                    @if ($loop->index === 3 && count($villa->images) > 4)
                                        <div class="see-all-overlay">See all Photos</div>
                                    @endif
                                    <img src="{{ asset($image) }}" alt="Gallery image">
                                </div>
                            @endforeach
                        </div>
                    @endif
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

                    <img id="lightboxImage" class="lightbox-main-image" src="" alt="Gallery">

                    <div class="lightbox-counter">
                        <span id="currentImageNumber">1</span> / <span id="totalImageCount">1</span>
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

        // Handle "See all Photos" click
        document.addEventListener('DOMContentLoaded', function () {
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