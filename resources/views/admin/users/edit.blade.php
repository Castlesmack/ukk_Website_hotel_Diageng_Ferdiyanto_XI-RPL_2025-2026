@extends('layouts.app')

@section('title', 'Edit Pengguna')

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
                    style="padding: 12px; background: #f05b4f; color: white; text-decoration: none; border-radius: 4px;">👥
                    Users</a>
                <a href="{{ route('admin.finances.index') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">💰
                    Finance</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div style="padding: 20px; background: white;">
            <h1 style="margin: 0 0 20px 0; font-size: 28px;">Edit Pengguna</h1>

            @if ($errors->any())
                <div
                    style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                    <strong>Terjadi kesalahan:</strong>
                    <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user) }}" method="POST"
                style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 30px; max-width: 500px;">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 20px;">
                    <label for="name"
                        style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="email"
                        style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="password"
                        style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Password <span
                            style="color: #999; font-weight: 400;">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" id="password" name="password"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="phone" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">No.
                        Telp</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="role"
                        style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Role</label>
                    <select id="role" name="role" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                        <option value="guest" {{ old('role', $user->role) == 'guest' ? 'selected' : '' }}>Guest</option>
                        <option value="receptionist" {{ old('role', $user->role) == 'receptionist' ? 'selected' : '' }}>
                            Receptionist</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('admin.users.index') }}"
                        style="flex: 1; padding: 10px; background: #6c757d; color: white; text-decoration: none; text-align: center; border-radius: 4px; cursor: pointer; border: none; font-weight: 500;">Batal</a>
                    <button type="submit"
                        style="flex: 1; padding: 10px; background: #f05b4f; color: white; border-radius: 4px; cursor: pointer; border: none; font-weight: 500;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection