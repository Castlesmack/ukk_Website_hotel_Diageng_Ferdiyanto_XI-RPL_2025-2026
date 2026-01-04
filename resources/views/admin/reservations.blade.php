@extends('layouts.app')

@section('title', 'Reservations - Admin')

@push('styles')
    <style>
        .admin-layout {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .sidebar {
            width: 250px;
            background: #343a40;
            color: white;
            padding: 20px;
            border-radius: 8px;
            height: fit-content;
            flex-shrink: 0;
        }

        .sidebar h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #fff;
            font-size: 18px;
            border-bottom: 2px solid #495057;
            padding-bottom: 15px;
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sidebar .menu-item {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            color: #333;
            text-decoration: none;
            border: none;
            font-size: 14px;
            font-weight: 500;
            background: #e9ecef;
            text-align: center;
        }

        .sidebar .menu-item:hover {
            background: #007bff;
            color: white;
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .sidebar .menu-item.active {
            background: #007bff;
            color: white;
            font-weight: 600;
        }

        .main-content {
            flex: 1;
        }

        .filter-section {
            background: white;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .filter-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr auto;
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
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #007bff;
        }

        .filter-dates {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .go-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .go-btn:hover {
            background: #0056b3;
        }

        .reservation-table {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .reservation-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .reservation-table th,
        .reservation-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
        }

        .reservation-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .reservation-table tr:hover {
            background: #f8f9fa;
        }

        .status {
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

        .status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .actions {
            display: flex;
            gap: 5px;
        }

        .action-btn {
            padding: 6px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
        }

        .view-btn {
            background: #007bff;
            color: white;
        }

        .view-btn:hover {
            background: #0056b3;
        }

        .edit-btn {
            background: #28a745;
            color: white;
        }

        .edit-btn:hover {
            background: #218838;
        }
    </style>
@endpush

@section('content')
    <div class="admin-layout">
        <aside class="sidebar">
            <h3>Ade Villa Admin</h3>
            <nav>
                <a href="/admin/dashboard" class="menu-item">Dashboard</a>
                <a href="/admin/manage" class="menu-item">Manage</a>
                <a href="/admin/reservations" class="menu-item active">Reservations</a>
                <a href="/admin/users" class="menu-item">Users</a>
                <a href="/admin/finances" class="menu-item">Finance</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="filter-section">
                <form method="GET" action="{{ route('admin.reservations') }}">
                    <div class="filter-row">
                        <div class="filter-group" style="grid-column: span 2;">
                            <label for="booking">Booking ID/Name</label>
                            <input type="text" id="booking" name="booking" placeholder="">
                        </div>

                        <div class="filter-group">
                            <label for="date_type">Date of</label>
                            <select id="date_type" name="date_type">
                                <option value="check-in" selected>Check-in</option>
                                <option value="check-out">Check-out</option>
                            </select>
                        </div>

                        <div class="filter-group" style="grid-column: span 2;">
                            <label>Start Date - End Date</label>
                            <div class="filter-dates">
                                <input type="date" name="start_date" value="{{ request('start_date') }}">
                                <input type="date" name="end_date" value="{{ request('end_date') }}">
                            </div>
                        </div>

                        <button type="submit" class="go-btn">GO</button>
                    </div>
                </form>
            </div>

            <div class="reservation-table">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Booking ID</th>
                            <th>No Telp</th>
                            <th>Guest Name</th>
                            <th>Booking Time</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Tipe</th>
                            <th>Tamu</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $index => $booking)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $booking->booking_code }}</td>
                                <td>{{ $booking->user->phone ?? '-' }}</td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ $booking->created_at->format('D, d M, y') }}<br>{{ $booking->created_at->format('h:i A') }}
                                </td>
                                <td>{{ $booking->check_in_date->format('D, d M, y') }}</td>
                                <td>{{ $booking->check_out_date->format('D, d M, y') }}</td>
                                <td>{{ $booking->villa->slug ?? '-' }}</td>
                                <td>{{ $booking->guests }}</td>
                                <td>Rp {{ number_format($booking->total_price, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="text-align: center; padding: 20px;">No bookings found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div style="margin-top: 20px; text-align: center;">
                    {{ $bookings->links() }}
                </div>
            @endif
        </main>
    </div>
@endsection