@extends('layouts.app')

@section('title', 'My Profile')

@push('styles')
    <style>
        .profile-layout {
            display: flex;
            gap: 20px;
        }

        .sidebar {
            width: 200px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            background: #f8f9fa;
        }

        .sidebar .menu-item {
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .sidebar .menu-item.active {
            background: #007bff;
            color: white;
        }

        .main-content {
            flex: 1;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            background: white;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn-save {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-save:hover {
            background: #218838;
        }
    </style>
@endpush

@section('content')
    <div class="profile-layout">
        <aside class="sidebar">
            <div class="menu-item active">Profile</div>
            <div class="menu-item">My Bookings</div>
        </aside>
        <main class="main-content">
            <h4>My Profile</h4>
            <form method="POST" action="/user/profile">
                @csrf
                <div class="form-group">
                    <label for="name">Name*</label>
                    <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email*</label>
                    <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone*</label>
                    <input type="text" id="phone" name="phone" value="{{ auth()->user()->phone }}">
                </div>
                <div class="form-group">
                    <label for="password">New Password (leave blank to keep current)</label>
                    <input type="password" id="password" name="password">
                </div>
                <button type="submit" class="btn-save">Save Changes</button>
            </form>
        </main>
    </div>
@endsection
<input type="email" name="email" value="john@example.com">
<label>No Telp*</label>
<input type="text" name="phone" value="081234567890">
<label>Password*</label>
<input type="password" name="password" placeholder="New password">
<div style="text-align:right"><button class="btn">Simpan</button></div>
</form>
</main>
</div>
</body>

</html>