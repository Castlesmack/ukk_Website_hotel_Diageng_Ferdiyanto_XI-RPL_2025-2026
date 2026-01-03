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

            <div class="filters">
                <input type="text" placeholder="Itinerary ID/Name">
                <select>
                    <option>Check-In</option>
                    <option>Check-Out</option>
                </select>
                <input type="date">
                <input type="date">
                <button>Search</button>
            </div>

            <div class="reservation-table">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Itinerary ID</th>
                            <th>Phone</th>
                            <th>Guest Name</th>
                            <th>Booking Time</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Type</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>XXXX</td>
                            <td>081234567890</td>
                            <td>John Doe</td>
                            <td>Sun, 10 Nov</td>
                            <td>Sat, 22 Nov</td>
                            <td>Sun, 23 Nov</td>
                            <td>0608</td>
                            <td>Rp 800,000</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>YYYY</td>
                            <td>081234567891</td>
                            <td>Jane Smith</td>
                            <td>Mon, 11 Nov</td>
                            <td>Sun, 24 Nov</td>
                            <td>Mon, 25 Nov</td>
                            <td>0608</td>
                            <td>Rp 800,000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
@endsection