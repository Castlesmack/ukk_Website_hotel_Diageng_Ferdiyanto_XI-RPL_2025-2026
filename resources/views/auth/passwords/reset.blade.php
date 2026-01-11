@extends('layouts.app')

@section('title', 'Reset Password - Ade Villa')

@php
    $hideHeader = true;
    $hideFooter = true;
@endphp

@push('styles')
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .reset-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        .reset-card h1 {
            text-align: center;
            margin-bottom: 10px;
            color: #333;
        }

        .reset-card p {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007bff;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s;
            font-weight: 600;
        }

        .btn-submit:hover {
            background: #0056b3;
        }

        .error-msg {
            color: #dc3545;
            text-align: center;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .success-msg {
            color: #28a745;
            text-align: center;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #007bff;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .exit-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            padding: 5px;
        }

        .exit-btn:hover {
            color: #333;
        }
    </style>
@endpush

@section('content')
    <div class="reset-card">
        <button class="exit-btn" onclick="window.location.href='/'">&times;</button>
        <h1>Set New Password</h1>
        <p>Enter your new password below.</p>

        @if($errors->any())
            <div class="error-msg">
                <strong>Error:</strong><br>
                @foreach($errors->all() as $error)
                    <small>{{ $error }}</small><br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                @error('password_confirmation')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Reset Password</button>
        </form>

        <div class="back-link">
            <a href="/login">Back to Login</a>
        </div>
    </div>
@endsection