@extends('layouts.app')

@section('title', 'Edit Facility')

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
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
                <a href="{{ route('admin.settings.facilities') }}" style="font-size: 20px; text-decoration: none;">←
                    Kembali</a>
                <h1 style="margin: 0; font-size: 28px;">Edit Fasilitas</h1>
            </div>

            @if ($errors->any())
                <div
                    style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Edit Form -->
            <div style="background: white; padding: 30px; border-radius: 8px; border: 1px solid #ddd; max-width: 500px;">
                <form action="{{ route('admin.settings.facilities.update', $facility->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Kategori</label>
                        <select name="category" required
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
                            <option value="public_facilities" {{ $facility->category === 'public_facilities' ? 'selected' : '' }}>Public Facilities</option>
                            <option value="connectivity" {{ $facility->category === 'connectivity' ? 'selected' : '' }}>
                                Connectivity</option>
                            <option value="other_activities" {{ $facility->category === 'other_activities' ? 'selected' : '' }}>Other Activities</option>
                            <option value="transportation" {{ $facility->category === 'transportation' ? 'selected' : '' }}>
                                Transportation</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Nama
                            Fasilitas</label>
                        <input type="text" name="name" value="{{ $facility->name }}" required placeholder="Contoh: WiFi"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit"
                            style="padding: 10px 20px; background: #f05b4f; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; flex: 1;">
                            💾 Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.settings.facilities') }}"
                            style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; text-decoration: none; text-align: center; flex: 1;">
                            ❌ Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection