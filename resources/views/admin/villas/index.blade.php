@extends('layouts.app')

@section('title', 'Manage Villa')

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
                    <div id="manage-menu" style="display: flex; flex-direction: column; gap: 5px; margin-left: 10px;">
                        <a href="{{ route('admin.villas.index') }}"
                            style="padding: 8px; background: #f05b4f; color: white; text-decoration: none; border-radius: 4px; font-size: 13px;">
                            Villa</a>
                        <a href="{{ route('admin.settings.homepage') }}"
                            style="padding: 8px; background: white; color: #333; text-decoration: none; border-radius: 4px; font-size: 13px;">
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1 style="margin: 0; font-size: 28px;">Halaman Data Villa</h1>
                <a href="{{ route('admin.villas.create') }}"
                    style="background: #f05b4f; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: 600;">+
                    Tambah Villa</a>
            </div>

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
                                Nama Tipe Villa</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Harga</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Deskripsi</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Status Villa</th>
                            <th style="padding: 12px; text-align: center; font-weight: 600; border-bottom: 1px solid #ddd;">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($villas as $villa)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 12px;">{{ $villa->name }}</td>
                                <td style="padding: 12px;">Rp {{ number_format($villa->base_price, 0, ',', '.') }}</td>
                                <td style="padding: 12px;">{{ Str::limit($villa->description, 50) }}</td>
                                <td style="padding: 12px;">
                                    <span
                                        style="background: {{ $villa->status === 'available' ? '#d4edda' : '#f8d7da' }}; color: {{ $villa->status === 'available' ? '#155724' : '#721c24' }}; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                        {{ ucfirst($villa->status) }}
                                    </span>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <a href="{{ route('admin.villas.edit', $villa) }}"
                                        style="color: #f05b4f; text-decoration: none; margin-right: 10px;">Edit</a>
                                    <form action="{{ route('admin.villas.destroy', $villa) }}" method="POST"
                                        style="display: inline;" onsubmit="return confirm('Delete?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            style="background: none; border: none; color: #dc3545; cursor: pointer; text-decoration: none;">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 20px; text-align: center; color: #666;">Tidak ada villa</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                {{ $villas->links() }}
            </div>
        </div>
    </div>
@endsection