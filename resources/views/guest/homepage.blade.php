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
            height: 600px;
            margin: 0;
            margin-top: 20px;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
            transition: opacity 0.8s ease-in-out;
        }

        .carousel-item.active {
            opacity: 1;
        }

        /* Content Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
            background: white;
            border-radius: 12px;
        }

        /* Description Section */
        .description-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 40px;
            margin: 40px 0;
            border-left: 5px solid #5a4a42;
            border-radius: 8px;
            text-align: center;
            line-height: 1.8;
            font-size: 15px;
            color: #555;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* Villa Section */
        .villa-section {
            margin: 60px 0;
        }

        .section-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #2c2c2c;
            position: relative;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #5a4a42 0%, #8b7a72 100%);
            border-radius: 2px;
        }

        /* Search Bar */
        .search-bar {
            display: grid;
            grid-template-columns: repeat(4, 1fr) 1fr;
            gap: 15px;
            margin-bottom: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .search-bar:hover {
            border-color: #5a4a42;
            box-shadow: 0 6px 20px rgba(90, 74, 66, 0.12);
        }

        .search-input {
            padding: 12px 15px;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s;
            background: white;
            color: #333;
        }

        .search-input:hover {
            border-color: #bbb;
        }

        .search-input:focus {
            outline: none;
            border-color: #5a4a42;
            box-shadow: 0 0 0 4px rgba(90, 74, 66, 0.1);
            background: white;
            transform: scale(1.01);
        }

        .search-input::placeholder {
            color: #999;
            font-weight: 500;
        }

        .search-btn {
            padding: 12px 32px;
            background: linear-gradient(135deg, #5a4a42 0%, #3d2f2a 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            align-self: flex-start;
            transition: all 0.3s;
            box-shadow: 0 4px 16px rgba(90, 74, 66, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .search-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(90, 74, 66, 0.4);
        }

        .search-btn:active {
            transform: translateY(-1px);
        }

        .search-loading {
            display: none;
            color: #f05b4f;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            font-size: 16px;
        }

        .search-loading.active {
            display: block;
        }

        /* Villa Grid */
        .villa-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 40px;
        }

        .villa-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .villa-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            border-color: #5a4a42;
        }

        .villa-image {
            background: linear-gradient(135deg, #e8e8e8 0%, #f5f5f5 100%);
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #999;
            overflow: hidden;
            position: relative;
        }

        .villa-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0);
            transition: background 0.3s;
        }

        .villa-card:hover .villa-image::after {
            background: rgba(0, 0, 0, 0.1);
        }

        .villa-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .villa-card:hover .villa-image img {
            transform: scale(1.05);
        }

        .villa-info {
            padding: 16px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .villa-name {
            font-size: 16px;
            font-weight: 600;
            color: #2c2c2c;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .villa-capacity {
            font-size: 13px;
            color: #666;
            background: #f5f5f5;
            padding: 6px 10px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 10px;
            margin-top: auto;
        }

        .villa-price {
            font-size: 16px;
            color: #5a4a42;
            font-weight: 700;
        }

        /* Facility Section */
        .facility-section {
            margin: 80px 0;
        }

        .facility-section .section-title {
            margin-bottom: 40px;
        }

        .facility-tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 40px;
            flex-wrap: wrap;
            overflow-x: auto;
            padding-bottom: 5px;
        }

        .facility-tab {
            padding: 10px 20px;
            border: 2px solid #ddd;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #555;
            transition: all 0.3s ease;
            background: white;
            white-space: nowrap;
            border-radius: 25px;
        }

        .facility-tab:hover {
            background: #f5f5f5;
            color: #333;
            border-color: #5a4a42;
        }

        .facility-tab.active {
            color: white;
            background: linear-gradient(135deg, #5a4a42 0%, #3d2f2a 100%);
            border-color: #5a4a42;
            box-shadow: 0 4px 12px rgba(90, 74, 66, 0.2);
        }

        .facility-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            animation: fadeIn 0.4s ease-in-out;
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
            padding: 30px 20px;
            background: white;
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            gap: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
        }

        .facility-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #5a4a42 0%, #8b7a72 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .facility-item:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
            border-color: #5a4a42;
        }

        .facility-item:hover::before {
            opacity: 0.05;
        }

        .facility-icon {
            font-size: 56px;
            transition: all 0.3s ease;
            line-height: 1;
        }

        .facility-item:hover .facility-icon {
            transform: scale(1.15) rotate(5deg);
            filter: drop-shadow(0 4px 8px rgba(90, 74, 66, 0.2));
        }

        .facility-name {
            font-size: 15px;
            color: #2c2c2c;
            font-weight: 600;
            line-height: 1.4;
        }

        .facility-item:hover .facility-name {
            color: #5a4a42;
        }

        .facility-type {
            font-size: 11px;
            color: #888;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, #2c2c2c 0%, #1a1a1a 100%);
            border-top: 1px solid #e8e8e8;
            padding: 40px;
            text-align: center;
            color: #999;
            font-size: 13px;
            margin-top: 60px;
        }

        footer a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }

        footer a:hover {
            color: #5a4a42;
        }

        /* Responsive */
        @media (max-width: 1024px) {

            .villa-grid,
            .facility-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .search-bar {
                grid-template-columns: repeat(2, 1fr);
            }

            .carousel-overlay h1 {
                font-size: 2em;
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
                margin: 0;
                height: 350px;
            }

            .container {
                padding: 0 20px;
            }

            .section-title {
                font-size: 24px;
            }

            .villa-grid,
            .facility-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .search-bar {
                grid-template-columns: 1fr;
            }

            .search-bar button {
                grid-column: 1/-1;
            }

            .carousel-overlay h1 {
                font-size: 1.5em;
            }

            .carousel-overlay p {
                font-size: 1em;
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

            .section-title {
                font-size: 20px;
            }

            .hero {
                height: 250px;
            }

            .carousel-overlay h1 {
                font-size: 1.2em;
            }

            .carousel-overlay p {
                font-size: 0.9em;
            }

            .facility-item {
                padding: 20px 15px;
            }

            .facility-icon {
                font-size: 40px;
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
            <h2 class="section-title">🏠 Find Your Perfect Villa</h2>

            <div class="search-bar" id="searchBar">
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label
                        style="font-size: 11px; font-weight: 700; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">👥
                        Jumlah Tamu</label>
                    <input type="number" id="capacity" class="search-input" placeholder="2" min="1" max="20"
                        aria-label="Jumlah Tamu">
                </div>
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label
                        style="font-size: 11px; font-weight: 700; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">📍
                        Cek Masuk</label>
                    <input type="date" id="checkin" class="search-input" min="{{ date('Y-m-d') }}"
                        aria-label="Tanggal Cek Masuk">
                </div>
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label
                        style="font-size: 11px; font-weight: 700; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">📍
                        Cek Keluar</label>
                    <input type="date" id="checkout" class="search-input" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        aria-label="Tanggal Cek Keluar">
                </div>
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label
                        style="font-size: 11px; font-weight: 700; color: #666; text-transform: uppercase; letter-spacing: 0.5px;">💰
                        Maks. Harga</label>
                    <input type="number" id="price" class="search-input" placeholder="Rp 5.000.000" min="0" step="100000"
                        aria-label="Harga Maksimal">
                </div>
                <button class="search-btn" onclick="searchVillas()" id="searchSubmitBtn"
                    style="grid-column: 1/-1; margin-top: 8px;">
                    <span id="searchBtnText">🔍 Cari Villa</span>
                    <span id="searchBtnLoader" style="display: none;">⏳ Mencari...</span>
                </button>
            </div>

            <div id="searchLoading" class="search-loading">
                ⏳ Sedang mencari villa yang tersedia...
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
                                    <span style="color: #999;">No image</span>
                                @endif
                            </div>
                            <div class="villa-info">
                                <div class="villa-name">{{ $villa->name }}</div>
                                <div class="villa-capacity">👥 {{ $villa->capacity }} tamu</div>
                                <div class="villa-price">💰 Rp {{ number_format($villa->base_price, 0, ',', '.') }}/malam</div>
                            </div>
                        </div>
                    </a>
                @empty
                    <p style="grid-column: 1 / -1; text-align: center; color: #999; padding: 30px; font-size: 16px;">📭 Tidak
                        ada villa tersedia</p>
                @endforelse
            </div>
        </div>

        <!-- Facility Section -->
        @if ($facilities && (is_array($facilities) ? count($facilities) > 0 : $facilities->count() > 0))
            <div class="facility-section" id="facility">
                <h2 class="section-title">Our Facilities</h2>

                <div class="facility-grid">
                    @foreach ($facilities as $facility)
                        <div class="facility-item" data-category="{{ $facility->category }}">
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

            if (slides[n]) {
                slides[n].classList.add('active');
            }
            if (dots[n]) {
                dots[n].classList.add('active');
            }
            currentSlide = n;
        }

        function nextSlide() {
            if (slides.length === 0) return;
            currentSlide = (currentSlide + 1) % slides.length;
            goToSlide(currentSlide);
        }

        // Auto-rotate carousel every 6 seconds
        if (slides.length > 1) {
            setInterval(nextSlide, 6000);
        }

        function filterFacility(category, element) {
            document.querySelectorAll('.facility-tab').forEach(tab => tab.classList.remove('active'));
            element.classList.add('active');

            const items = document.querySelectorAll('.facility-item');
            items.forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'flex';
                    setTimeout(() => item.style.opacity = '1', 10);
                } else {
                    item.style.opacity = '0';
                    setTimeout(() => item.style.display = 'none', 300);
                }
            });
        }

        // Add transition for facility items
        const style = document.createElement('style');
        style.textContent = '.facility-item { transition: opacity 0.3s ease; }';
        document.head.appendChild(style);

        // Instant search for villas with better UX
        function searchVillas() {
            const capacity = document.getElementById('capacity').value;
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;
            const price = document.getElementById('price').value;

            // Show loading state
            document.getElementById('searchLoading').classList.add('active');
            document.getElementById('searchSubmitBtn').disabled = true;
            document.getElementById('searchBtnText').style.display = 'none';
            document.getElementById('searchBtnLoader').style.display = 'inline';

            const params = new URLSearchParams();
            if (capacity) params.append('capacity', capacity);
            if (checkin) params.append('checkin', checkin);
            if (checkout) params.append('checkout', checkout);
            if (price) params.append('price', price);

            fetch(`/api/villas/search?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    const villaGrid = document.getElementById('villaGrid');
                    villaGrid.innerHTML = '';

                    // Hide loading state
                    document.getElementById('searchLoading').classList.remove('active');
                    document.getElementById('searchSubmitBtn').disabled = false;
                    document.getElementById('searchBtnText').style.display = 'inline';
                    document.getElementById('searchBtnLoader').style.display = 'none';

                    if (data.villas && data.villas.length > 0) {
                        data.villas.forEach((villa, index) => {
                            const villaHTML = `
                                                <a href="/villa/${villa.id}" style="text-decoration: none; color: inherit;">
                                                    <div class="villa-card" style="animation: fadeIn 0.4s ease-in-out ${index * 0.05}s both;">
                                                        <div class="villa-image">
                                                            ${villa.image_url ? `<img src="${villa.image_url}" alt="${villa.name}" style="width:100%; height:100%; object-fit: cover;">` : '<span style="color: #999;">No image</span>'}
                                                        </div>
                                                        <div class="villa-info">
                                                            <div class="villa-name">${villa.name}</div>
                                                            <div class="villa-capacity">👥 ${villa.capacity} tamu</div>
                                                            <div class="villa-price">💰 Rp ${villa.base_price.toLocaleString('id-ID')}/malam</div>
                                                        </div>
                                                    </div>
                                                </a>
                                            `;
                            villaGrid.innerHTML += villaHTML;
                        });
                    } else {
                        villaGrid.innerHTML = `
                                            <div style="grid-column: 1/-1; text-align: center; padding: 40px 20px;">
                                                <p style="font-size: 20px; margin-bottom: 10px;">😔 Tidak ada villa tersedia</p>
                                                <p style="color: #999; margin: 0;">Coba ubah tanggal atau filters yang lain</p>
                                            </div>
                                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('searchLoading').classList.remove('active');
                    document.getElementById('searchSubmitBtn').disabled = false;
                    document.getElementById('searchBtnText').style.display = 'inline';
                    document.getElementById('searchBtnLoader').style.display = 'none';

                    const villaGrid = document.getElementById('villaGrid');
                    villaGrid.innerHTML = `
                                        <div style="grid-column: 1/-1; text-align: center; padding: 40px 20px;">
                                            <p style="font-size: 18px; color: #dc3545; margin-bottom: 10px;">⚠️ Terjadi kesalahan</p>
                                            <p style="color: #999; margin: 0;">Mohon coba lagi nanti</p>
                                        </div>
                                    `;
                });
        }

        // Validate and enforce minimum 1 day stay
        function validateDateRange() {
            const checkinInput = document.getElementById('checkin');
            const checkoutInput = document.getElementById('checkout');

            if (checkinInput.value && checkoutInput.value) {
                const checkinDate = new Date(checkinInput.value);
                const checkoutDate = new Date(checkoutInput.value);

                // If checkout is same day or before checkin, set it to next day
                if (checkoutDate <= checkinDate) {
                    const nextDay = new Date(checkinDate);
                    nextDay.setDate(nextDay.getDate() + 1);
                    checkoutInput.value = nextDay.toISOString().split('T')[0];
                }
            }
        }

        // Load all villas on page load
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-populate check-in with today and check-out with tomorrow
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);

            const todayString = today.toISOString().split('T')[0];
            const tomorrowString = tomorrow.toISOString().split('T')[0];

            const checkinInput = document.getElementById('checkin');
            const checkoutInput = document.getElementById('checkout');

            if (!checkinInput.value) {
                checkinInput.value = todayString;
            }
            if (!checkoutInput.value) {
                checkoutInput.value = tomorrowString;
            }

            searchVillas();
        });

        // Real-time search as user changes filters
        document.getElementById('capacity').addEventListener('change', searchVillas);
        document.getElementById('checkin').addEventListener('change', function () {
            validateDateRange();
            searchVillas();
        });
        document.getElementById('checkout').addEventListener('change', function () {
            validateDateRange();
            searchVillas();
        });
        document.getElementById('price').addEventListener('input', searchVillas);
    </script>
@endsection