@extends('layouts.app')

@section('title', 'My Bookings')

@push('styles')
    <style>
        .bookings-layout {
            display: flex;
            gap: 20px;
            margin: 20px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .sidebar {
            width: 200px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            background: #f8f9fa;
            height: fit-content;
        }

        .sidebar .menu-item {
            display: block;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
        }

        .sidebar .menu-item:hover {
            background: #e9ecef;
        }

        .sidebar .menu-item.active {
            background: #007bff;
            color: white;
        }

        .main-content {
            flex: 1;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 30px;
            background: white;
        }

        .main-content h2 {
            margin-top: 0;
            margin-bottom: 25px;
            color: #333;
            font-size: 24px;
            font-weight: 600;
        }

        .booking-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .booking-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .booking-card h4 {
            margin: 0 0 15px;
            color: #007bff;
            font-size: 16px;
            font-weight: 600;
        }

        .booking-details {
            margin: 0;
        }

        .booking-details div {
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }

        .booking-details strong {
            color: #495057;
            font-weight: 600;
            min-width: 100px;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status.confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .no-bookings {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 60px 40px;
            background: #f8f9fa;
            border-radius: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="bookings-layout">
        <aside class="sidebar">
            <a href="{{ route('user.profile') }}" class="menu-item">Profile</a>
            <a href="{{ route('user.bookings') }}" class="menu-item active">My Bookings</a>
        </aside>

        <main class="main-content">
            <h2>My Bookings</h2>

            @if ($bookings && $bookings->count() > 0)
                @foreach ($bookings as $booking)
                    <div class="booking-card">
                        <h4>Order ID: {{ $booking->booking_code }}</h4>
                        <div class="booking-details">
                            <div><strong>Villa:</strong> {{ $booking->villa->name }}</div>
                            <div><strong>Guests:</strong> {{ $booking->guests }}</div>
                            <div><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d/m/Y') }}
                            </div>
                            <div><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d/m/Y') }}
                            </div>
                            <div><strong>Nights:</strong> {{ $booking->nights }}</div>
                            <div><strong>Total Price:</strong> Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                            <div><strong>Status:</strong> <span
                                    class="status {{ strtolower($booking->status) }}">{{ ucfirst($booking->status) }}</span></div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-bookings">
                    <p>You have no bookings yet.</p>
                </div>
            @endif
        </main>
    </div>
@endsection