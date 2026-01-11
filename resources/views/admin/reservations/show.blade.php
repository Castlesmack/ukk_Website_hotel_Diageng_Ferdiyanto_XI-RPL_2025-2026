@extends('layouts.app')

@section('title', 'Detail Pemesanan')

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
                    <button onclick="document.getElementById('manage-menu').style.display = document.getElementById('manage-menu').style.display === 'none' ? 'flex' : 'none'"
                        style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; text-align: left; font-weight: 500;">🏡
                        Manage</button>
                    <div id="manage-menu" style="display: none; flex-direction: column; gap: 5px; margin-left: 10px;">
                        <a href="{{ route('admin.villas.index') }}"
                            style="padding: 8px; background: #f0f0f0; color: #333; text-decoration: none; border-radius: 4px; font-size: 13px;">
                            Villa</a>
                        <a href="{{ route('admin.settings.homepage') }}"
                            style="padding: 8px; background: #f0f0f0; color: #333; text-decoration: none; border-radius: 4px; font-size: 13px;">
                            Homepage</a>
                    </div>
                </div>
                <a href="{{ route('admin.reservations.index') }}"
                    style="padding: 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">📅
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
        <div style="padding: 20px; background: white; max-width: 800px;">
            <a href="{{ route('admin.reservations.index') }}"
                style="color: #007bff; text-decoration: none; margin-bottom: 20px; display: inline-block;">← Kembali</a>

            <h1 style="margin: 0 0 20px 0; font-size: 28px;">Detail Pemesanan</h1>

            <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div style="padding-bottom: 15px; border-bottom: 1px solid #ddd;">
                        <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Nama Tamu</p>
                        <p style="margin: 8px 0 0 0; font-size: 16px; font-weight: 600;">
                            {{ $booking->guest_name ?? $booking->user->name }}</p>
                    </div>
                    <div style="padding-bottom: 15px; border-bottom: 1px solid #ddd;">
                        <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">No Telp</p>
                        <p style="margin: 8px 0 0 0; font-size: 16px; font-weight: 600;">
                            {{ $booking->guest_phone ?? $booking->user->phone }}</p>
                    </div>
                    <div style="padding-bottom: 15px; border-bottom: 1px solid #ddd;">
                        <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Villa</p>
                        <p style="margin: 8px 0 0 0; font-size: 16px; font-weight: 600;">{{ $booking->villa->name }}</p>
                    </div>
                    <div style="padding-bottom: 15px; border-bottom: 1px solid #ddd;">
                        <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Status Pembayaran</p>
                        <p style="margin: 8px 0 0 0;">
                            <span
                                style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">{{ ucfirst($booking->status) }}</span>
                        </p>
                    </div>
                    <div style="padding-bottom: 15px; border-bottom: 1px solid #ddd;">
                        <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Check In</p>
                        <p style="margin: 8px 0 0 0; font-size: 16px; font-weight: 600;">
                            {{ $booking->check_in_date->format('d M Y') }}</p>
                    </div>
                    <div style="padding-bottom: 15px; border-bottom: 1px solid #ddd;">
                        <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Check Out</p>
                        <p style="margin: 8px 0 0 0; font-size: 16px; font-weight: 600;">
                            {{ $booking->check_out_date->format('d M Y') }}</p>
                    </div>
                    <div style="padding-bottom: 15px; border-bottom: 1px solid #ddd;">
                        <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Total Harga</p>
                        <p style="margin: 8px 0 0 0; font-size: 16px; font-weight: 600;">Rp
                            {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.reservations.updateStatus', $booking) }}" method="POST"
                style="margin-bottom: 20px;">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Validasi Pemesanan *</label>
                    <select name="status" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"
                        required>
                        <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <button type="submit"
                    style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">Update
                    Status</button>
            </form>

            <form action="{{ route('admin.reservations.destroy', $booking) }}" method="POST"
                onsubmit="return confirm('Hapus pemesanan?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">Hapus
                    Pemesanan</button>
            </form>
        </div>
    </div>
@endsection