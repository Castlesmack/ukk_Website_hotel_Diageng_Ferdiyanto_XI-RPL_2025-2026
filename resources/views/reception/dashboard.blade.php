@extends('layouts.app')

@section('title', 'Reception Dashboard')

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
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .header h2 {
            margin: 0;
            color: #2c3e50;
            font-size: 28px;
            font-weight: 700;
        }

        .kpis {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .kpi-card {
            background: #FAF2E8;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 5px solid #000;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .kpi-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .kpi-card.guests {
            border-left-color: #000;
        }

        .kpi-card.bookings {
            border-left-color: #000;
        }

        .kpi-card.revenue {
            border-left-color: #000;
        }

        .kpi-card h1 {
            margin: 0 0 10px;
            color: #000;
            font-size: 36px;
            font-weight: 700;
        }

        .kpi-card div {
            color: #7f8c8d;
            font-weight: 500;
            font-size: 14px;
        }

        .kpi-card small {
            display: block;
            color: #bdc3c7;
            font-size: 12px;
            margin-top: 8px;
        }

        .schedule-and-chart {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
        }

        .schedule-card {
            background: #FAF2E8;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .schedule-card h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #2c3e50;
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .schedule-card h3::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 20px;
            background: #000;
            border-radius: 2px;
            margin-right: 12px;
        }

        .schedule-list {
            list-style: none;
            padding: 0;
        }

        .schedule-list li {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
            color: #555;
        }

        .schedule-list li:last-child {
            border-bottom: none;
        }

        .schedule-list .date {
            font-weight: 600;
            color: #000;
            display: block;
            margin-bottom: 4px;
        }

        .schedule-list .guest {
            color: #7f8c8d;
        }

        .schedule-list .code {
            font-size: 11px;
            color: #bdc3c7;
            font-family: monospace;
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            display: inline-block;
            margin-top: 4px;
        }

        .chart-section {
            background: #FAF2E8;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .chart-section h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #2c3e50;
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .chart-section h3::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 20px;
            background: #000;
            border-radius: 2px;
            margin-right: 12px;
        }

        .chart-bars {
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            height: 200px;
            padding: 20px 0;
            gap: 15px;
        }

        .bar {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }

        .bar-fill {
            width: 100%;
            background: linear-gradient(180deg, #000 0%, #333 100%);
            border-radius: 6px 6px 0 0;
            transition: height 0.3s;
            min-height: 40px;
            position: relative;
        }

        .bar-fill:hover {
            opacity: 0.8;
        }

        .bar-label {
            margin-top: 10px;
            font-size: 12px;
            color: #7f8c8d;
            font-weight: 600;
            text-align: center;
        }

        .bar-value {
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            font-weight: 700;
            color: #000;
            font-size: 14px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #95a5a6;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        @media (max-width: 1024px) {
            .schedule-and-chart {
                grid-template-columns: 1fr;
            }

            .kpis {
                grid-template-columns: 1fr;
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

            .header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div class="reception-layout">
        <aside class="sidebar">
            <h3>Reception</h3>
            <a href="/reception/dashboard" class="menu-item active">Dashboard</a>
            <a href="/reception/reservations" class="menu-item">Reservations</a>
            <a href="/reception/calendar" class="menu-item">Calendar</a>
        </aside>

        <main class="main-content">
            <header class="header">
                <div>
                    <h2>Reception Dashboard</h2>
                    <p style="margin: 5px 0 0 0; color: #7f8c8d; font-size: 14px;">
                        Welcome back! Here's your daily overview.
                    </p>
                </div>
            </header>

            <section class="kpis">
                <div class="kpi-card guests">
                    <h1>{{ $totalGuests ?? 0 }}</h1>
                    <div>Total Guests</div>
                    <small>Currently staying</small>
                </div>
                <div class="kpi-card bookings">
                    <h1>{{ $totalBookings ?? 0 }}</h1>
                    <div>Total Bookings</div>
                    <small>This period</small>
                </div>
                <div class="kpi-card revenue">
                    <h1>Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h1>
                    <div>Total Revenue</div>
                    <small>Confirmed bookings</small>
                </div>
            </section>

            <div class="schedule-and-chart">
                <div class="schedule-card">
                    <h3>Upcoming Check-ins</h3>
                    <ul class="schedule-list">
                        @forelse($upcomingSchedule as $booking)
                            <li>
                                <span class="date">{{ $booking->check_in_date->format('M d, Y') }}</span>
                                <span class="guest">👤 {{ $booking->guest_name }}</span>
                                <span class="code">{{ $booking->booking_code }}</span>
                            </li>
                        @empty
                            <div class="empty-state">
                                <div class="empty-state-icon">📅</div>
                                <p>No upcoming bookings</p>
                            </div>
                        @endforelse
                    </ul>
                </div>

                <div class="chart-section">
                    <h3>Weekly Occupancy</h3>
                    <div class="chart-bars">
                        @foreach($weeklyData as $data)
                            <div class="bar">
                                <div class="bar-fill" style="height: {{ $data['height'] }}px;">
                                    @if($data['count'] > 0)
                                        <span class="bar-value">{{ $data['count'] }}</span>
                                    @endif
                                </div>
                                <div class="bar-label">{{ substr($data['day'], 0, 3) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection