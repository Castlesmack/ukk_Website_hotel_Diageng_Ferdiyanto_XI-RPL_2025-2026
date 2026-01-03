@extends('layouts.app')

@section('title', 'Payment - Ade Villa')

@push('styles')
    <style>
        .payment-card {
            max-width: 420px;
            margin: 30px auto;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            padding: 20px;
            background: white;
        }

        .payment-card h4 {
            margin-top: 0;
            color: #333;
        }

        .amount {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }

        .payment-widget {
            height: 160px;
            border: 1px dashed #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin-top: 12px;
        }

        .pay-btn {
            padding: 12px 24px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .pay-btn:hover {
            background: #0056b3;
        }

        .pay-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
    </style>
@endpush

@section('content')
    <div class="payment-card">
        <h4>Villa Kota Bunga Ade</h4>
        <div class="amount">Rp 5,104,000</div>
        <hr>
        <div>
            <h5>Select Payment Method</h5>
            <div class="payment-widget">
                <button id="payBtn" class="pay-btn">Pay with Midtrans</button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const payBtn = document.getElementById('payBtn');
            payBtn.addEventListener('click', async function () {
                payBtn.disabled = true;
                payBtn.textContent = 'Requesting token...';

                try {
                    const res = await fetch('/midtrans/token', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' },
                        body: JSON.stringify({ amount: 5104000 })
                    });
                    const data = await res.json();
                    if (!res.ok) {
                        alert('Error: ' + (data.error || JSON.stringify(data)));
                        payBtn.disabled = false;
                        payBtn.textContent = 'Pay with Midtrans';
                        return;
                    }

                    const token = data.token;
                    const clientKey = data.client_key;

                    // Dynamically load Snap JS with client key (if provided in env)
                    const scriptUrl = 'https://app.sandbox.midtrans.com/snap/snap.js';
                    if (clientKey) {
                        const s = document.createElement('script');
                        s.src = scriptUrl;
                        s.setAttribute('data-client-key', clientKey);
                        document.body.appendChild(s);
                        s.onload = function () {
                            // call snap.pay
                            window.snap.pay(token, {
                                onSuccess: function (result) { window.location = '/guest/payment/success'; },
                                onPending: function (result) { window.location = '/guest/payment'; },
                                onError: function (result) { window.location = '/guest/payment/failed'; }
                            });
                        };
                    } else {
                        // No client key configured — open midtrans checkout if token present
                        if (window.snap) {
                            window.snap.pay(token);
                        } else {
                            // If client key not set, open a simple redirect to Midtrans (not ideal)
                            alert('Midtrans client key not configured. Please set MIDTRANS_CLIENT_KEY in .env for full flow. Token: ' + token);
                        }
                    }

                } catch (err) {
                    alert('Request failed: ' + err.message);
                    payBtn.disabled = false;
                    payBtn.textContent = 'Pay with Midtrans';
                }
            });
        })();
    </script>
@endsection