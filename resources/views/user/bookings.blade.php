@extends('layouts.app')

@section('title', 'My Bookings')

@push('styles')
    <style>
        .bookings-layout {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .sidebar {
            width: 250px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
        }

        .sidebar h3 {
            margin-top: 0;
            color: #007bff;
        }

        .menu-item {
            display: block;
            padding: 12px;
            margin-bottom: 8px;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            transition: background 0.3s;
        }

        .menu-item:hover,
        .menu-item.active {
            background: #e9ecef;
        }

        .main-content {
            flex: 1;
        }

        .booking-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .booking-card h4 {
            margin-top: 0;
            color: #007bff;
        }

        .booking-details {
            margin: 10px 0;
        }

        .booking-details div {
            margin-bottom: 5px;
        }

        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .status.confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .no-bookings {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 40px;
        }
    </style>
@endpush

@section('content')
    <div class="bookings-layout">
        <aside class="sidebar">
            <h3>My Account</h3>
            <a href="/user/profile" class="menu-item">Profile</a>
            <a href="/user/bookings" class="menu-item active">My Bookings</a>
        </aside>

        <main class="main-content">
            <h2>My Bookings</h2>

            <div class="booking-card">
                <h4>Order ID: 14524621</h4>
                <div class="booking-details">
                    <div><strong>Villa:</strong> Villa Tipe - 0608</div>
                    <div><strong>Guests:</strong> 2</div>
                    <div><strong>Dates:</strong> 20/12/2025 - 21/12/2025</div>
                    <div><strong>Status:</strong> <span class="status confirmed">Confirmed</span></div>
                </div>
            </div>

            <div class="booking-card">
                <h4>Order ID: 14524622</h4>
                <div class="booking-details">
                    <div><strong>Villa:</strong> Villa Puncak Harmony</div>
                    <div><strong>Guests:</strong> 4</div>
                    <div><strong>Dates:</strong> 15/01/2026 - 18/01/2026</div>
                    <div><strong>Status:</strong> <span class="status pending">Pending</span></div>
                </div>
            </div>
        </main>
    </div>
@endsection