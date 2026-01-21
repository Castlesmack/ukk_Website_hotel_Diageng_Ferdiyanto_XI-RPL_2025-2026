@extends('layouts.app')

@section('title', 'Finances')

@section('content')
    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px; margin: 0 -20px; padding: 0;">
        <!-- Sidebar -->
        <div style="background: #f8f9fa; padding: 20px; min-height: 100vh;">
            <h2 style="margin: 0 0 30px 0; font-size: 18px; font-weight: 600;">Menu</h2>
            <nav style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('admin.dashboard') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">📊
                    Dashboard</a>
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <button
                        onclick="document.getElementById('manage-menu').style.display = document.getElementById('manage-menu').style.display === 'none' ? 'flex' : 'none'"
                        style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; text-align: left; font-weight: 500;">🏡
                        Manage</button>
                    <div id="manage-menu" style="display: none; flex-direction: column; gap: 5px; margin-left: 10px;">
                        <a href="{{ route('admin.villas.index') }}"
                            style="padding: 8px; background: #ffffff; color: #333; text-decoration: none; border-radius: 4px; font-size: 13px;">
                            Villa</a>
                        <a href="{{ route('admin.settings.homepage') }}"
                            style="padding: 8px; background: #ffffff; color: #333; text-decoration: none; border-radius: 4px; font-size: 13px;">
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
                    style="padding: 12px; background: #f05b4f; color: white; text-decoration: none; border-radius: 4px;">💰
                    Finance</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div style="padding: 20px; background: white;">
            <h1 style="margin: 0 0 20px 0; font-size: 28px;">Halaman Keuangan</h1>

            <!-- Filters -->
            <form method="GET"
                style="background: #FAF2E8; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-size: 12px; font-weight: 600;">Filter Tanggal
                        Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-size: 12px; font-weight: 600;">Filter Tanggal
                        Akhir</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-size: 12px; font-weight: 600;">Filter Tipe
                        Villa</label>
                    <select name="villa_id" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">Semua Villa</option>
                        @foreach($villas as $villa)
                            <option value="{{ $villa->id }}" {{ request('villa_id') == $villa->id ? 'selected' : '' }}>
                                {{ $villa->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; align-items: flex-end;">
                    <button type="submit"
                        style="width: 100%; background: #f05b4f; color: white; padding: 8px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">Filter</button>
                </div>
            </form>

            <!-- Income Summary -->
            <div
                style="background: #f0f7ff; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #f05b4f;">
                <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Total Income Villa</p>
                <p style="margin: 8px 0 0 0; font-size: 32px; font-weight: bold; color: #f05b4f;">Rp
                    {{ number_format($totalIncome, 0, ',', '.') }}
                </p>
            </div>

            <!-- Income Details -->
            <div style="background: white; border: 1px solid #ddd; border-radius: 8px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Booking ID</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Villa</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Tamu</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Tanggal</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 12px;">#{{ $booking->booking_code ?? $booking->id }}</td>
                                <td style="padding: 12px;">{{ $booking->villa->name }}</td>
                                <td style="padding: 12px;">{{ $booking->guest_name ?? $booking->user->name }}</td>
                                <td style="padding: 12px;">{{ $booking->created_at->format('d M Y') }}</td>
                                <td style="padding: 12px; font-weight: 600;">Rp
                                    {{ number_format($booking->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 20px; text-align: center; color: #666;">Tidak ada data income
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection