@extends('layouts.app')

@section('title', 'Tambah Villa')

@section('content')
    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px; margin: 0 -20px; padding: 0;">
        <!-- Sidebar -->
        <div style="background: white; padding: 20px; min-height: 100vh;">
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
        <div style="padding: 20px; background: white; max-width: 800px;">
            <h1 style="margin: 0 0 20px 0; font-size: 28px;">Tambah Villa Baru</h1>

            @if ($errors->any())
                <div
                    style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                    <strong>Validation Errors:</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.villas.store') }}" method="POST" enctype="multipart/form-data"
                style="background: white; padding: 20px; border-radius: 8px;">
                @csrf

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Villa *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" required>
                    @error('name') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                    <small style="color: #666; display: block; margin-top: 5px;">Slug will be auto-generated from
                        name</small>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Kapasitas *</label>
                        <input type="number" name="capacity" value="{{ old('capacity') }}"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" required>
                        @error('capacity') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Jumlah Kamar *</label>
                        <input type="number" name="rooms_total" value="{{ old('rooms_total') }}"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" required>
                        @error('rooms_total') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Harga Per Malam (Rp) *</label>
                    <input type="number" name="base_price" value="{{ old('base_price') }}"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" required>
                    @error('base_price') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Deskripsi *</label>
                    <textarea name="description"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; min-height: 100px;"
                        required>{{ old('description') }}</textarea>
                    @error('description') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Status *</label>
                    <select name="status" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"
                        required>
                        <option value="">-- Select Status --</option>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance
                        </option>
                        <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>Available (Legacy)
                        </option>
                        <option value="unavailable" {{ old('status') === 'unavailable' ? 'selected' : '' }}>Unavailable
                            (Legacy)</option>
                    </select>
                    @error('status') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Thumbnail Image</label>
                    <input type="file" name="thumbnail" accept="image/*"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <small style="color: #666;">Max file size: 2MB. Format: JPG, PNG, GIF</small>
                    @error('thumbnail') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Gallery Images</label>
                    <input type="file" name="images[]" multiple accept="image/*"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <small style="color: #666;">You can select multiple images at once. Max 2MB per image.</small>
                    @error('images') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit"
                        style="background: #f05b4f; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">Simpan</button>
                    <a href="{{ route('admin.villas.index') }}"
                        style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: 600;">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection