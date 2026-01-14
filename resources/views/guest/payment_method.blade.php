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
            color: #f05b4f;
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
            background: #f05b4f;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .pay-btn:hover {
            background: #d84539;
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
        // Load Midtrans Snap.js globally
        const clientKey = '{{ env("MIDTRANS_CLIENT_KEY") }}';
        const snapScript = document.createElement('script');
        snapScript.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
        snapScript.setAttribute('data-client-key', clientKey);
        document.head.appendChild(snapScript);

        document.getElementById('payBtn').addEventListener('click', async function () {
            const payBtn = this;
            payBtn.disabled = true;
            payBtn.textContent = 'Requesting token...';

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const res = await fetch('/midtrans/token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        amount: 5104000,
                        order_id: 'ORDER-' + Date.now(),
                        name: 'Guest User',
                        email: 'guest@example.com'
                    })
                });

                const data = await res.json();

                if (!res.ok) {
                    const errorMsg = data.error || data.message || JSON.stringify(data);
                    console.error('Token request failed:', data);
                    alert('Payment Error: ' + errorMsg);
                    payBtn.disabled = false;
                    payBtn.textContent = 'Pay with Midtrans';
                    return;
                }

                const token = data.token;
                if (!token) {
                    console.error('No token received:', data);
                    alert('No payment token received');
                    payBtn.disabled = false;
                    payBtn.textContent = 'Pay with Midtrans';
                    return;
                }

                // Wait for Snap to be loaded
                let attempts = 0;
                const checkSnap = setInterval(function () {
                    if (window.snap) {
                        clearInterval(checkSnap);
                        window.snap.pay(token, {
                            onSuccess: function (result) {
                                console.log('Payment success:', result);
                                window.location = '/guest/payment/success';
                            },
                            onPending: function (result) {
                                console.log('Payment pending:', result);
                                window.location = '/guest/payment';
                            },
                            onError: function (result) {
                                console.log('Payment error:', result);
                                window.location = '/guest/payment/failed';
                            },
                            onClose: function () {
                                console.log('Payment dialog closed');
                                payBtn.disabled = false;
                                payBtn.textContent = 'Pay with Midtrans';
                            }
                        });
                    } else if (++attempts > 100) {
                        clearInterval(checkSnap);
                        console.error('Snap.js failed to load');
                        alert('Payment gateway failed to load. Please refresh and try again.');
                        payBtn.disabled = false;
                        payBtn.textContent = 'Pay with Midtrans';
                    }
                }, 100);

            } catch (error) {
                console.error('Payment error:', error);
                alert('Payment Error: ' + error.message);
                payBtn.disabled = false;
                payBtn.textContent = 'Pay with Midtrans';
            }
        });
    </script>
@endsection