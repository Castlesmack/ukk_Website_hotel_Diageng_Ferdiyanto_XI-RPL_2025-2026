@extends('layouts.app')

@section('title', 'Home - Ade Villa')

@push('styles')
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

        /* Image Slider */
        .slider-container {
            position: relative;
            width: 100%;
            height: 300px;
            background: #FAF2E8;
            border: 2px solid #333;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 40px;
        }

        .slider {
            display: flex;
            width: 100%;
            height: 100%;
            position: relative;
            transition: transform 0.5s ease;
        }

        .slide {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            flex-shrink: 0;
        }

        .slide-dots {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #000;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        .dot.active {
            background: #333;
        }

        /* Description Section */
        .description-section {
            background: #FAF2E8;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 40px;
            text-align: center;
            line-height: 1.6;
            color: #333;
            font-size: 14px;
        }

        .description-section h3 {
            margin-bottom: 15px;
            font-size: 18px;
        }

        /* Section Headers */
        .section-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        /* Search Section */
        .search-section {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .search-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
            align-items: flex-end;
        }

        .search-form input,
        .search-form select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-form button {
            padding: 10px 25px;
            background: #f05b4f;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .search-form button:hover {
            background: #d84539;
        }

        /* Villa Grid */
        .villa-section {
            margin-bottom: 50px;
        }

        .villa-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .villa-card {
            border: 2px solid #333;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            background: white;
            cursor: pointer;
        }

        .villa-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .villa-card a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .villa-card-header {
            background: #000;
            color: white;
            padding: 12px;
            font-weight: bold;
            font-size: 14px;
        }

        .villa-card-header-small {
            font-size: 12px;
            opacity: 0.8;
            margin-top: 4px;
        }

        .villa-card-image {
            width: 100%;
            height: 150px;
            background: #FAF2E8;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 12px;
        }

        .villa-card-body {
            padding: 15px;
        }

        .villa-card-body h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            font-weight: bold;
        }

        .villa-info {
            font-size: 13px;
            color: #666;
            margin: 5px 0;
        }

        .no-results {
            text-align: center;
            padding: 40px;
            color: #999;
            grid-column: 1 / -1;
        }

        /* Facilities Section */
        .facility-section {
            background: #FAF2E8;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 30px;
            margin-top: 50px;
        }

        .facility-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .facility-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .facility-icon {
            font-size: 24px;
            min-width: 30px;
            text-align: center;
        }

        .facility-text {
            font-size: 13px;
            color: #333;
            line-height: 1.4;
        }

        .facility-text strong {
            display: block;
            margin-bottom: 4px;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
            color: #666;
            grid-column: 1 / -1;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <!-- Image Slider -->
        <div class="slider-container">
            <div class="slider" id="slider">
                <div class="slide">Slide 1</div>
                <div class="slide">Slide 2</div>
                <div class="slide">Slide 3</div>
            </div>
            <div class="slide-dots" id="slideDots">
                <span class="dot active" data-index="0" onclick="goToSlide(0)"></span>
                <span class="dot" data-index="1" onclick="goToSlide(1)"></span>
                <span class="dot" data-index="2" onclick="goToSlide(2)"></span>
            </div>
        </div>

        <!-- Description Section -->
        <div class="description-section">
            <h3>Selamat Datang di Villa Ade Kota Bunga</h3>
            <p>
                Penyewaan villa tipe Jasping di Kota Bunga Puncak. Kami menyediakan villa yang nyaman, asri, bersih,
                serta dilengkapi dengan berbagai fasilitas mewah. Fasilitas yang kami sediakan penuh dan lengkap untuk anda.
                Segera booking villa pilihan anda, kami tunggu kedatangan Anda.<br><br>

                Bersama kami Nikmati Desain Alami Hijaunya Villa Kota Bunga Ade, Cipeuas.<br>
                Villa dengan Kelengkapan Fasilitas seperti di rumah sendiri.<br>
                Villa di lembang gunung dengan suasana Mempersonik.
            </p>
        </div>

        <!-- Villa Section -->
        <div class="villa-section">
            <div class="section-title">Villa</div>

            <!-- Search Form -->
            <div class="search-section">
                <form class="search-form" id="searchForm">
                    <input type="text" id="kamarTidur" name="kamarTidur" placeholder="Kamar Tidur">
                    <input type="date" id="tanggal" name="tanggal" placeholder="Tanggal">
                    <input type="number" id="harga" name="harga" placeholder="Harga" min="0">
                    <button type="submit">Search</button>
                </form>
            </div>

            <!-- Villa Grid -->
            <div class="loading" id="loading">Loading villas...</div>
            <div class="villa-grid" id="villaGrid">
                @forelse($villas ?? [] as $villa)
                    <div class="villa-card">
                        <a href="{{ route('guest.villa.detail', $villa->id) }}">
                            <div class="villa-card-header">
                                {{ $villa->name }}
                                <div class="villa-card-header-small">{{ $villa->status }}</div>
                            </div>
                            <div class="villa-card-image">
                                No Image
                            </div>
                            <div class="villa-card-body">
                                <h3>Kapasitas {{ $villa->capacity }} orang</h3>
                                <div class="villa-info">
                                    <strong>Harga:</strong> Rp {{ number_format($villa->base_price, 0, ',', '.') }}
                                </div>
                                <div class="villa-info">
                                    <strong>Kamar:</strong> {{ $villa->rooms_total }}
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="no-results">No villas found</div>
                @endforelse
            </div>
        </div>

        <!-- Facilities Section -->
        <div class="facility-section">
            <div class="section-title">Facility</div>
            <div class="facility-grid">
                <div class="facility-item">
                    <div class="facility-icon">🏢</div>
                    <div class="facility-text">
                        <strong>Public Facilities</strong>
                        Parking area
                    </div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">🎯</div>
                    <div class="facility-text">
                        <strong>Other Activities</strong>
                        Garden
                    </div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">📡</div>
                    <div class="facility-text">
                        <strong>Connectivity</strong>
                        In-room internet
                    </div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">🚴</div>
                    <div class="facility-text">
                        <strong>Transportation</strong>
                        Bicycle rental
                    </div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">📶</div>
                    <div class="facility-text">
                        <strong>WiFi</strong>
                        WiFi in public areas
                    </div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon">🏊</div>
                    <div class="facility-text">
                        <strong>Swimming Pool</strong>
                        Private pool access
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentSlide = 0;
            const slides = document.querySelectorAll('.slide');
            const totalSlides = slides.length;

            function showSlide(index) {
                const slider = document.getElementById('slider');
                slider.style.transform = `translateX(-${index * 100}%)`;

                document.querySelectorAll('.dot').forEach(dot => dot.classList.remove('active'));
                document.querySelector(`.dot[data-index="${index}"]`).classList.add('active');
            }

            function goToSlide(index) {
                currentSlide = index;
                showSlide(currentSlide);
            }

            // Auto advance slides every 5 seconds
            setInterval(() => {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }, 5000);

            // Search functionality
            document.getElementById('searchForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const kamarTidur = document.getElementById('kamarTidur').value;
                const tanggal = document.getElementById('tanggal').value;
                const harga = document.getElementById('harga').value;

                const loading = document.getElementById('loading');
                const villaGrid = document.getElementById('villaGrid');

                loading.style.display = 'block';
                villaGrid.innerHTML = '';

                const params = new URLSearchParams();
                if (kamarTidur) params.append('capacity', kamarTidur);
                if (tanggal) params.append('checkin', tanggal);
                if (harga) params.append('price', harga);

                fetch(`/villa/search?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        loading.style.display = 'none';

                        if (data.villas && data.villas.length > 0) {
                            villaGrid.innerHTML = data.villas.map(villa => `
                                        <div class="villa-card">
                                            <a href="/villa/${villa.id}">
                                                <div class="villa-card-header">
                                                    ${villa.name}
                                                    <div class="villa-card-header-small">${villa.status}</div>
                                                </div>
                                                <div class="villa-card-image">
                                                    No Image
                                                </div>
                                                <div class="villa-card-body">
                                                    <h3>Kapasitas ${villa.capacity} orang</h3>
                                                    <div class="villa-info">
                                                        <strong>Harga:</strong> Rp ${new Intl.NumberFormat('id-ID').format(villa.base_price)}
                                                    </div>
                                                    <div class="villa-info">
                                                        <strong>Kamar:</strong> ${villa.rooms_total}
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    `).join('');
                        } else {
                            villaGrid.innerHTML = '<div class="no-results">No villas found matching your criteria</div>';
                        }
                    })
                    .catch(error => {
                        loading.style.display = 'none';
                        villaGrid.innerHTML = '<div class="no-results">Error loading villas</div>';
                        console.error('Error:', error);
                    });
            });
        </script>
    @endpush
@endsection