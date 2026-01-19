@extends('layouts.app')

@section('title', 'Reception Calendar')

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

        .calendar-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
            background: #FAF2E8;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .calendar-controls>div {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .calendar-controls select,
        .calendar-controls a,
        .calendar-controls button {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #FAF2E8;
            color: #000;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            font-size: 14px;
        }

        .calendar-controls select:focus,
        .calendar-controls a:hover,
        .calendar-controls button:hover {
            background: #A0522D;
            color: white;
            border-color: #A0522D;
        }

        .calendar-nav-btn {
            padding: 8px 12px;
            font-size: 18px;
        }

        .calendar-container {
            background: #FAF2E8;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #e0e0e0;
            padding: 1px;
            border-radius: 8px;
            overflow: hidden;
        }

        .day-header {
            font-weight: 700;
            text-align: center;
            padding: 12px;
            background: #1a1a1a;
            color: white;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .day {
            min-height: 120px;
            background: #FAF2E8;
            padding: 10px;
            position: relative;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        .day:hover {
            background: #f8f9ff;
            box-shadow: inset 0 0 8px rgba(102, 126, 234, 0.1);
        }

        .day.other-month {
            background: #f5f5f5;
            color: #ccc;
        }

        .day-number {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #000;
        }

        .day.other-month .day-number {
            color: #bdc3c7;
        }

        .day.booked {
            background: linear-gradient(135deg, #fff5f0 0%, #fff9f7 100%);
            border-left: 4px solid #A0522D;
        }

        .day.available {
            background: linear-gradient(135deg, #f5f5f5 0%, #f9f9f9 100%);
        }

        .booking-badge {
            background: #A0522D;
            color: white;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 4px;
            display: block;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .guest-info {
            font-size: 11px;
            color: #666;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .price-info {
            font-size: 10px;
            color: #000;
            font-weight: 600;
            margin-top: auto;
            padding-top: 5px;
            border-top: 1px solid #f0f0f0;
        }

        .availability-text {
            font-size: 12px;
            color: #27ae60;
            font-weight: 600;
            text-align: center;
            margin-top: auto;
            padding: 8px 0;
        }

        .legend {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #666;
        }

        .legend-color {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .legend-available {
            background: linear-gradient(135deg, #f5f5f5 0%, #f9f9f9 100%);
        }

        .legend-booked {
            background: linear-gradient(135deg, #fff5f0 0%, #fff9f7 100%);
            border-left: 3px solid #A0522D;
        }

        @media (max-width: 1024px) {
            .calendar-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .calendar-controls>div {
                justify-content: space-between;
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

            .day {
                min-height: 90px;
                padding: 8px;
                font-size: 12px;
            }

            .calendar-grid {
                font-size: 12px;
            }

            .legend {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="reception-layout">
        <aside class="sidebar">
            <h3>Reception</h3>
            <a href="/reception/dashboard" class="menu-item">Dashboard</a>
            <a href="/reception/reservations" class="menu-item">Reservations</a>
            <a href="/reception/calendar" class="menu-item active">Calendar</a>
        </aside>

        <main class="main-content">
            <div class="header">
                <div>
                    <h2>Availability Calendar</h2>
                    <p style="margin: 5px 0 0 0; color: #7f8c8d; font-size: 14px;">
                        Track villa occupancy and manage bookings
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ route('reception.calendar') }}">
                <div class="calendar-controls">
                    <div>
                        <a href="{{ route('reception.calendar', ['month' => $month - 1 ?: 12, 'year' => $month - 1 ? $year : $year - 1]) }}"
                            class="calendar-nav-btn">← Prev</a>

                        <select name="month" onchange="this.form.submit()">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromDate($year, $m, 1)->format('F') }}
                                </option>
                            @endfor
                        </select>

                        <select name="year" onchange="this.form.submit()">
                            @for($y = \Carbon\Carbon::now()->year - 1; $y <= \Carbon\Carbon::now()->year + 2; $y++)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>

                        <a href="{{ route('reception.calendar', ['month' => \Carbon\Carbon::now()->month, 'year' => \Carbon\Carbon::now()->year]) }}"
                            class="calendar-nav-btn">Today</a>

                        <a href="{{ route('reception.calendar', ['month' => $month + 1 <= 12 ? $month + 1 : 1, 'year' => $month + 1 > 12 ? $year + 1 : $year]) }}"
                            class="calendar-nav-btn">Next →</a>
                    </div>
                    <div>
                        <select name="villa_id" onchange="this.form.submit()">
                            <option value="">📍 All Villas</option>
                            @foreach($villas as $villa)
                                <option value="{{ $villa->id }}" {{ $villaId == $villa->id ? 'selected' : '' }}>
                                    {{ $villa->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <div class="calendar-container">
                <h4 style="text-align: center; margin-bottom: 20px; color: #2c3e50;">
                    {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}
                </h4>

                <div class="calendar-grid">
                    <div class="day-header">Sun</div>
                    <div class="day-header">Mon</div>
                    <div class="day-header">Tue</div>
                    <div class="day-header">Wed</div>
                    <div class="day-header">Thu</div>
                    <div class="day-header">Fri</div>
                    <div class="day-header">Sat</div>

                    @foreach($calendarDays as $day)
                        @if($day['isCurrentMonth'])
                            <div class="day {{ $day['isBooked'] ? 'booked' : 'available' }}">
                                <div class="day-number">{{ $day['day'] }}</div>
                                @if($day['bookings']->count() > 0)
                                    @foreach($day['bookings'] as $booking)
                                        <span class="booking-badge" title="{{ $booking->guest_name }}">
                                            🏠 {{ $booking->guest_name }}
                                        </span>
                                    @endforeach
                                    @if($day['totalPrice'] > 0)
                                        <div class="price-info">
                                            Rp {{ number_format($day['totalPrice'], 0, ',', '.') }}
                                        </div>
                                    @endif
                                @else
                                    <div class="availability-text">✓ Available</div>
                                @endif
                            </div>
                        @else
                            <div class="day other-month">
                                <div class="day-number">{{ $day['day'] ?? '-' }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-color legend-available"></div>
                        <span>Available</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color legend-booked"></div>
                        <span>Booked</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection