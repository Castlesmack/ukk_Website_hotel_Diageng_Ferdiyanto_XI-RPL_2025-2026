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
                <h2>Availability List</h2>
            </div>

            <form method="GET" action="{{ route('reception.calendar') }}" style="display: contents;">
                <div class="calendar-controls">
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <a href="{{ route('reception.calendar', ['month' => $month - 1 ?: 12, 'year' => $month - 1 ? $year : $year - 1]) }}"
                            style="background: white; color: #007bff; border: 1px solid #ccc; padding: 6px 10px; cursor: pointer; border-radius: 4px; text-decoration: none; display: inline-block;">←</a>

                        <select name="month" onchange="this.form.submit()"
                            style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromDate($year, $m, 1)->format('F Y') }}
                                </option>
                            @endfor
                        </select>

                        <a href="{{ route('reception.calendar', ['month' => \Carbon\Carbon::now()->month, 'year' => \Carbon\Carbon::now()->year]) }}"
                            style="background: white; color: #007bff; border: 1px solid #ccc; padding: 6px 10px; cursor: pointer; border-radius: 4px; text-decoration: none; display: inline-block;">Today</a>
                    </div>
                    <div>
                        <select name="villa_id" onchange="this.form.submit()"
                            style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
                            <option value="">All Villas</option>
                            @foreach($villas as $villa)
                                <option value="{{ $villa->id }}" {{ $villaId == $villa->id ? 'selected' : '' }}>
                                    {{ $villa->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <div class="calendar-grid">
                <div class="day-header">Sunday</div>
                <div class="day-header">Monday</div>
                <div class="day-header">Tuesday</div>
                <div class="day-header">Wednesday</div>
                <div class="day-header">Thursday</div>
                <div class="day-header">Friday</div>
                <div class="day-header">Saturday</div>

                @foreach($calendarDays as $day)
                    @if($day['isCurrentMonth'])
                        <div class="day" style="{{ $day['isBooked'] ? 'background: #b3d9ff;' : 'background: white;' }}">
                            <div class="day-number" style="{{ $day['isBooked'] ? 'color: #0066cc;' : 'color: #333;' }}">
                                {{ $day['day'] }}
                            </div>
                            @if($day['bookings']->count() > 0)
                                <div class="availability" style="color: #0066cc; font-weight: 600;">
                                    Rp {{ number_format($day['totalPrice'], 0, ',', '.') }}
                                </div>
                                @foreach($day['bookings'] as $booking)
                                    <div class="availability" style="font-size: 10px; color: #0066cc; margin-top: 2px;">
                                        {{ $booking->guest_name }}
                                    </div>
                                @endforeach
                            @else
                                <div class="availability">Available</div>
                            @endif
                        </div>
                    @else
                        <div class="day" style="background: #f0f0f0;">
                            <div class="day-number" style="color: #999;">{{ $day['day'] ?? '' }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </main>
    </div>
@endsection