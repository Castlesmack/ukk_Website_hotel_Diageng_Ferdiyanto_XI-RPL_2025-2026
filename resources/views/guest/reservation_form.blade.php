@extends('layouts.app')

@section('title', 'Reservation Form')

@push('styles')
    <style>
        .reservation-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .form-section {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            background: white;
        }

        .form-section h3 {
            margin-top: 0;
            color: #007bff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .continue-btn {
            background: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .continue-btn:hover {
            background: #0056b3;
        }

        .order-summary {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            background: white;
            height: fit-content;
        }

        .order-summary h4 {
            margin-top: 0;
            color: #007bff;
        }

        .summary-item {
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .total-price {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin: 15px 0;
        }

        .payment-btn {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .payment-btn:hover {
            background: #218838;
        }

        .back-link {
            color: #007bff;
            text-decoration: none;
            margin-bottom: 20px;
            display: inline-block;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    <a href="/" class="back-link">&larr; Back to Home</a>
    <h2>{{ $villa ? $villa->name : 'Villa Reservation' }}</h2>

    <div class="reservation-layout">
        <div class="form-section">
            <h3>Booking Details</h3>
            <form action="{{ route('guest.reservation.store') }}" method="POST">
                @csrf
                <input type="hidden" name="villa_id" value="{{ $villa->id ?? '' }}">
                <div class="form-group">
                    <label>Check In:</label>
                    <input type="date" name="checkin" value="{{ $checkin }}" required>
                </div>
                <div class="form-group">
                    <label>Check Out:</label>
                    <input type="date" name="checkout" value="{{ $checkout }}" required>
                </div>
                <div class="form-group">
                    <label>Number of Guests:</label>
                    <input type="number" name="guests" value="{{ $guests }}" min="1" max="10" required>
                </div>
                <div class="form-group">
                    <label>Guest Name:</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" required>
                </div>
                <div class="form-group">
                    <label>Phone Number:</label>
                    <input type="tel" name="phone" value="{{ auth()->user()->phone }}" required>
                </div>
                <div class="form-group">
                    <label>Special Requests:</label>
                    <textarea name="special_requests" placeholder="Any special requests or notes..."></textarea>
                </div>
                <div style="text-align: right;">
                    <button type="submit" class="continue-btn">Continue to Payment</button>
                </div>
            </form>
        </div>

        <aside class="order-summary">
            <h4>Order Summary</h4>
            @if($villa)
                <div class="summary-item">
                    <strong>Villa:</strong> {{ $villa->name }}
                </div>
            @endif
            <div class="summary-item">
                <strong>Check in:</strong> {{ $checkin ?: '[Select date]' }}
            </div>
            <div class="summary-item">
                <strong>Check out:</strong> {{ $checkout ?: '[Select date]' }}
            </div>
            <div class="summary-item">
                <strong>Guests:</strong> {{ $guests }}
            </div>
            @if($villa && $checkin && $checkout)
                @php
                    $checkInDate = \Carbon\Carbon::parse($checkin);
                    $checkOutDate = \Carbon\Carbon::parse($checkout);
                    $nights = $checkOutDate->diffInDays($checkInDate);
                    $totalPrice = $nights * $villa->base_price;
                @endphp
                <div class="summary-item">
                    <strong>Price per night:</strong> Rp {{ number_format($villa->base_price, 0, ',', '.') }}
                </div>
                <div class="summary-item">
                    <strong>Number of nights:</strong> {{ $nights }}
                </div>
                <div class="total-price">
                    Total: Rp {{ number_format($totalPrice, 0, ',', '.') }}
                </div>
            @elseif($villa)
                <div class="summary-item">
                    <strong>Price per night:</strong> Rp {{ number_format($villa->base_price, 0, ',', '.') }}
                </div>
                <div class="total-price">
                    Total: [Will be calculated once dates are selected]
                </div>
            @endif
        </aside>
    </div>
@endsection