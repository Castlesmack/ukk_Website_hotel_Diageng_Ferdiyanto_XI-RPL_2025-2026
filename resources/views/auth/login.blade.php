@extends('layouts.app')

@section('title', 'Login - Ade Villa')

@php
    $hideHeader = true;
    $hideFooter = true;
@endphp

@push('styles')
    <style>
        body {
            background: #FAF2E8;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: 150px;
        }

        .login-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        .login-card h1 {
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
            border-color: #f05b4f;
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
            background: #f05b4f;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-submit:hover {
            background: #d84539;
        }

        .error-msg {
            color: #dc3545;
            text-align: center;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .forgot-link {
            text-align: center;
            margin-top: 15px;
        }

        .forgot-link a {
            color: #f05b4f;
            text-decoration: none;
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

        img {
            max-width: 150px;
            height: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
    </style>
@endpush

@section('content')
    <div class="login-card">
        <button class="exit-btn" onclick="window.location.href='/'">&times;</button>
        <h1>Login</h1>

        @if($errors->any())
            <div class="error-msg">
                <strong>Login Failed:</strong><br>
                @foreach($errors->all() as $error)
                    <small>{{ $error }}</small><br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div class="form-group">
                <label for="email">Email Address <span style="color: #dc3545;">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password <span style="color: #dc3545;">*</span></label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="confirm" name="confirm" required>
                <label for="confirm" style="margin-bottom: 0;">I confirm all the details I entered are correct</label>
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>

        <div style="margin-top: 20px; text-align: center; font-size: 14px;">
            <p style="margin: 0 0 10px 0; color: #666;">Don't have an account? <a href="/register"
                    style="color: #f05b4f; text-decoration: none; font-weight: 600;">Register here</a></p>
        </div>
    </div>
@endsection