@extends('layouts.app')

@section('title', 'Reservations')

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
                            style="padding: 8px; background: white; color: #333; text-decoration: none; border-radius: 4px; font-size: 13px;">
                            Villa</a>
                        <a href="{{ route('admin.settings.homepage') }}"
                            style="padding: 8px; background: white; color: #333; text-decoration: none; border-radius: 4px; font-size: 13px;">
                            Homepage</a>
                    </div>
                </div>
                <a href="{{ route('admin.reservations.index') }}"
                    style="padding: 12px; background: #f05b4f; color: white; text-decoration: none; border-radius: 4px;">📅
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
            <h1 style="margin: 0 0 20px 0; font-size: 28px;">Halaman Detail Pemesanan</h1>

            @if(session('success'))
                <div
                    style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            <div style="background: white; border: 1px solid #ddd; border-radius: 8px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Data Tamu</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Villa</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Tanggal</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">No
                                Telp</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Status Pembayaran</th>
                            <th style="padding: 12px; text-align: center; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $reservation)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 12px;">{{ $reservation->guest_name ?? $reservation->user->name }}</td>
                                <td style="padding: 12px;">{{ $reservation->villa->name }}</td>
                                <td style="padding: 12px;">{{ $reservation->check_in_date->format('d M Y') }} -
                                    {{ $reservation->check_out_date->format('d M Y') }}
                                </td>
                                <td style="padding: 12px;">{{ $reservation->guest_phone ?? $reservation->user->phone }}</td>
                                <td style="padding: 12px;">
                                    <span
                                        style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 12px;">{{ ucfirst($reservation->status) }}</span>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <a href="{{ route('admin.reservations.show', $reservation) }}"
                                        style="color: #f05b4f; text-decoration: none;">Lihat</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 20px; text-align: center; color: #666;">Tidak ada pemesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                {{ $reservations->links() }}
            </div>
        </div>
    </div>
@endsection