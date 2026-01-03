@extends('layouts.app')

@section('title', 'Home - Ade Villa')

@push('styles')
    <style>
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .hero h1 {
            margin: 0 0 10px;
            font-size: 2.5em;
        }

        .hero p {
            font-size: 1.2em;
            margin: 0;
        }

        .section {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .section h2 {
            margin-top: 0;
            color: #007bff;
        }

        .villa-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .villa-card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-form input,
        .search-form select,
        .search-form button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-form button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .search-form button:hover {
            background: #0056b3;
        }

        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }

            .villa-card {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="hero">
        <h1>Welcome to Ade Villa</h1>
        <p>Discover and book beautiful villas for your perfect getaway.</p>
    </div>

    <div class="section">
        <h2>Search & Book Your Villa</h2>
        <form class="search-form" action="/villa" method="GET">
            <input type="date" name="checkin" placeholder="Check-in" required>
            <input type="date" name="checkout" placeholder="Check-out" required>
            <select name="guests">
                <option value="1">1 Guest</option>
                <option value="2">2 Guests</option>
                <option value="4">4 Guests</option>
                <option value="6">6 Guests</option>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="section">
        <h2>Featured Villas</h2>
        <div class="villa-card">
            <img src="/storage/villa/sample-1.jpg" alt="Villa Sample">
            <div>
                <h3>Villa Kota Bunga Ade</h3>
                <p>Comfortable villa close to the city with garden and pool.</p>
                <p><strong>Rp 5,104,000 / night</strong></p>
                <a href="/villa/1" style="color: #007bff;">View Details</a>
            </div>
        </div>
        <div class="villa-card">
            <img src="/storage/villa/sample-2.jpg" alt="Villa Sample">
            <div>
                <h3>Villa Puncak Harmony</h3>
                <p>Spacious villa with panoramic mountain views.</p>
                <p><strong>Rp 7,200,000 / night</strong></p>
                <a href="/villa/2" style="color: #007bff;">View Details</a>
            </div>
        </div>
    </div>
@endsection