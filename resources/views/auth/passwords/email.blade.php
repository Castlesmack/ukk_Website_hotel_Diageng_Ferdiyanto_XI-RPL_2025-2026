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
        }

        .form-group {
            margin-bottom: 20px;
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

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            color: #f05b4f;
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

        .success-msg {
            color: #28a745;
            text-align: center;
            margin-bottom: 15px;
            font-weight: 500;
        }
    </style>
@endpush

@section('content')
    <div class="reset-card">
        <button class="exit-btn" onclick="window.location.href='/'">&times;</button>
        <h1>Reset Password</h1>
        <p>Enter the email associated with your account and we'll send you a link to reset your password.</p>

        @if(session('status'))
            <div class="success-msg">{{ session('status') }}</div>
        @endif

        <form method="POST" action="/password/reset">
            @csrf
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <button type="submit" class="btn-submit">Send Reset Link</button>
        </form>

        <div class="back-link">
            <a href="/login">Back to Login</a>
        </div>
    </div>
@endsection