@extends('layouts.app')

@section('title', 'Payment - Ade Villa')

@push('styles')
    <style>
        .payment-container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .payment-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .payment-header h2 {
            margin: 0 0 10px;
            color: #333;
        }

        .booking-info {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #666;
        }

        .info-value {
            color: #333;
        }

        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
        }

        .payment-method {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #ddd;
        }

        .payment-method h3 {
            margin-top: 0;
            color: #333;
        }

        .payment-btn {
            width: 100%;
            padding: 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .payment-btn:hover {
            background: #218838;
        }

        .payment-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .error-message {
            display: none;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .success-message {
            display: none;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="payment-container">
        <div class="payment-header">
            <h2>Payment</h2>
            <p>Booking ID: <strong>{{ $booking->booking_code }}</strong></p>
        </div>

        <div class="error-message" id="errorMsg"></div>
        <div class="success-message" id="successMsg"></div>

        <div class="booking-info">
            <div class="info-row">
                <span class="info-label">Villa Name:</span>
                <span class="info-value">{{ $booking->villa->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Guest Name:</span>
                <span class="info-value">{{ $booking->guest_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Check In:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Check Out:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Number of Guests:</span>
                <span class="info-value">{{ $booking->guest_count }} people</span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span class="info-value">{{ $booking->guest_phone }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $booking->guest_email }}</span>
            </div>

            <div class="total-amount">
                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
            </div>
        </div>

        <div class="payment-method">
            <h3>Payment Method</h3>
            <p style="color: #666; margin-bottom: 20px;">Complete your payment securely via Midtrans</p>

            <button type="button" class="payment-btn" id="paymentBtn" onclick="processPayment()">
                Pay Now with Midtrans
            </button>

            <div class="loading-spinner" id="loadingSpinner">
                <div class="spinner"></div>
                <p>Processing payment...</p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script>
            function processPayment() {
                const btn = document.getElementById('paymentBtn');
                const loader = document.getElementById('loadingSpinner');
                const errorMsg = document.getElementById('errorMsg');

                btn.disabled = true;
                loader.style.display = 'block';
                errorMsg.style.display = 'none';

                // Send request to backend to get Midtrans token
                fetch('{{ route("midtrans.token") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        amount: {{ $booking->total_price }},
                        email: '{{ $booking->guest_email }}',
                        name: '{{ $booking->guest_name }}',
                        order_id: '{{ $booking->booking_code }}'
                    })
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(`HTTP ${response.status}: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Token response:', data);
                        loader.style.display = 'none';

                        if (data.error) {
                            showError('Error: ' + data.error);
                            btn.disabled = false;
                            return;
                        }

                        // Open Midtrans payment page
                        snap.pay(data.token, {
                            onSuccess: function (result) {
                                handlePaymentSuccess(result);
                            },
                            onPending: function (result) {
                                handlePaymentPending(result);
                            },
                            onError: function (result) {
                                handlePaymentError(result);
                            },
                            onClose: function () {
                                loader.style.display = 'none';
                                btn.disabled = false;
                                console.log('Payment window closed');
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Full error:', error);
                        loader.style.display = 'none';
                        btn.disabled = false;
                        showError('Connection error: ' + error.message);
                    });
            }

            function handlePaymentSuccess(result) {
                console.log('Payment successful:', result);
                // Redirect to success page
                window.location.href = '{{ route("guest.payment.success") }}?booking_code={{ $booking->booking_code }}';
            }

            function handlePaymentPending(result) {
                console.log('Payment pending:', result);
                window.location.href = '{{ route("guest.payment.success") }}?booking_code={{ $booking->booking_code }}&status=pending';
            }

            function handlePaymentError(result) {
                console.log('Payment error:', result);
                showError('Payment failed. Please try again.');
                document.getElementById('paymentBtn').disabled = false;
                document.getElementById('loadingSpinner').style.display = 'none';
            }

            function showError(message) {
                const errorMsg = document.getElementById('errorMsg');
                errorMsg.textContent = message;
                errorMsg.style.display = 'block';
            }
        </script>
    @endpush
@endsection