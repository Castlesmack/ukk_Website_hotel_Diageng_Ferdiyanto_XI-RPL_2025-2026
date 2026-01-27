@extends('layouts.app')

@section('title', 'Finances')

@section('content')
    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px; margin: 0 -20px; padding: 0;">
        <!-- Sidebar -->
        <div style="background: #f8f9fa; padding: 20px; min-height: 100vh;">
            <h2 style="margin: 0 0 30px 0; font-size: 18px; font-weight: 600;">Menu</h2>
            <nav style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('admin.dashboard') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">Dashboard</a>
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <button
                        onclick="document.getElementById('manage-menu').style.display = document.getElementById('manage-menu').style.display === 'none' ? 'flex' : 'none'"
                        style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; text-align: left; font-weight: 500;">Manage</button>
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
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">Reservation</a>
                <a href="{{ route('admin.users.index') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">Users</a>
                <a href="{{ route('admin.finances.index') }}"
                    style="padding: 12px; background: #f05b4f; color: white; text-decoration: none; border-radius: 4px;">Finance</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div style="padding: 20px; background: white;">
            <h1 style="margin: 0 0 20px 0; font-size: 28px;">Halaman Keuangan</h1>

            <!-- Filters -->
            <form method="GET"
                style="background: linear-gradient(135deg, #f8f9fa 0%, #FAF2E8 100%); padding: 20px; border-radius: 12px; margin-bottom: 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; border: 1px solid #e0e0e0;">
                <div>
                    <label
                        style="display: block; margin-bottom: 8px; font-size: 12px; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 0.5px;">📅
                        Dari Tanggal</label>
                    <input type="date" name="start_date" id="financeStartDate" value="{{ request('start_date') }}"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: all 0.3s;"
                        onchange="this.style.borderColor='#5a4a42';"
                        onfocus="this.style.boxShadow='0 0 0 3px rgba(90,74,66,0.1)';" aria-label="Tanggal Mulai Filter">
                </div>
                <div>
                    <label
                        style="display: block; margin-bottom: 8px; font-size: 12px; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 0.5px;">📅
                        Hingga Tanggal</label>
                    <input type="date" name="end_date" id="financeEndDate" value="{{ request('end_date') }}"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: all 0.3s;"
                        onchange="this.style.borderColor='#5a4a42';"
                        onfocus="this.style.boxShadow='0 0 0 3px rgba(90,74,66,0.1)';" aria-label="Tanggal Akhir Filter">
                </div>
                <div>
                    <label
                        style="display: block; margin-bottom: 8px; font-size: 12px; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 0.5px;">🏠
                        Pilih Villa</label>
                    <select name="villa_id" id="financeVillaFilter"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: all 0.3s; background: white; cursor: pointer;"
                        onchange="this.style.borderColor='#5a4a42';"
                        onfocus="this.style.boxShadow='0 0 0 3px rgba(90,74,66,0.1)';" aria-label="Filter Villa">
                        <option value="">🔍 Semua Villa</option>
                        @foreach($villas as $villa)
                            <option value="{{ $villa->id }}" {{ request('villa_id') == $villa->id ? 'selected' : '' }}>
                                {{ $villa->name }} (Rp {{ number_format($villa->base_price, 0, ',', '.') }}/malam)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; align-items: flex-end; gap: 10px;">
                    <button type="submit"
                        style="flex: 1; background: linear-gradient(135deg, #f05b4f 0%, #e63c34 100%); color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.3s; box-shadow: 0 2px 8px rgba(240, 91, 79, 0.3);"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(240, 91, 79, 0.4)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(240, 91, 79, 0.3)';">
                        ✓ Terapkan Filter
                    </button>
                    <a href="{{ route('admin.finances.index') }}"
                        style="background: white; color: #666; padding: 10px 20px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; text-decoration: none; transition: all 0.3s;"
                        onmouseover="this.style.background='#f5f5f5';" onmouseout="this.style.background='white';">
                        ↻ Reset
                    </a>
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

    @include('components.websocket-connector')
@endsection