@extends('layouts.app')

@section('title', 'My Profile')

@push('styles')
    <style>
        .profile-layout {
            display: flex;
            gap: 20px;
            margin: 20px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .sidebar {
            width: 200px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            background: #f8f9fa;
            height: fit-content;
        }

        .sidebar .menu-item {
            display: block;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: #333;
        }

        .sidebar .menu-item.active {
            background: #007bff;
            color: white;
        }

        .sidebar .menu-item:hover {
            background: #e9ecef;
        }

        .sidebar .menu-item.active:hover {
            background: #007bff;
        }

        .main-content {
            flex: 1;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 30px;
            background: white;
        }

        .main-content h4 {
            margin-top: 0;
            margin-bottom: 25px;
            color: #333;
            font-size: 20px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .btn-save {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-save:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush

@section('content')
    <div class="profile-layout">
        <aside class="sidebar">
            <a href="{{ route('user.profile') }}" class="menu-item active">Profile</a>
            <a href="{{ route('user.bookings') }}" class="menu-item">My Bookings</a>
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