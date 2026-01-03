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
                    <h1>100</h1>
                    <div>Total Guests</div>
                </div>
                <div class="kpi-card">
                    <h1>30</h1>
                    <div>Total Revenue</div>
                </div>
                <div class="kpi-card schedule-card">
                    <h3>Guest Schedule</h3>
                    <ul class="schedule-list">
                        <li>John Doe - Check-in: Today 2:00 PM</li>
                        <li>Jane Smith - Check-out: Tomorrow 11:00 AM</li>
                        <li>Mike Johnson - Check-in: Tomorrow 3:00 PM</li>
                    </ul>
                </div>
                <div class="kpi-card">
                    <h1>8</h1>
                    <div>Today's Check-ins</div>
                </div>
            </section>

            <section class="chart-section">
                <h4>Weekly Occupancy</h4>
                <div class="chart-placeholder">
                    <div
                        style="display: flex; justify-content: space-around; align-items: end; height: 200px; padding: 20px;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 30px; height: 80px; background: #007bff; margin-bottom: 5px;"></div>
                            <span>Mon</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 30px; height: 120px; background: #007bff; margin-bottom: 5px;"></div>
                            <span>Tue</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 30px; height: 150px; background: #007bff; margin-bottom: 5px;"></div>
                            <span>Wed</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 30px; height: 100px; background: #007bff; margin-bottom: 5px;"></div>
                            <span>Thu</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 30px; height: 180px; background: #007bff; margin-bottom: 5px;"></div>
                            <span>Fri</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 30px; height: 200px; background: #007bff; margin-bottom: 5px;"></div>
                            <span>Sat</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 30px; height: 160px; background: #007bff; margin-bottom: 5px;"></div>
                            <span>Sun</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="chart-section">
                <h4>Weekly Reservations</h4>
                <div class="chart-placeholder">
                    <div
                        style="display: flex; align-items: end; justify-content: space-around; height: 200px; padding: 20px;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 30px; background: #007bff; margin-bottom: 5px; height: 120px;"></div>
                            <span>Mon</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 30px; background: #007bff; margin-bottom: 5px; height: 80px;"></div>
                            <span>Tue</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 30px; background: #007bff; margin-bottom: 5px; height: 150px;"></div>
                            <span>Wed</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 30px; background: #007bff; margin-bottom: 5px; height: 100px;"></div>
                            <span>Thu</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 30px; background: #007bff; margin-bottom: 5px; height: 90px;"></div>
                            <span>Fri</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 30px; background: #007bff; margin-bottom: 5px; height: 180px;"></div>
                            <span>Sat</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 30px; background: #007bff; margin-bottom: 5px; height: 140px;"></div>
                            <span>Sun</span>
                        </div>
                    </div>
                </div>
            </section>

            <section style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h4 style="margin-top: 0;">Today's Check-ins</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 8px 0; border-bottom: 1px solid #f8f9fa;">Room 101 - Sarah Wilson</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #f8f9fa;">Room 205 - David Brown</li>
                        <li style="padding: 8px 0;">Villa A - Michael Davis</li>
                    </ul>
                </div>
                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h4 style="margin-top: 0;">Today's Check-outs</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 8px 0; border-bottom: 1px solid #f8f9fa;">Room 102 - Emma Johnson</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #f8f9fa;">Villa B - Robert Miller</li>
                        <li style="padding: 8px 0;">Room 303 - Lisa Garcia</li>
                    </ul>
                </div>
            </section>
        </main>
    </div>
@endsection