@extends('layouts.app')

@section('title', 'My Bookings')

@push('styles')
    <style>
        * {
            box-sizing: border-box;
        }

        .bookings-layout {
            display: flex;
            gap: 30px;
            margin: 40px auto;
            max-width: 1300px;
            padding: 0 20px;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 240px;
            flex-shrink: 0;
        }

        .sidebar-card {
            background: #FAF2E8;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .sidebar-header {
            background: linear-gradient(135deg, #f5f6f8 0%, #f8f9fa 100%);
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .sidebar-header h3 {
            margin: 0;
            color: #333;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            padding: 8px;
        }

        .sidebar .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            margin-bottom: 4px;
            border-radius: 8px;
            text-decoration: none;
            color: #495057;
            transition: all 0.2s ease;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .sidebar .menu-item:hover {
            background: #f5f6f8;
            color: #333;
            border-color: #e9ecef;
        }

        .sidebar .menu-item.active {
            background: #f05b4f;
            color: white;
            border-color: #f05b4f;
        }

        .menu-icon {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            min-width: 0;
        }

        .content-header {
            margin-bottom: 35px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
        }

        .content-header h2 {
            margin: 0;
            color: #1a1a1a;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .content-header p {
            margin: 8px 0 0 0;
            color: #6c757d;
            font-size: 14px;
        }

        /* Booking Cards */
        .bookings-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .booking-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 24px;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .booking-card:hover {
            border-color: #d0d8e0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
        }

        .booking-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: #f05b4f;
            border-radius: 12px 0 0 12px;
        }

        .booking-card {
            position: relative;
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 22px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f0f0f0;
        }

        .card-title-section {
            flex: 1;
        }

        .booking-code {
            font-size: 11px;
            font-weight: 700;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
        }

        .booking-villa-name {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        .status {
            display: inline-flex;
            align-items: center;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
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

        /* Booking Details Grid */
        .booking-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 0;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .detail-label {
            font-size: 12px;
            font-weight: 700;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 15px;
            font-weight: 600;
            color: #333;
        }

        .detail-value.price {
            color: #f05b4f;
            font-size: 16px;
        }

        /* Empty State */
        .no-bookings {
            text-align: center;
            padding: 80px 40px;
            background: #FAF2E8;
            border: 2px dashed #e9ecef;
            border-radius: 12px;
        }

        .no-bookings-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .no-bookings p {
            margin: 0;
            color: #6c757d;
            font-size: 16px;
            font-weight: 500;
        }

        .no-bookings-action {
            margin-top: 20px;
        }

        .no-bookings-action a {
            display: inline-block;
            padding: 12px 28px;
            background: #f05b4f;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .no-bookings-action a:hover {
            background: #d84539;
            transform: translateY(-1px);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .bookings-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .bookings-layout {
                flex-direction: column;
                gap: 20px;
            }

            .sidebar {
                width: 100%;
            }

            .bookings-container {
                grid-template-columns: 1fr;
            }

            .booking-details {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .content-header h2 {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .bookings-layout {
                margin: 20px auto;
                padding: 0 12px;
            }

            .booking-card {
                padding: 16px;
            }

            .booking-details {
                grid-template-columns: 1fr;
            }

            .card-top {
                flex-direction: column;
                gap: 12px;
            }

            .status {
                align-self: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div class="bookings-layout">
        <aside class="sidebar">
            <div class="sidebar-card">
                <div class="sidebar-header">
                    <h3>📋 Menu</h3>
                </div>
                <div class="sidebar-menu">
                    <a href="{{ route('user.profile') }}" class="menu-item">
                        <span class="menu-icon">👤</span>
                        <span>Profile</span>
                    </a>
                    <a href="{{ route('user.bookings') }}" class="menu-item active">
                        <span class="menu-icon">🗓️</span>
                        <span>My Bookings</span>
                    </a>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <div class="content-header">
                <h2>My Bookings</h2>
                <p>Manage and track your villa reservations</p>
            </div>

            @if ($bookings && $bookings->count() > 0)
                <div class="bookings-container">
                    @foreach ($bookings as $booking)
                        <div class="booking-card">
                            <div class="card-top">
                                <div class="card-title-section">
                                    <div class="booking-code">Booking #{{ substr($booking->booking_code, -6) }}</div>
                                    <h4 class="booking-villa-name">{{ $booking->villa->name }}</h4>
                                </div>
                                <span class="status {{ strtolower($booking->status) }}">{{ ucfirst($booking->status) }}</span>
                            </div>

                            <div class="booking-details">
                                <div class="detail-item">
                                    <span class="detail-label">👥 Guests</span>
                                    <span class="detail-value">{{ $booking->guests }}
                                        {{ $booking->guests > 1 ? 'People' : 'Person' }}</span>
                                </div>

                                <div class="detail-item">
                                    <span class="detail-label">📅 Check-in</span>
                                    <span
                                        class="detail-value">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</span>
                                </div>

                                <div class="detail-item">
                                    <span class="detail-label">📅 Check-out</span>
                                    <span
                                        class="detail-value">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</span>
                                </div>

                                <div class="detail-item">
                                    <span class="detail-label">🌙 Nights</span>
                                    <span class="detail-value">{{ $booking->nights }}
                                        {{ $booking->nights > 1 ? 'Nights' : 'Night' }}</span>
                                </div>

                                <div class="detail-item">
                                    <span class="detail-label">💰 Total Price</span>
                                    <span class="detail-value price">Rp
                                        {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                </div>

                                <div class="detail-item">
                                    <span class="detail-label">👤 Guest Name</span>
                                    <span class="detail-value">{{ $booking->guest_name }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-bookings">
                    <div class="no-bookings-icon">📭</div>
                    <p>You have no bookings yet.</p>
                    <p style="margin: 8px 0 0 0; font-size: 14px; color: #999;">Start your next adventure by booking a villa</p>
                    <div class="no-bookings-action">
                        <a href="/">Browse Villas</a>
                    </div>
                </div>
            @endif
        </main>
    </div>
@endsection