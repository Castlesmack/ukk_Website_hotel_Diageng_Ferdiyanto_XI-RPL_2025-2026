@extends('layouts.app')

@section('title', 'Villas')

@push('styles')
    <style>
        .villa-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .villa-header {
            margin-bottom: 30px;
        }

        .villa-header h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .filter-section {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: flex-end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .filter-group input,
        .filter-group select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .search-btn:hover {
            background: #0056b3;
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
            cursor: pointer;
            background: white;
        }

        .villa-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
            background: #f0f0f0;
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

        .villa-price {
            margin-top: 10px;
            font-size: 12px;
            color: #007bff;
            font-weight: bold;
        }

        .no-villas {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
@endpush

@section('content')
    <div class="villa-container">
        <div class="villa-header">
            <h1>Villa</h1>
        </div>

        <div class="filter-section">
            <form method="GET" action="{{ route('villa.search') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="capacity">Kapasitas</label>
                        <input type="number" id="capacity" name="capacity" placeholder="Min guests"
                            value="{{ request('capacity') }}">
                    </div>

                    <div class="filter-group">
                        <label for="checkin">Tanggal</label>
                        <input type="date" id="checkin" name="checkin" value="{{ $checkin }}">
                    </div>

                    <div class="filter-group">
                        <label for="price">Harga</label>
                        <input type="number" id="price" name="price" placeholder="Max price" value="{{ request('price') }}">
                    </div>

                    <button type="submit" class="search-btn">Search</button>
                </div>
            </form>
        </div>

        @if($villas->count() > 0)
            <div class="villa-grid">
                @foreach($villas as $villa)
                    <a href="{{ route('guest.villa.detail', $villa->id) }}" style="text-decoration: none; color: inherit;">
                        <div class="villa-card">
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
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="no-villas">
                <p>No villas found matching your criteria.</p>
            </div>
        @endif
    </div>
@endsection