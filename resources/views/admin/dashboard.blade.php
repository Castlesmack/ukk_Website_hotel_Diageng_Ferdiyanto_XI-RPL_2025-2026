@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px; margin: 0 -20px; padding: 0;">
        <!-- Sidebar -->
        <div style="background: #f8f9fa; padding: 20px; min-height: 100vh;">
            <h2 style="margin: 0 0 30px 0; font-size: 18px; font-weight: 600;">Menu</h2>
            <nav style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('admin.dashboard') }}"
                    style="padding: 12px; background: #f05b4f; color: white; text-decoration: none; border-radius: 4px;">📊
                    Dashboard</a>
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <button
                        onclick="document.getElementById('manage-menu').style.display = document.getElementById('manage-menu').style.display === 'none' ? 'flex' : 'none'"
                        style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; text-align: left; font-weight: 500;">🏡
                        Manage</button>
                    <div id="manage-menu" style="display: none; flex-direction: column; gap: 5px; margin-left: 10px;">
                        <a href="{{ route('admin.villas.index') }}"
                            style="padding: 8px; background: #FAF2E8; color: #333; text-decoration: none; border-radius: 4px; font-size: 13px;">
                            Villa</a>
                        <a href="{{ route('admin.settings.homepage') }}"
                            style="padding: 8px; background: #FAF2E8; color: #333; text-decoration: none; border-radius: 4px; font-size: 13px;">
                            Homepage</a>
                    </div>
                </div>
                <a href="{{ route('admin.reservations.index') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">📅
                    Reservation</a>
                <a href="{{ route('admin.users.index') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">👥
                    Users</a>
                <a href="{{ route('admin.finances.index') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">💰
                    Finance</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div style="padding: 20px; background: white;">
            <h1 style="margin: 0 0 30px 0; font-size: 28px;">Dashboard Admin</h1>

            <!-- Stats Grid -->
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div style="background: #f0f7ff; padding: 20px; border-radius: 8px; border-left: 4px solid #f05b4f;">
                    <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Total Villa</p>
                    <p style="margin: 8px 0 0 0; font-size: 28px; font-weight: bold; color: #f05b4f;">{{ $totalVillas }}</p>
                </div>
                <div style="background: #fff0f5; padding: 20px; border-radius: 8px; border-left: 4px solid #dc3545;">
                    <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Total Tamu</p>
                    <p style="margin: 8px 0 0 0; font-size: 28px; font-weight: bold; color: #dc3545;">{{ $totalGuests }}</p>
                </div>
                <div style="background: #f0fff0; padding: 20px; border-radius: 8px; border-left: 4px solid #f05b4f;">
                    <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Total Revenue</p>
                    <p style="margin: 8px 0 0 0; font-size: 28px; font-weight: bold; color: #f05b4f;">Rp
                        {{ number_format($totalRevenue, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">Monitor Penyewaan</h3>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Tamu</th>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Villa</th>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Check In</th>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Check Out</th>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBookings as $booking)
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 12px;">{{ $booking->guest_name ?? $booking->user->name }}</td>
                                    <td style="padding: 12px;">{{ $booking->villa->name }}</td>
                                    <td style="padding: 12px;">{{ $booking->check_in_date->format('d M Y') }}</td>
                                    <td style="padding: 12px;">{{ $booking->check_out_date->format('d M Y') }}</td>
                                    <td style="padding: 12px;">
                                        <span
                                            style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 12px;">{{ ucfirst($booking->status) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection