@extends('layouts.app')

@section('title', 'Reception Dashboard')

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

        .profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #007bff;
        }

        .kpis {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .kpi-card {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 1px solid #e9ecef;
        }

        .kpi-card h1 {
            margin: 0 0 10px;
            color: #007bff;
            font-size: 32px;
        }

        .kpi-card div {
            color: #6c757d;
            font-weight: 500;
        }

        .schedule-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
            margin-bottom: 20px;
        }

        .schedule-card h3 {
            margin-top: 0;
            color: #007bff;
        }

        .schedule-list {
            list-style: none;
            padding: 0;
        }

        .schedule-list li {
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .schedule-list li:last-child {
            border-bottom: none;
        }

        .chart-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
        }

        .chart-section h4 {
            margin-top: 0;
            color: #007bff;
        }

        .chart-placeholder {
            height: 250px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            margin-top: 15px;
        }
    </style>
@endpush

@section('content')
    <div class="reception-layout">
        <aside class="sidebar">
            <h3>Ade Villa Reception</h3>
            <a href="/reception/dashboard" class="menu-item active">Dashboard</a>
            <a href="/reception/reservations" class="menu-item">Reservations</a>
            <a href="/reception/calendar" class="menu-item">Calendar</a>
        </aside>

        <main class="main-content">
            <header class="header">
                <h2>Dashboard</h2>
                <div class="profile-icon"></div>
            </header>

            <section class="kpis">
                <div class="kpi-card">
                    <h1>{{ $totalGuests ?? 0 }}</h1>
                    <div>Total Tamu</div>
                </div>
                <div class="kpi-card">
                    <h1>{{ $totalBookings ?? 0 }}</h1>
                    <div>Total Pemasakan</div>
                </div>
            </section>

            <section style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h4 style="margin-top: 0;">Jadwal Tamu</h4>
                    <ul style="list-style: none; padding: 0;">
                        @forelse($upcomingSchedule as $booking)
                            <li style="padding: 8px 0; border-bottom: 1px solid #f8f9fa; font-size: 13px;">
                                {{ $booking->check_in_date->format('M d') }} - {{ $booking->check_out_date->format('M d') }}
                            </li>
                            <li style="padding: 8px 0; border-bottom: 1px solid #f8f9fa; font-size: 13px;">
                                {{ $booking->guest_name }} ({{ $booking->booking_code }})
                            </li>
                        @empty
                            <li style="padding: 8px 0; color: #999; font-size: 13px;">No upcoming bookings</li>
                        @endforelse
                    </ul>
                </div>
                <div
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; flex-direction: column;">
                    <h4 style="margin-top: 0;">Penyewaan Minggu ini</h4>
                    <div
                        style="display: flex; justify-content: space-around; align-items: flex-end; flex: 1; padding: 20px 0; min-height: 200px;">
                        @foreach($weeklyData as $data)
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <div
                                    style="width: 35px; height: {{ $data['height'] }}px; background: #4a5f7f; margin-bottom: 5px; border-radius: 3px;">
                                </div>
                                <span style="font-size: 12px;">{{ $loop->index + 1 }}</span>
                                <span style="font-size: 10px; color: #999;">{{ $data['count'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </main>
    </div>
@endsection