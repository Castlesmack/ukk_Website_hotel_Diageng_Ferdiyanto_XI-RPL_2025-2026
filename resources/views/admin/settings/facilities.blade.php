@extends('layouts.app')

@section('title', 'Manage Facilities')

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
                            style="padding: 8px; background: white; color: #333; text-decoration: none; border-radius: 4px; font-size: 13px;">
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
        <div style="padding: 20px;">
            <h1 style="margin: 0 0 30px 0; font-size: 28px;">Manage Facilities</h1>

            @if (session('success'))
                <div
                    style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Add Facility Form -->
            <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 30px;">
                <h3 style="margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">Tambah Fasilitas Baru</h3>
                <form action="{{ route('admin.settings.facilities.store') }}" method="POST"
                    style="display: grid; gap: 10px;">
                    @csrf
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Kategori</label>
                        <select name="category" required
                            style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                            <option value="">Pilih Kategori</option>
                            <option value="public_facilities">Public Facilities</option>
                            <option value="connectivity">Connectivity</option>
                            <option value="other_activities">Other Activities</option>
                            <option value="transportation">Transportation</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Nama Fasilitas</label>
                        <input type="text" name="name" required placeholder="Contoh: WiFi"
                            style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                    </div>
                    <button type="submit"
                        style="padding: 8px 16px; background: #f05b4f; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">
                        ➕ Tambah Fasilitas
                    </button>
                </form>
            </div>

            <!-- Facilities List -->
            <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
                <h3 style="margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">Daftar Fasilitas</h3>

                @php
                    $grouped = $facilities->groupBy('category');
                @endphp

                @if ($facilities->count() > 0)
                    @foreach ($grouped as $category => $items)
                        <div style="margin-bottom: 20px;">
                            <h4
                                style="margin: 0 0 10px 0; font-size: 14px; font-weight: 600; text-transform: capitalize; padding-bottom: 10px; border-bottom: 2px solid #f05b4f;">
                                {{ str_replace('_', ' ', $category) }}
                            </h4>
                            <div style="display: grid; gap: 0;">
                                @foreach ($items as $facility)
                                    <div
                                        style="padding: 15px; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: space-between; background: {{ $facility->is_visible ? '#ffffff' : '#f9f9f9' }}; transition: background 0.2s;">
                                        <div style="flex: 1;">
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <strong style="font-size: 15px;">{{ $facility->name }}</strong>
                                                <span
                                                    style="background: {{ $facility->is_visible ? '#d4edda' : '#f8d7da' }}; color: {{ $facility->is_visible ? '#155724' : '#721c24' }}; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                                    {{ $facility->is_visible ? '✓ TERLIHAT' : '✕ TERSEMBUNYI' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div style="display: flex; gap: 8px; align-items: center;">
                                            <a href="{{ route('admin.settings.facilities.edit', $facility->id) }}"
                                                style="padding: 6px 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 500; transition: background 0.2s; text-decoration: none; display: inline-block;"
                                                title="Edit">
                                                ✏️ Edit
                                            </a>
                                            <form action="{{ route('admin.settings.facilities.toggle', $facility->id) }}" method="POST"
                                                style="margin: 0;">
                                                @csrf
                                                <button type="submit"
                                                    style="padding: 6px 12px; background: {{ $facility->is_visible ? '#ffc107' : '#28a745' }}; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 500; transition: background 0.2s;"
                                                    title="{{ $facility->is_visible ? 'Sembunyikan' : 'Tampilkan' }}">
                                                    {{ $facility->is_visible ? '👁️ Sembunyikan' : '👁️‍🗨️ Tampilkan' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.settings.facilities.destroy', $facility->id) }}" method="POST"
                                                style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Hapus fasilitas ini?')"
                                                    style="padding: 6px 12px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: 500; transition: background 0.2s;"
                                                    title="Hapus">
                                                    🗑️ Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <p style="color: #666;">Belum ada fasilitas. Tambahkan fasilitas baru di atas.</p>
                @endif
            </div>
        </div>
    </div>
@endsection