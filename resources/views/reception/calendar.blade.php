@extends('layouts.app')

@section('title', 'Reception Calendar')

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

        .calendar-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .calendar-controls select,
        .calendar-controls button {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: white;
        }

        .calendar-controls button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .calendar-controls button:hover {
            background: #0056b3;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }

        .day {
            min-height: 100px;
            background: white;
            border: 1px solid #f8f9fa;
            padding: 8px;
            position: relative;
        }

        .day-header {
            font-weight: bold;
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .day-number {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .availability {
            font-size: 12px;
            color: #6c757d;
        }

        .booked {
            background: #fff3cd !important;
        }

        .fully-booked {
            background: #f8d7da !important;
        }
    </style>
@endpush

@section('content')
    <div class="reception-layout">
        <aside class="sidebar">
            <h3>Ade Villa Reception</h3>
            <a href="/reception/dashboard" class="menu-item">Dashboard</a>
            <a href="/reception/reservations" class="menu-item">Reservations</a>
            <a href="/reception/calendar" class="menu-item active">Calendar</a>
        </aside>

        <main class="main-content">
            <div class="header">
                <h2>Availability Calendar</h2>
            </div>

            <div class="calendar-controls">
                <div>
                    <select>
                        <option>November 2025</option>
                        <option>December 2025</option>
                        <option>January 2026</option>
                    </select>
                    <button>Today</button>
                </div>
                <div>
                    <select>
                        <option>All Villas</option>
                        <option>Type 0608</option>
                        <option>Villa Kota Bunga Ade</option>
                        <option>Villa Puncak Harmony</option>
                    </select>
                </div>
            </div>

            <div class="calendar-grid">
                <div class="day-header">Sun</div>
                <div class="day-header">Mon</div>
                <div class="day-header">Tue</div>
                <div class="day-header">Wed</div>
                <div class="day-header">Thu</div>
                <div class="day-header">Fri</div>
                <div class="day-header">Sat</div>

                <!-- Days will be generated dynamically -->
                @for($i = 1; $i <= 31; $i++)
                    <div class="day {{ $i % 3 == 0 ? 'booked' : ($i % 7 == 0 ? 'fully-booked' : '') }}">
                        <div class="day-number">{{ $i }}</div>
                        <div class="availability">{{ rand(0, 2) }}/{{ rand(2, 5) }}</div>
                    </div>
                @endfor
            </div>
        </main>
    </div>
@endsection