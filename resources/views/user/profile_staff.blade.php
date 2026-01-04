@extends('layouts.app')

@section('title', 'My Profile')

@push('styles')
    <style>
        .profile-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-container h2 {
            margin-top: 0;
            margin-bottom: 30px;
            color: #333;
            font-size: 24px;
            font-weight: 600;
            text-align: center;
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
            width: 100%;
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-save:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .profile-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 30px;
        }

        .profile-info div {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .profile-info strong {
            color: #495057;
        }
    </style>
@endpush

@section('content')
    <div class="profile-container">
        <h2>Profile</h2>

        <div class="profile-info">
            <div><strong>Role:</strong> {{ ucfirst(auth()->user()->role) }}</div>
            <div><strong>Email:</strong> {{ auth()->user()->email }}</div>
        </div>

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
    </div>
@endsection