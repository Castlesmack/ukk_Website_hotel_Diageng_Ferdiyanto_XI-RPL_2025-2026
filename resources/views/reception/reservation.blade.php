@extends('layouts.app')

@section('title', 'Reception Reservations')

@push('styles')
    <style>
        .reception-layout {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .sidebar {
            width: 250px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .sidebar h3 {
            margin-top: 0;
            color: #007bff;
        }

        .sidebar .menu-item {
            display: block;
            padding: 12px;
            margin-bottom: 8px;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            transition: background 0.3s;
        }

        .sidebar .menu-item:hover,
        .sidebar .menu-item.active {
            background: #e9ecef;
        }

        .main-content {
            flex: 1;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .header h2 {
            margin: 0;
            color: #007bff;
        }

        .filters {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filters input,
        .filters select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .filters button {
            padding: 8px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .filters button:hover {
            background: #0056b3;
        }

        .reservation-table {
            background: white;
            border: 1px solid #e9ecef;
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
            border-bottom: 1px solid #e9ecef;
        }

        .reservation-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        .reservation-table tr:hover {
            background: #f8f9fa;
        }
    </style>
@endpush

@section('content')
    <div class="reception-layout">
        <aside class="sidebar">
            <h3>Ade Villa Reception</h3>
            <a href="/reception/dashboard" class="menu-item">Dashboard</a>
            <a href="/reception/reservations" class="menu-item active">Reservations</a>
            <a href="/reception/calendar" class="menu-item">Calendar</a>
        </aside>

        <main class="main-content">
            <div class="header">
                <h2>Reservations</h2>
            </div>

            <form method="GET" action="{{ route('reception.reservations') }}">
                <div class="filters"
                    style="display: grid; grid-template-columns: auto auto auto auto auto auto; gap: 15px; align-items: flex-end; margin-bottom: 20px;">
                    <div style="display: flex; flex-direction: column;">
                        <label style="font-size: 12px; color: #666; margin-bottom: 5px;">Itinerary ID/Name</label>
                        <input type="text" name="itinerary" placeholder="" value="{{ request('itinerary') }}"
                            style="padding: 10px; border: 1px solid #ccc; border-radius: 4px; width: 150px;">
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <label style="font-size: 12px; color: #666; margin-bottom: 5px;">Date of</label>
                        <select name="date_type"
                            style="padding: 10px; border: 1px solid #ccc; border-radius: 4px; width: 150px;">
                            <option value="check_in" {{ request('date_type') == 'check_in' ? 'selected' : '' }}>Check-in
                            </option>
                            <option value="check_out" {{ request('date_type') == 'check_out' ? 'selected' : '' }}>Check-Out
                            </option>
                        </select>
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <label style="font-size: 12px; color: #666; margin-bottom: 5px;">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            style="padding: 10px; border: 1px solid #ccc; border-radius: 4px; width: 150px;">
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <label style="font-size: 12px; color: #666; margin-bottom: 5px;">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            style="padding: 10px; border: 1px solid #ccc; border-radius: 4px; width: 150px;">
                    </div>
                    <div></div>
                    <button type="submit"
                        style="padding: 10px 25px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">GO</button>
                </div>
            </form>

            <div class="reservation-table">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Itinerary ID</th>
                            <th>No Telp</th>
                            <th>Guest Name</th>
                            <th>Booking Time</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Tipe</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->booking_code }}</td>
                                <td>{{ $booking->guest_phone }}</td>
                                <td>{{ $booking->guest_name }}</td>
                                <td>{{ $booking->created_at->format('D, d M y') }}</td>
                                <td>{{ $booking->check_in_date->format('D, d M y') }}</td>
                                <td>{{ $booking->check_out_date->format('D, d M y') }}</td>
                                <td>{{ $booking->villa->name ?? 'N/A' }}</td>
                                <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 20px; color: #999;">No bookings found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div style="margin-top: 20px;">
                    {{ $bookings->links() }}
                </div>
            @endif
        </main>
    </div>
@endsection