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
            background: #FAF2E8;
        }

        /* Hero Section */
        .hero {
            background: #e8e8e8;
            height: 500px;
            margin: 20px 40px;
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
            padding: 0 40px;
            background: white opacity: 0.95;
            q
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
            margin-bottom: 20px;
            color: #333;
        }

        /* Search Bar */
        .search-bar {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 12px;
            margin-bottom: 25px;
            padding: 15px;
            background: white;
            border: 1px solid #e8e8e8;
            border-radius: 4px;
        }

        .search-input {
            padding: 10px 12px;
            border: 1px solid #e8e8e8;
            border-radius: 3px;
            font-size: 13px;
            font-family: inherit;
        }

        .search-input:focus {
            outline: none;
            border-color: #333;
        }

        .search-btn {
            padding: 10px 20px;
            background: #333;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            align-self: flex-start;
        }

        .search-btn:hover {
            background: #555;
        }

        /* Villa Grid */
        .villa-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .villa-card {
            background: white;
            border: 1px solid #e8e8e8;
            border-radius: 4px;
            overflow: hidden;
            cursor: pointer;
            transition: box-shadow 0.3s;
        }

        .villa-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .villa-image {
            background: #e8e8e8;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #999;
        }

        .villa-info {
            padding: 12px;
        }

        .villa-name {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .villa-capacity {
            font-size: 12px;
            color: #666;
            background: #f5f5f5;
            padding: 5px 8px;
            border-radius: 3px;
            display: inline-block;
            margin-bottom: 8px;
        }

        .villa-price {
            font-size: 12px;
            color: #333;
            font-weight: 600;
        }

        /* Facility Section */
        .facility-section {
            margin: 50px 0;
        }

        .facility-section .section-title {
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }

        .facility-tabs {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            border-bottom: 2px solid #e8e8e8;
            flex-wrap: wrap;
            overflow-x: auto;
            padding-bottom: 15px;
        }

        .facility-tab {
            padding: 8px 16px;
            border: 2px solid transparent;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #666;
            transition: all 0.3s ease;
            background: transparent;
            white-space: nowrap;
            border-radius: 20px;
        }

        .facility-tab:hover {
            background: #f5f5f5;
            color: #333;
        }

        .facility-tab.active {
            color: white;
            background: #5a4a42;
            border-color: #5a4a42;
        }

        .facility-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .facility-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 25px;
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            gap: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .facility-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
            border-color: #5a4a42;
        }

        .facility-icon {
            font-size: 48px;
            transition: transform 0.3s ease;
        }

        .facility-item:hover .facility-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .facility-name {
            font-size: 14px;
            color: #333;
            font-weight: 600;
            line-height: 1.4;
        }

        .facility-type {
            font-size: 12px;
            color: #999;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Footer */
        footer {
            background: white;
            border-top: 1px solid #e8e8e8;
            padding: 20px;
            text-align: center;
            color: #999;
            font-size: 12px;
            margin-top: 40px;
        }

        /* Responsive */
        @media (max-width: 1024px) {

            .villa-grid,
            .facility-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .search-bar {
                grid-template-columns: 1fr 1fr auto;
            }
        }

        @media (max-width: 768px) {
            header {
                padding: 12px 20px;
            }

            .nav-links {
                gap: 20px;
            }

            .hero {
                margin: 20px;
                height: 250px;
            }

            .container {
                padding: 0 20px;
            }

            .villa-grid,
            .facility-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .search-bar {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .nav-links {
                display: none;
            }

            .villa-grid,
            .facility-grid {
                grid-template-columns: 1fr;
            }

            .search-bar {
                grid-template-columns: 1fr;
            }

            header {
                padding: 12px 15px;
            }

            .container {
                padding: 0 15px;
            }
        }
    </style>

    <!-- Hero Section with Carousel -->
    <div class="hero">
        <div class="carousel-container">
            @if ($sliderImages && count($sliderImages) > 0)
                @foreach ($sliderImages as $index => $image)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}"
                        style="background-image: url('{{ asset($image) }}');"></div>
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
                                @if ($villa->image_url)
                                    <img src="{{ asset($villa->image_url) }}" alt="{{ $villa->name }}"
                                        style="width:100%; height:100%; object-fit: cover;">
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
                    <p style="grid-column: 1 / -1; text-align: center; color: #999; padding: 30px;">Tidak ada villa tersedia</p>
                @endforelse
            </div>
        </div>

        <!-- Facility Section -->
        @if ($facilities && $facilities->count() > 0)
            <div class="facility-section" id="facility">
                <h2 class="section-title">Facility</h2>

                <div class="facility-grid">
                    @foreach ($facilities as $facility)
                        <div class="facility-item" data-category="{{ $facility->category }}">
                            <div class="facility-icon">
                                <span style="font-size: 28px; color: #5a4a42;">■</span>
                            </div>
                            <div class="facility-name">{{ $facility->name }}</div>
                            <div class="facility-type">{{ ucfirst(str_replace('_', ' ', $facility->category)) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-item');
        const dots = document.querySelectorAll('.dot');

        function goToSlide(n) {
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));

            slides[n].classList.add('active');
            dots[n].classList.add('active');
            currentSlide = n;
        }

        function nextSlide() {
            if (slides.length === 0) return;
            currentSlide = (currentSlide + 1) % slides.length;
            goToSlide(currentSlide);
        }

        // Auto-rotate carousel every 5 seconds
        if (slides.length > 1) {
            setInterval(nextSlide, 5000);
        }

        function filterFacility(category, element) {
            document.querySelectorAll('.facility-tab').forEach(tab => tab.classList.remove('active'));
            element.classList.add('active');

            document.querySelectorAll('.facility-item').forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Instant search for villas
        function searchVillas() {
            const capacity = document.getElementById('capacity').value;
            const dates = document.getElementById('dates').value;
            const price = document.getElementById('price').value;

            const params = new URLSearchParams();
            if (capacity) params.append('capacity', capacity);
            if (dates) params.append('dates', dates);
            if (price) params.append('price', price);

            fetch(`/api/villas/search?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    const villaGrid = document.getElementById('villaGrid');
                    villaGrid.innerHTML = '';

                    if (data.villas && data.villas.length > 0) {
                        data.villas.forEach(villa => {
                            const villaHTML = `
                                            <a href="/villa/${villa.id}" style="text-decoration: none; color: inherit;">
                                                <div class="villa-card">
                                                    <div class="villa-image">
                                                        ${villa.image_url ? `<img src="${villa.image_url}" alt="${villa.name}" style="width:100%; height:100%; object-fit: cover;">` : 'No image'}
                                                    </div>
                                                    <div class="villa-info">
                                                        <div class="villa-name">${villa.name}</div>
                                                        <div class="villa-capacity">Kapasitas ${villa.capacity} orang</div>
                                                        <div class="villa-price">Rp ${villa.base_price.toLocaleString('id-ID')}</div>
                                                    </div>
                                                </div>
                                            </a>
                                        `;
                            villaGrid.innerHTML += villaHTML;
                        });
                    } else {
                        villaGrid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #999;">Villa tidak ditemukan</p>';
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Load all villas on page load
        document.addEventListener('DOMContentLoaded', function () {
            searchVillas();
        });

        // Real-time search as user types
        document.getElementById('capacity').addEventListener('change', searchVillas);
        document.getElementById('dates').addEventListener('change', searchVillas);
        document.getElementById('price').addEventListener('input', searchVillas);
    </script>
@endsection