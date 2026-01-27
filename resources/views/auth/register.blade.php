@extends('layouts.app')

@section('title', 'Register - Ade Villa')

@php
    $hideHeader = true;
    $hideFooter = true;
@endphp

@push('styles')
    <style>
        body {
            background: #FAF2E8 min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: 55px;
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

        <form method="POST" action="/register" id="registerForm" name="registerForm">
            @csrf
            <div class="form-group">
                <label for="registerFullName">Full Name <span style="color: #dc3545;">*</span></label>
                <input type="text" id="registerFullName" name="name" value="{{ old('name') }}" placeholder="John Doe"
                    maxlength="255" required autocomplete="name" aria-label="Full Name">
                @error('name')
                    <small style="color: #dc3545;" role="alert">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="registerEmail">Email Address <span style="color: #dc3545;">*</span></label>
                <input type="email" id="registerEmail" name="email" value="{{ old('email') }}" placeholder="your@email.com"
                    required autocomplete="email" aria-label="Email Address">
                @error('email')
                    <small style="color: #dc3545;" role="alert">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="registerPhone">Phone Number <span style="color: #dc3545;">*</span></label>
                <input type="tel" id="registerPhone" name="phone" value="{{ old('phone') }}" placeholder="08123456789"
                    maxlength="20" pattern="[0-9\-\+\s]+" required autocomplete="tel" aria-label="Phone Number">
                @error('phone')
                    <small style="color: #dc3545;" role="alert">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="registerPassword">Password <span style="color: #dc3545;">*</span></label>
                <input type="password" id="registerPassword" name="password" required minlength="8"
                    placeholder="Min. 8 characters" autocomplete="new-password" aria-label="Password">
                <small style="color: #666; display: block; margin-top: 5px;">Minimum 8 characters</small>
                @error('password')
                    <small style="color: #dc3545;" role="alert">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="registerPasswordConfirm">Confirm Password <span style="color: #dc3545;">*</span></label>
                <input type="password" id="registerPasswordConfirm" name="password_confirmation" required minlength="8"
                    placeholder="Re-enter password" autocomplete="new-password" aria-label="Confirm Password">
                @error('password_confirmation')
                    <small style="color: #dc3545;" role="alert">{{ $message }}</small>
                @enderror
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="registerConfirm" name="confirm" value="yes" required
                    aria-label="Confirm details">
                <label for="registerConfirm">I confirm all the details I entered are correct <span
                        style="color: #dc3545;">*</span></label>
            </div>
            @error('confirm')
                <small style="color: #dc3545; display: block; margin-bottom: 15px;" role="alert">{{ $message }}</small>
            @enderror

            <button type="submit" class="btn-submit" id="registerSubmitBtn">Create Account</button>
        </form>

        <div style="margin-top: 20px; text-align: center; font-size: 14px;">
            <p style="margin: 0; color: #666;">Already have an account? <a href="/login"
                    style="color: #f05b4f; text-decoration: none; font-weight: 600;">Login here</a></p>
        </div>
    </div>
@endsection