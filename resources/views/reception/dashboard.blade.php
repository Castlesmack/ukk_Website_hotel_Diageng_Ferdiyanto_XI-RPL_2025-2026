@extends('layouts.app')

@section('title', 'Reception Dashboard')

@section('content')
    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px; margin: 0 -20px; padding: 0;">
        <!-- Sidebar -->
        <div style="background: #f8f9fa; padding: 20px; min-height: 100vh;">
            <h2 style="margin: 0 0 30px 0; font-size: 18px; font-weight: 600;">Menu</h2>
            <nav style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('reception.dashboard') }}"
                    style="padding: 12px; background: #f05b4f; color: white; text-decoration: none; border-radius: 4px;">Dashboard</a>
                <a href="{{ route('reception.reservations') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">Reservations</a>
                <a href="{{ route('reception.calendar') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">Calendar</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div style="padding: 20px; background: white;">
            <h1 style="margin: 0 0 30px 0; font-size: 28px;">Reception Dashboard</h1>

            <!-- Stats Grid -->
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div style="background: #f0f7ff; padding: 20px; border-radius: 8px; border-left: 4px solid #f05b4f;">
                    <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Today Check-in</p>
                    <p style="margin: 8px 0 0 0; font-size: 28px; font-weight: bold; color: #f05b4f;">
                        {{ $todayCheckIn ?? 0 }}
                    </p>
                </div>
                <div style="background: #fff0f5; padding: 20px; border-radius: 8px; border-left: 4px solid #dc3545;">
                    <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Today Check-out</p>
                    <p style="margin: 8px 0 0 0; font-size: 28px; font-weight: bold; color: #dc3545;">
                        {{ $todayCheckOut ?? 0 }}
                    </p>
                </div>
                <div style="background: #f0fff0; padding: 20px; border-radius: 8px; border-left: 4px solid #f05b4f;">
                    <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Occupied Villas</p>
                    <p style="margin: 8px 0 0 0; font-size: 28px; font-weight: bold; color: #f05b4f;">
                        {{ $occupiedVillas ?? 0 }}
                    </p>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">Today's Activity</h3>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Guest</th>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Villa</th>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Type</th>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Time</th>
                                <th
                                    style="padding: 12px; text-align: center; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayActivities ?? [] as $activity)
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 12px;">{{ $activity->guest_name ?? $activity->user->name }}</td>
                                    <td style="padding: 12px;">{{ $activity->villa->name }}</td>
                                    <td style="padding: 12px;">
                                        <span
                                            style="background: {{ $activity->type === 'check_in' ? '#d4edda' : '#fff3cd' }}; color: {{ $activity->type === 'check_in' ? '#155724' : '#856404' }}; padding: 4px 8px; border-radius: 4px; font-size: 12px;">{{ ucfirst($activity->type) }}</span>
                                    </td>
                                    <td style="padding: 12px;">{{ $activity->time ?? 'N/A' }}</td>
                                    <td style="padding: 12px; text-align: center;">
                                        <span
                                            style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Scheduled</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="padding: 20px; text-align: center; color: #666;">No activities for
                                        today</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection