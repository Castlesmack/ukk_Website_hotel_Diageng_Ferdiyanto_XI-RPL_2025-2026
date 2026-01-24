@extends('layouts.app')

@section('title', 'Reservations')

@section('content')
    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px; margin: 0 -20px; padding: 0;">
        <!-- Sidebar -->
        <div style="background: #f8f9fa; padding: 20px; min-height: 100vh;">
            <h2 style="margin: 0 0 30px 0; font-size: 18px; font-weight: 600;">Menu</h2>
            <nav style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('reception.dashboard') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">Dashboard</a>
                <a href="{{ route('reception.reservations') }}"
                    style="padding: 12px; background: #f05b4f; color: white; text-decoration: none; border-radius: 4px;">Reservation</a>
                <a href="{{ route('reception.calendar') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">Calendar</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div style="padding: 20px; background: white;">
            <h1 style="margin: 0 0 20px 0; font-size: 28px;">Reservations</h1>

            @if(session('success'))
                <div
                    style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filter Form -->
            <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                <form method="GET" action="{{ route('reception.reservations') }}"
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; align-items: flex-end;">
                    <div>
                        <label
                            style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 6px; text-transform: uppercase;">Booking
                            Code/Guest Name</label>
                        <input type="text" name="itinerary" placeholder="e.g., ADV-001 or John Doe"
                            value="{{ request('itinerary') }}"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div>
                        <label
                            style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 6px; text-transform: uppercase;">Date
                            Type</label>
                        <select name="date_type"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                            <option value="check_in" {{ request('date_type') == 'check_in' ? 'selected' : '' }}>Check-in
                            </option>
                            <option value="check_out" {{ request('date_type') == 'check_out' ? 'selected' : '' }}>Check-out
                            </option>
                        </select>
                    </div>
                    <div>
                        <label
                            style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 6px; text-transform: uppercase;">Start
                            Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                    <div>
                        <label
                            style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 6px; text-transform: uppercase;">End
                            Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    </div>
                    <button type="submit"
                        style="padding: 10px 25px; background: #A0522D; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px;">🔍
                        Search</button>
                </form>
            </div>

            <!-- Reservations Table -->
            <div style="background: white; border: 1px solid #ddd; border-radius: 8px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">#
                            </th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Booking Code</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Guest Name</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Phone</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Check-in</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Check-out</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Villa</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 12px;">{{ $bookings->currentPage() * 15 - 15 + $loop->iteration }}</td>
                                <td style="padding: 12px;"><span
                                        style="background: #f0f0f0; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-family: monospace; font-weight: 600;">{{ $booking->booking_code }}</span>
                                </td>
                                <td style="padding: 12px; font-weight: 600;">{{ $booking->guest_name }}</td>
                                <td style="padding: 12px; color: #7f8c8d; font-size: 13px;">{{ $booking->guest_phone }}</td>
                                <td style="padding: 12px; font-size: 13px; color: #666;">
                                    {{ $booking->check_in_date?->format('D, d M y') ?? '-' }}</td>
                                <td style="padding: 12px; font-size: 13px; color: #666;">
                                    {{ $booking->check_out_date?->format('D, d M y') ?? '-' }}</td>
                                <td style="padding: 12px;">{{ $booking->villa->name ?? '-' }}</td>
                                <td style="padding: 12px; font-weight: 600; color: #27ae60; font-size: 13px;">Rp
                                    {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding: 20px; text-align: center; color: #666;">No reservations found
                                </td>
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
        </div>
    </div>
@endsection