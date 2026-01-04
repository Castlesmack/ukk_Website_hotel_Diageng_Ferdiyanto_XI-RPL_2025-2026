@extends('layouts.app')

@section('title', 'Payment Success - Ade Villa')

@push('styles')
    <style>
        .result-container {
            max-width: 500px;
            margin: 50px auto;
            text-align: center;
            background: white;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .success-icon {
            font-size: 60px;
            color: #28a745;
            margin-bottom: 20px;
        }

        .result-title {
            font-size: 28px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 15px;
        }

        .result-message {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .booking-code {
            background: #f9f9f9;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            justify-content: center;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-primary {
            background: #28a745;
            color: white;
        }

        .btn-primary:hover {
            background: #218838;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .status-badge {
            display: inline-block;
            background: #d4edda;
            color: #155724;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            margin-top: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="result-container">
        <div class="success-icon">✓</div>

        <div class="result-title">
            @if(request('status') === 'pending')
                Payment Pending
            @else
                Payment Successful!
            @endif
        </div>

        <div class="result-message">
            @if(request('status') === 'pending')
                <p>Your payment is being processed. Please wait for confirmation.</p>
                <p style="font-size: 14px; color: #999;">This usually takes a few minutes.</p>
                <div class="status-badge">Status: Pending</div>
            @else
                <p>Thank you for your payment! Your booking has been confirmed.</p>
                <p style="font-size: 14px; color: #999;">A confirmation email will be sent shortly.</p>
                <div class="status-badge">Payment Successful</div>
            @endif
        </div>

        <div class="booking-code">
            Booking Code: {{ request('booking_code') ?? 'N/A' }}
        </div>

        <p style="color: #666; margin: 20px 0;">
            Please keep this booking code for your records.
        </p>

        <div class="action-buttons">
            <a href="/" class="btn btn-primary">Back to Home</a>
            <a href="{{ route('user.bookings') }}" class="btn btn-secondary">My Bookings</a>
        </div>
    </div>
@endsection