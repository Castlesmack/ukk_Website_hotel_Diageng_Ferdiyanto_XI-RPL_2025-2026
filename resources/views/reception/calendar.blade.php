@extends('layouts.app')

@section('title', 'Availability Calendar')

@section('content')
    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px; margin: 0 -20px; padding: 0;">
        <!-- Sidebar -->
        <div style="background: #f8f9fa; padding: 20px; min-height: 100vh;">
            <h2 style="margin: 0 0 30px 0; font-size: 18px; font-weight: 600;">Menu</h2>
            <nav style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('reception.dashboard') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">📊
                    Dashboard</a>
                <a href="{{ route('reception.reservations') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">📅
                    Reservations</a>
                <a href="{{ route('reception.calendar') }}"
                    style="padding: 12px; background: #f05b4f; color: white; text-decoration: none; border-radius: 4px;">📆
                    Calendar</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div style="padding: 20px; background: white;">
            <!-- Filter Bar -->
            <form method="GET" action="{{ route('reception.calendar') }}"
                style="display: flex; gap: 12px; align-items: center; margin-bottom: 25px; flex-wrap: wrap; background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #e0e0e0;">
                <!-- Navigation -->
                <div style="display: flex; gap: 8px; align-items: center;">
                    <a href="{{ route('reception.calendar', ['month' => $month - 1 ?: 12, 'year' => $month - 1 ? $year : $year - 1]) }}"
                        style="padding: 8px 10px; background: white; border: 1px solid #bbb; border-radius: 5px; text-decoration: none; color: #333; font-size: 16px; cursor: pointer; font-weight: 600; transition: all 0.2s; display: flex; align-items: center; justify-content: center;"
                        onmouseover="this.style.borderColor='#A0522D';" onmouseout="this.style.borderColor='#bbb';">
                        &lt;
                    </a>
                    <a href="{{ route('reception.calendar', ['month' => $month + 1 <= 12 ? $month + 1 : 1, 'year' => $month + 1 > 12 ? $year + 1 : $year]) }}"
                        style="padding: 8px 10px; background: white; border: 1px solid #bbb; border-radius: 5px; text-decoration: none; color: #333; font-size: 16px; cursor: pointer; font-weight: 600; transition: all 0.2s; display: flex; align-items: center; justify-content: center;"
                        onmouseover="this.style.borderColor='#A0522D';" onmouseout="this.style.borderColor='#bbb';">
                        &gt;
                    </a>
                </div>

                <!-- Month/Year Selector -->
                <select name="month" onchange="this.form.submit()"
                    style="padding: 8px 12px; border: 1px solid #bbb; border-radius: 5px; font-size: 14px; background: white; cursor: pointer;">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate($year, $m, 1)->format('F Y') }}
                        </option>
                    @endfor
                </select>

                <!-- Today Button -->
                <a href="{{ route('reception.calendar', ['month' => \Carbon\Carbon::now()->month, 'year' => \Carbon\Carbon::now()->year]) }}"
                    style="padding: 8px 16px; background: white; border: 1px solid #bbb; border-radius: 5px; text-decoration: none; color: #333; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
                    onmouseover="this.style.background='#f0f0f0';" onmouseout="this.style.background='white';">
                    Today
                </a>

                <!-- Spacer -->
                <div style="flex: 1;"></div>

                <!-- Villa Filter -->
                <select name="villa_id" onchange="this.form.submit()"
                    style="padding: 8px 12px; border: 1px solid #bbb; border-radius: 5px; font-size: 14px; background: white; cursor: pointer;">
                    <option value="">All Villas</option>
                    @foreach($villas as $villa)
                        <option value="{{ $villa->id }}" {{ $villaId == $villa->id ? 'selected' : '' }}>{{ $villa->name }}
                        </option>
                    @endforeach
                </select>
            </form>

            <!-- Calendar Grid -->
            <div style="background: white; border: 1px solid #ddd; border-radius: 0; overflow: hidden;">
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 0; background: white;">
                    <!-- Day Headers -->
                    @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                        <div
                            style="font-weight: 700; text-align: center; padding: 15px 10px; background: white; color: #666; text-transform: capitalize; font-size: 13px; letter-spacing: 0.5px; border-bottom: 2px solid #f0f0f0; border-right: 1px solid #f0f0f0;">
                            {{ $day }}
                        </div>
                    @endforeach

                    <!-- Calendar Days -->
                    @foreach($calendarDays as $dayData)
                        <div
                            style="min-height: 100px; padding: 12px 10px; position: relative; border-right: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0; background: white; display: flex; flex-direction: column;">
                            @if($dayData['day'] === null)
                                <!-- Empty cell for other month days -->
                            @else
                                <!-- Day number -->
                                <div style="font-size: 14px; font-weight: 700; color: #333; margin-bottom: 8px;">
                                    {{ $dayData['day'] }}
                                </div>

                                <!-- Diagonal lines pattern for visual interest -->
                                <div
                                    style="position: absolute; top: 0; right: 0; width: 40px; height: 40px; background: repeating-linear-gradient(45deg, transparent, transparent 10px, #f5f5f5 10px, #f5f5f5 20px); opacity: 0.3; pointer-events: none;">
                                </div>

                                <!-- Availability Status -->
                                @if($dayData['isBooked'] && $dayData['bookings']->count() > 0)
                                    <div style="font-size: 11px; font-weight: 600; color: #A0522D; margin-top: auto;">
                                        <span
                                            style="background: #ffe0d0; padding: 2px 6px; border-radius: 3px; display: inline-block;">Booked</span>
                                    </div>
                                    <div style="font-size: 11px; color: #666; margin-top: 3px;">
                                        Rp {{ number_format($dayData['totalPrice'] ?? 0, 0, ',', '.') }}
                                    </div>
                                @else
                                    <div style="font-size: 11px; font-weight: 600; color: #27ae60; margin-top: auto;">
                                        <span
                                            style="background: #e8f5e9; padding: 2px 6px; border-radius: 3px; display: inline-block;">Available</span>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection