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
            background: #FAF2E8;
            border: 2px solid #f05b4f;
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
            background: #f05b4f;
            color: white;
        }

        .btn-primary:hover {
            background: #d84539;
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
        @if($status === 'error')
            <div class="success-icon" style="color: #dc3545;">✕</div>
            <div class="result-title" style="color: #dc3545;">Payment Error</div>
            <div class="result-message">
                <p>{{ $message ?? 'An error occurred during payment processing.' }}</p>
            </div>
            @if($booking)
                <div class="booking-code">
                    Booking Code: {{ $booking->booking_code }}
                </div>
                <div class="action-buttons">
                    <a href="{{ route('guest.payment', $booking->id) }}" class="btn btn-primary">Try Again</a>
                    <a href="/" class="btn btn-secondary">Back to Home</a>
                </div>
            @endif
        @else
            <div class="success-icon">✓</div>
            <div class="result-title">
                @if($status === 'pending')
                    Payment Pending
                @else
                    Payment Successful!
                @endif
            </div>

            <div class="result-message">
                @if($status === 'pending')
                    <p>Your payment is being processed. Please wait for confirmation.</p>
                    <p style="font-size: 14px; color: #999;">This usually takes a few minutes.</p>
                    <div class="status-badge">Status: Pending</div>
                @else
                    <p>Thank you for your payment! Your booking has been confirmed.</p>
                    <p style="font-size: 14px; color: #999;">A confirmation email will be sent shortly.</p>
                    <div class="status-badge">Payment Successful</div>
                @endif
            </div>

            @if($booking)
                <div style="background: #FAF2E8; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: left;">
                    <h4 style="margin-top: 0; color: #333;">Booking Details</h4>
                    <p style="margin: 8px 0;"><strong>Booking Code:</strong> {{ $booking->booking_code }}</p>
                    <p style="margin: 8px 0;"><strong>Villa:</strong> {{ $booking->villa->name ?? 'N/A' }}</p>
                    <p style="margin: 8px 0;"><strong>Check-in:</strong> {{ $booking->check_in_date->format('d M Y') }}</p>
                    <p style="margin: 8px 0;"><strong>Check-out:</strong> {{ $booking->check_out_date->format('d M Y') }}</p>
                    <p style="margin: 8px 0;"><strong>Guest Name:</strong> {{ $booking->guest_name }}</p>
                    <p style="margin: 8px 0;"><strong>Guest Email:</strong> {{ $booking->guest_email }}</p>
                    <p style="margin: 8px 0; border-top: 1px solid #ddd; padding-top: 10px;"><strong>Total Price:</strong> Rp
                        {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                </div>
            @else
                <div class="booking-code">
                    Booking Code: {{ request('booking_code') ?? 'N/A' }}
                </div>
            @endif

            <p style="color: #666; margin: 20px 0;">
                Please keep this booking code for your records.
            </p>

            <div class="action-buttons">
                <a href="/" class="btn btn-primary">Back to Home</a>
                <a href="{{ route('user.bookings') }}" class="btn btn-secondary">My Bookings</a>
            </div>
        @endif
    </div>
@endsection