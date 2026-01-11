@extends('layouts.app')

@section('title', 'Register - Ade Villa')

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

        .register-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        .register-card h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
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
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            font-size: 14px;
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
    <div class="register-card">
        <button class="exit-btn" onclick="window.location.href='/'">&times;</button>
        <h1>Register</h1>

        @if($errors->any())
            <div class="error-msg">
                <strong>Registration Failed:</strong><br>
                @foreach($errors->all() as $error)
                    <small>{{ $error }}</small><br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf
            <div class="form-group">
                <label for="name">Full Name <span style="color: #dc3545;">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address <span style="color: #dc3545;">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Phone Number <span style="color: #dc3545;">*</span></label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required>
                @error('phone')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password <span style="color: #dc3545;">*</span></label>
                <input type="password" id="password" name="password" required>
                <small style="color: #666; display: block; margin-top: 5px;">Minimum 8 characters</small>
                @error('password')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password <span style="color: #dc3545;">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                @error('password_confirmation')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="confirm" name="confirm" value="yes" required>
                <label for="confirm">I confirm all the details I entered are correct <span
                        style="color: #dc3545;">*</span></label>
            </div>
            @error('confirm')
                <small style="color: #dc3545; display: block; margin-bottom: 15px;">{{ $message }}</small>
            @enderror

            <button type="submit" class="btn-submit">Create Account</button>
        </form>

        <div style="margin-top: 20px; text-align: center; font-size: 14px;">
            <p style="margin: 0; color: #666;">Already have an account? <a href="/login"
                    style="color: #007bff; text-decoration: none; font-weight: 600;">Login here</a></p>
        </div>
    </div>
@endsection