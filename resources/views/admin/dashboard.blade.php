@extends('layouts.app')

@section('title', 'Admin Dashboard')

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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .header h2 {
            margin: 0;
            color: #333;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-top: 4px solid #007bff;
        }

        .stat-card h3 {
            margin: 0 0 10px;
            color: #6c757d;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card strong {
            font-size: 28px;
            color: #007bff;
            display: block;
        }

        .stat-card.revenue {
            border-top-color: #28a745;
        }

        .stat-card.revenue strong {
            color: #28a745;
        }

        .stat-card.guests {
            border-top-color: #ffc107;
        }

        .stat-card.guests strong {
            color: #ffc107;
        }

        .chart-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .chart-section h3 {
            margin-top: 0;
            color: #333;
        }

        .chart-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            height: 250px;
            padding: 20px;
        }

        .chart-bar {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .chart-bar .bar {
            background: #007bff;
            border-radius: 4px;
            width: 40px;
        }

        .chart-bar .label {
            font-size: 12px;
            color: #666;
            font-weight: 600;
        }

        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .data-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .data-table h3 {
            margin: 0;
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            color: #333;
        }

        .data-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }

        .data-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        .data-table tr:hover {
            background: #f8f9fa;
        }
    </style>
@endpush

@section('content')
    <div class="admin-layout">
        <aside class="sidebar">
            <h3>Ade Villa Admin</h3>
            <nav>
                <a href="/admin/dashboard" class="menu-item active">Dashboard</a>
                <a href="/admin/manage" class="menu-item">Manage</a>
                <a href="/admin/reservations" class="menu-item">Reservations</a>
                <a href="/admin/users" class="menu-item">Users</a>
                <a href="/admin/finances" class="menu-item">Finance</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="header">
                <h2>Dashboard</h2>
                <div style="width: 40px; height: 40px; border-radius: 50%; background: #007bff;"></div>
            </div>

            <section class="stats">
                <div class="stat-card">
                    <h3>Total Villas</h3>
                    <strong>{{ $totalVillas ?? 0 }}</strong>
                </div>
                <div class="stat-card revenue">
                    <h3>Total Revenue</h3>
                    <strong>Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</strong>
                </div>
                <div class="stat-card guests">
                    <h3>Total Guests</h3>
                    <strong>{{ $totalGuests ?? 0 }}</strong>
                </div>
                <div class="stat-card">
                    <h3>Total Bookings</h3>
                    <strong>{{ $totalBookings ?? 0 }}</strong>
                </div>
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <strong>{{ $totalUsers ?? 0 }}</strong>
                </div>
            </section>

            <section class="chart-section">
                <h3>Revenue Trend (Last 7 Days)</h3>
                <div class="chart-container">
                    @forelse($revenueTrend ?? [] as $trend)
                        <div class="chart-bar">
                            <div class="bar" style="height: {{ $trend['height'] }}px;"></div>
                            <div class="label">{{ $trend['day'] }}</div>
                            <div style="font-size: 11px; color: #999;">Rp {{ number_format($trend['revenue'] / 1000000, 1) }}M
                            </div>
                        </div>
                    @empty
                        <p>No data available</p>
                    @endforelse
                </div>
            </section>

            <section class="two-column">
                <div class="data-table">
                    <h3>Top Villas by Bookings</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Villa Name</th>
                                <th>Bookings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topVillas ?? [] as $villa)
                                <tr>
                                    <td>{{ $villa->name }}</td>
                                    <td><strong>{{ $villa->bookings_count }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" style="text-align: center; color: #999;">No data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="data-table">
                    <h3>Recent Bookings</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Guest</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings ?? [] as $booking)
                                <tr>
                                    <td>{{ $booking->guest_name }}</td>
                                    <td>{{ $booking->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" style="text-align: center; color: #999;">No bookings</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
@endsection