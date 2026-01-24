@extends('layouts.app')

@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            background: white;
        }

        /* Hero Section */
        .hero {
            background: #e8e8e8;
            height: 500px;
            margin: 20px auto;
            max-width: 1200px;
            border-radius: 4px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .carousel-container {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .carousel-item {
            position: absolute;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 0.7s ease-in-out;
        }

        .carousel-item.active {
            opacity: 1;
        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .carousel-dots {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .dot {
            width: 12px;
            height: 12px;
            background: black;
            border-radius: 50%;
            cursor: pointer;
            opacity: 0.4;
            transition: opacity 0.3s;
        }

        .dot.active {
            opacity: 1;
        }

        /* Content Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            background: white;
        }

        /* Description Section */
        .description-box {
            background: white;
            padding: 30px;
            margin: 30px 0;
            border: 1px solid #e8e8e8;
            border-radius: 4px;
            text-align: center;
            line-height: 1.6;
            font-size: 14px;
            color: #666;
        }

        /* Villa Section */
        .villa-section {
            margin: 40px 0;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        /* Search Bar */
        .search-bar {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 30px;
        }

        .search-input,
        .search-btn {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-input {
            background: white;
        }

        .search-btn {
            background: #f05b4f;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .search-btn:hover {
            background: #d84539;
        }

        /* Villa Grid */
        .villa-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .villa-card {
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            transition: box-shadow 0.3s;
        }

        .villa-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .villa-image {
            width: 100%;
            height: 200px;
            background: #FAF2E8;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 12px;
            overflow: hidden;
        }

        .villa-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .villa-info {
            padding: 15px;
        }

        .villa-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .villa-capacity {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }

        .villa-price {
            font-size: 15px;
            font-weight: 600;
            color: #28a745;
            margin-top: 10px;
        }

        /* Facility Section */
        .facility-section {
            background: #FAF2E8;
            border: 1px solid #e8e8e8;
            border-radius: 4px;
            padding: 40px;
            margin: 50px 0 30px;
        }

        .facility-categories {
            display: flex;
            flex-direction: column;
            gap: 25px;
            margin-top: 20px;
        }

        .facility-category {
            background: transparent;
            border: none;
            border-radius: 0;
            padding: 0;
            box-shadow: none;
        }

        .facility-category-title {
            display: block;
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f05b4f;
        }

        .facility-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-left: 20px;
        }

        .facility-item {
            display: block;
            padding: 6px 0;
            font-size: 14px;
            color: #555;
        }

        .facility-item::before {
            content: "";
            display: none;
        }

        .facility-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 20px;
        }

        .facility-icon {
            font-size: 32px;
            flex-shrink: 0;
        }

        .facility-text strong {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .facility-text {
            font-size: 13px;
            color: #666;
            line-height: 1.5;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .container {
                padding: 0 20px;
            }

            .hero {
                margin: 20px auto;
                height: 300px;
            }

            .villa-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .facility-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .search-bar {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .villa-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .facility-grid {
                grid-template-columns: 1fr;
            }

            .hero {
                height: 250px;
                margin: 20px auto;
            }

            .container {
                padding: 0 20px;
            }

            .search-bar {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .villa-grid {
                grid-template-columns: 1fr;
            }

            .facility-grid {
                grid-template-columns: 1fr;
            }

            .hero {
                margin: 20px auto;
                height: 200px;
            }

            .container {
                padding: 0 15px;
            }

            .search-bar {
                grid-template-columns: 1fr;
            }

            .section-title {
                font-size: 16px;
            }

            .villa-name {
                font-size: 14px;
            }
        }
    </style>

    <!-- Hero Section with Carousel -->
    <div class="hero">
        <div class="carousel-container">
            @if ($sliderImages && count($sliderImages) > 0)
                @foreach ($sliderImages as $index => $image)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}"
                        style="background-image: url('{{ asset($image) }}');">
                    </div>
                @endforeach
            @else
                <div class="carousel-item active" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
            @endif
        </div>


    </div>

    <div class="container">
        <!-- Description -->
        @if ($description)
            <div class="description-box">
                {!! nl2br(e($description)) !!}
            </div>
        @endif

        <!-- Villa Section -->
        <div class="villa-section" id="villa">
            <h2 class="section-title">Villa</h2>

            <div class="search-bar">
                <input type="number" id="capacity" class="search-input" placeholder="Kapasitas" min="1">
                <input type="date" id="dates" class="search-input" placeholder="Tanggal">
                <input type="number" id="price" class="search-input" placeholder="Harga Max" min="0">
                <button class="search-btn" onclick="searchVillas()">Search</button>
            </div>

            <div class="villa-grid" id="villaGrid">
                @forelse ($villas as $villa)
                    <a href="{{ route('guest.villa.detail', $villa->id) }}" style="text-decoration: none; color: inherit;">
                        <div class="villa-card">
                            <div class="villa-image">
                                @if ($villa->thumbnail_path)
                                    <img src="{{ asset($villa->thumbnail_path) }}" alt="{{ $villa->name }}">
                                @else
                                    No image
                                @endif
                            </div>
                            <div class="villa-info">
                                <div class="villa-name">{{ $villa->name }}</div>
                                <div class="villa-capacity">Kapasitas {{ $villa->capacity }} orang</div>
                                <div class="villa-price">Rp {{ number_format($villa->base_price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #999;">
                        No villas available
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Facility Section -->
        @if ($facilities && count($facilities) > 0)
            <div class="facility-section">
                <h2 class="section-title">Facilities</h2>
                <div class="facility-categories">
                    @php
                        $grouped = $facilities->groupBy('category');
                    @endphp
                    @foreach ($grouped as $category => $items)
                        <div class="facility-category">
                            <div class="facility-category-title">
                                <span>{{ str_replace('_', ' ', ucfirst($category)) }}</span>
                            </div>
                            <div class="facility-list">
                                @foreach ($items as $facility)
                                    <div class="facility-item">{{ $facility->name }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="facility-section">
                <h2 class="section-title">Facilities</h2>
                <div class="facility-categories">
                    <div class="facility-category">
                        <div class="facility-category-title">
                            <span>Public Facilities</span>
                        </div>
                        <div class="facility-list">
                            <div class="facility-item">Parking area</div>
                        </div>
                    </div>
                    <div class="facility-category">
                        <div class="facility-category-title">
                            <span>Connectivity</span>
                        </div>
                        <div class="facility-list">
                            <div class="facility-item">WiFi in public areas</div>
                            <div class="facility-item">In-room internet</div>
                        </div>
                    </div>
                    <div class="facility-category">
                        <div class="facility-category-title">
                            <span>Other Activities</span>
                        </div>
                        <div class="facility-list">
                            <div class="facility-item">Garden</div>
                        </div>
                    </div>
                    <div class="facility-category">
                        <div class="facility-category-title">
                            <span>Transportation</span>
                        </div>
                        <div class="facility-list">
                            <div class="facility-item">Bicycle rental</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        let currentSlide = 0;
        const sliderImages = @json($sliderImages ?? []);
        const totalSlides = sliderImages.length > 0 ? sliderImages.length : 1;

        function showSlide(index) {
            if (totalSlides <= 1) return;

            const dots = document.querySelectorAll('.dot');
            dots.forEach(dot => dot.classList.remove('active'));

            if (dots[index]) {
                dots[index].classList.add('active');
            }

            const items = document.querySelectorAll('.carousel-item');
            items.forEach(item => item.classList.remove('active'));
            if (items[index]) {
                items[index].classList.add('active');
            }
        }

        function goToSlide(index) {
            currentSlide = index;
            showSlide(currentSlide);
        }

        // Auto advance slides every 5 seconds
        if (totalSlides > 1) {
            setInterval(() => {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }, 5000);
        }

        function searchVillas() {
            const capacity = document.getElementById('capacity').value;
            const dates = document.getElementById('dates').value;
            const price = document.getElementById('price').value;

            const params = new URLSearchParams();
            if (capacity) params.append('capacity', capacity);
            if (dates) params.append('checkin', dates);
            if (price) params.append('price', price);

            window.location.href = '/villa?' + params.toString();
        }
    </script>
@endsection