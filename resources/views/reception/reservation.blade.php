@extends('layouts.app')

@section('title', 'Reception Reservations')

@push('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .reception-layout {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            margin-bottom: 40px;
        }

        .sidebar {
            width: 280px;
            background: #1a1a1a;
            padding: 25px 20px;
            border-radius: 12px;
            height: fit-content;
            position: sticky;
            top: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .sidebar h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: white;
            font-size: 18px;
            font-weight: 700;
        }

        .sidebar .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 8px;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s;
            font-weight: 500;
            font-size: 14px;
        }

        .sidebar .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar .menu-item.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left: 3px solid #A0522D;
            padding-left: 12px;
        }

        .sidebar .menu-item::before {
            content: '›';
            margin-right: 10px;
            font-size: 18px;
        }

        .main-content {
            flex: 1;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .header h2 {
            margin: 0;
            color: #2c3e50;
            font-size: 28px;
            font-weight: 700;
        }

        .filter-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            align-items: flex-end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-size: 12px;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-group input,
        .filter-group select {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .filter-button {
            padding: 10px 25px;
            background: #A0522D;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .filter-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(160, 82, 45, 0.4);
        }

        .reservation-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .reservation-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .reservation-table th {
            background: #1a1a1a;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .reservation-table td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        .reservation-table tbody tr {
            transition: background-color 0.2s;
        }

        .reservation-table tbody tr:hover {
            background-color: #f8f9ff;
        }

        .booking-code {
            font-family: monospace;
            font-weight: 600;
            color: #000;
            background: #FAF2E8;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .guest-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .villa-name {
            color: #000;
            font-weight: 500;
        }

        .date-cell {
            font-size: 13px;
            color: #666;
        }

        .price {
            font-weight: 600;
            color: #27ae60;
            font-size: 13px;
        }

        .phone {
            font-size: 13px;
            color: #7f8c8d;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-decoration: none;
            color: #000;
            transition: all 0.3s;
        }

        .pagination a:hover {
            background: #A0522D;
            color: white;
        }

        .pagination .active {
            background: #A0522D;
            color: white;
            border-color: #A0522D;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #95a5a6;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 15px;
        }

        .empty-state-text {
            font-size: 16px;
            margin-bottom: 10px;
        }

        @media (max-width: 1024px) {
            .filter-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .reception-layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                position: static;
                display: flex;
                gap: 10px;
            }

            .sidebar h3 {
                margin-bottom: 0;
            }

            .sidebar .menu-item {
                flex: 1;
                margin-bottom: 0;
                justify-content: center;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .reservation-table {
                overflow-x: auto;
            }

            .reservation-table table {
                min-width: 600px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="reception-layout">
        <aside class="sidebar">
            <h3>Reception</h3>
            <a href="/reception/dashboard" class="menu-item">Dashboard</a>
            <a href="/reception/reservations" class="menu-item active">Reservations</a>
            <a href="/reception/calendar" class="menu-item">Calendar</a>
        </aside>

        <main class="main-content">
            <div class="header">
                <div>
                    <h2>Reservations</h2>
                    <p style="margin: 5px 0 0 0; color: #7f8c8d; font-size: 14px;">
                        Manage and track all guest bookings
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ route('reception.reservations') }}" class="filter-card">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label>Booking Code/Guest Name</label>
                        <input type="text" name="itinerary" placeholder="e.g., ADV-001 or John Doe"
                            value="{{ request('itinerary') }}">
                    </div>
                    <div class="filter-group">
                        <label>Date Type</label>
                        <select name="date_type">
                            <option value="check_in" {{ request('date_type') == 'check_in' ? 'selected' : '' }}>Check-in
                            </option>
                            <option value="check_out" {{ request('date_type') == 'check_out' ? 'selected' : '' }}>Check-out
                            </option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="filter-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <button type="submit" class="filter-button" style="align-self: flex-end;">
                        🔍 Search
                    </button>
                </div>
            </form>

            @if($bookings->count() > 0)
                <div class="reservation-table">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking Code</th>
                                <th>Guest Name</th>
                                <th>Phone</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Villa</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>{{ $bookings->currentPage() * 15 - 15 + $loop->iteration }}</td>
                                    <td><span class="booking-code">{{ $booking->booking_code }}</span></td>
                                    <td><span class="guest-name">{{ $booking->guest_name }}</span></td>
                                    <td><span class="phone">{{ $booking->guest_phone }}</span></td>
                                    <td><span class="date-cell">{{ $booking->check_in_date?->format('D, d M y') ?? '-' }}</span>
                                    </td>
                                    <td><span class="date-cell">{{ $booking->check_out_date?->format('D, d M y') ?? '-' }}</span>
                                    </td>
                                    <td><span class="villa-name">{{ $booking->villa->name ?? '-' }}</span></td>
                                    <td><span class="price">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($bookings->hasPages())
                    <div class="pagination">
                        {{ $bookings->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <div class="empty-state-text">No reservations found</div>
                    <p style="color: #bdc3c7; font-size: 13px;">Try adjusting your search filters</p>
                </div>
            @endif
        </main>
    </div>
@endsection