@extends('layouts.app')

@section('title', 'Search Villas')

@push('styles')
    <style>
        .search-results {
            margin-top: 20px;
        }

        .search-header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-header h2 {
            margin-top: 0;
            color: #007bff;
        }

        .search-params {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-params div {
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 4px;
            font-size: 14px;
        }

        .villa-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .villa-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .villa-card:hover {
            transform: translateY(-2px);
        }

        .villa-image {
            height: 200px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }

        .villa-content {
            padding: 15px;
        }

        .villa-content h3 {
            margin-top: 0;
            color: #007bff;
        }

        .villa-price {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
            margin: 10px 0;
        }

        .villa-description {
            color: #6c757d;
            margin-bottom: 15px;
        }

        .book-btn {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .book-btn:hover {
            background: #0056b3;
        }

        .no-results {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <div class="search-results">
        <div class="search-header">
            <h2>Search Results</h2>
            <div class="search-params">
                @if($checkin)
                    <div><strong>Check-in:</strong> {{ $checkin }}</div>
                @endif
                @if($checkout)
                    <div><strong>Check-out:</strong> {{ $checkout }}</div>
                @endif
                <div><strong>Guests:</strong> {{ $guests }}</div>
            </div>
        </div>

        @if($villas->count() > 0)
            <div class="villa-grid">
                @foreach($villas as $villa)
                    <div class="villa-card">
                        <div class="villa-image">
                            [Villa Image]
                        </div>
                        <div class="villa-content">
                            <h3>{{ $villa->name }}</h3>
                            <div class="villa-price">Rp {{ number_format($villa->price, 0, ',', '.') }} / night</div>
                            <div class="villa-description">{{ $villa->description }}</div>
                            <a href="/villa/{{ $villa->id }}" class="book-btn">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-results">
                <h3>No villas found</h3>
                <p>Try adjusting your search criteria.</p>
            </div>
        @endif
    </div>
@endsection