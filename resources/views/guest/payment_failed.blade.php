@extends('layouts.app')

@section('title', 'Payment Failed - Ade Villa')

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

        .error-icon {
            font-size: 60px;
            color: #dc3545;
            margin-bottom: 20px;
        }

        .result-title {
            font-size: 28px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 15px;
        }

        .result-message {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .error-details {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: left;
            font-size: 14px;
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

        .btn-danger {
            background: #f05b4f;
            color: white;
        }

        .btn-danger:hover {
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
            background: #f8d7da;
            color: #721c24;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            margin-top: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="result-container">
        <div class="error-icon">✕</div>

        <div class="result-title">Payment Failed!</div>

        <div class="result-message">
            <p>Unfortunately, your payment could not be processed.</p>
            <p style="font-size: 14px; color: #999;">Please try again or contact our support team if the problem persists.
            </p>
            <div class="status-badge">Status: Failed</div>
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
                    {{ number_format($booking->total_price, 0, ',', '.') }}
                </p>
            </div>
        @endif

        <div class="error-details">
            <strong>Common reasons for payment failure:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Insufficient balance in your account</li>
                <li>Invalid card information</li>
                <li>Network or connection issues</li>
                <li>Transaction limit exceeded</li>
            </ul>
        </div>

        @if($error_message)
            <div
                style="background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 4px; margin-bottom: 20px; font-size: 14px;">
                <strong>Error Details:</strong> {{ is_array($error_message) ? json_encode($error_message) : $error_message }}
            </div>
        @endif

        <div class="action-buttons">
            @if($booking)
                <a href="{{ route('guest.payment', $booking->id) }}" class="btn btn-danger">Try Again</a>
            @else
                <a href="javascript:history.back()" class="btn btn-danger">Try Again</a>
            @endif
            <a href="/" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>
@endsection