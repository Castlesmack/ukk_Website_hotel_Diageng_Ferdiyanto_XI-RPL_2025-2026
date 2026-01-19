@extends('layouts.app')

@section('title', 'Villa Booking')

@section('content')
    <div style="background: #f5f5f5; min-height: 100vh; padding: 40px 20px;">
        <div style="max-width: 1000px; margin: 0 auto;">
            <h1 style="font-size: 24px; margin-bottom: 30px; color: #333;">{{ $villas->first()?->name ?? 'Villa' }}</h1>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <!-- Left: Booking Form -->
                <div style="background: #FAF2E8; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h2 style="font-size: 16px; font-weight: 600; color: #f05b4f; margin-bottom: 20px;">Booking Details</h2>

                    @if($errors->any())
                        <div
                            style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach($errors->all() as $error)
                                    <li style="font-size: 13px;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('guest.store.booking') }}" method="POST" id="bookingForm"
                        style="display: flex; flex-direction: column; gap: 18px;">
                        @csrf

                        @foreach($villas as $villa)
                            <input type="hidden" name="villa_id" value="{{ $villa->id }}">
                            @php $selectedVilla = $villa; @endphp
                        @endforeach

                        <!-- Check-in -->
                        <div>
                            <label
                                style="display: block; font-weight: 500; margin-bottom: 6px; font-size: 13px; color: #333;">Check-in:</label>
                            <input type="date" name="checkin" value="{{ old('checkin') }}" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;"
                                id="checkinInput">
                            <small style="display: block; color: #999; margin-top: 4px;">dd/mm/yyyy</small>
                        </div>

                        <!-- Check-out -->
                        <div>
                            <label
                                style="display: block; font-weight: 500; margin-bottom: 6px; font-size: 13px; color: #333;">Check
                                Out:</label>
                            <input type="date" name="checkout" value="{{ old('checkout') }}" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;"
                                id="checkoutInput">
                            <small style="display: block; color: #999; margin-top: 4px;">dd/mm/yyyy</small>
                        </div>

                        <!-- Number of Guests -->
                        <div>
                            <label
                                style="display: block; font-weight: 500; margin-bottom: 6px; font-size: 13px; color: #333;">Number
                                of Guests:</label>
                            <input type="number" name="guests" min="1" max="20" value="{{ old('guests', 2) }}" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                        </div>

                        <!-- Guest Name -->
                        <div>
                            <label
                                style="display: block; font-weight: 500; margin-bottom: 6px; font-size: 13px; color: #333;">Guest
                                Name:</label>
                            <input type="text" name="name" value="{{ old('name', 'Test User') }}" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                        </div>

                        <!-- Email -->
                        <div>
                            <label
                                style="display: block; font-weight: 500; margin-bottom: 6px; font-size: 13px; color: #333;">Email:</label>
                            <input type="email" name="email" value="{{ old('email', 'user@example.com') }}" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label
                                style="display: block; font-weight: 500; margin-bottom: 6px; font-size: 13px; color: #333;">Phone
                                Number:</label>
                            <input type="tel" name="phone" value="{{ old('phone', '081234567890') }}" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                        </div>

                        <!-- Special Requests -->
                        <div>
                            <label
                                style="display: block; font-weight: 500; margin-bottom: 6px; font-size: 13px; color: #333;">Special
                                Requests:</label>
                            <textarea name="special_requests" rows="3" placeholder="Any special requests or notes..."
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box; font-family: Arial; resize: vertical;">{{ old('special_requests') }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            style="background: #f05b4f; color: white; padding: 12px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 14px; margin-top: 10px;">
                            Continue to Payment
                        </button>
                    </form>
                </div>

                <!-- Right: Order Summary -->
                <div
                    style="background: #FAF2E8; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: fit-content; position: sticky; top: 20px;">
                    <h2 style="font-size: 16px; font-weight: 600; color: #f05b4f; margin-bottom: 20px;">Order Summary</h2>

                    <!-- Villa Info -->
                    <div style="padding-bottom: 15px; border-bottom: 1px solid #eee;">
                        <p style="margin: 0 0 8px 0; font-size: 13px; color: #666;">Villa:</p>
                        <p style="margin: 0; font-size: 14px; font-weight: 600; color: #333;">
                            {{ $villas->first()?->name ?? 'N/A' }}
                        </p>
                    </div>

                    <!-- Check-in & Check-out -->
                    <div style="padding: 15px 0; border-bottom: 1px solid #eee;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="font-size: 13px; color: #666;">Check in:</span>
                            <span style="font-size: 13px; color: #333;" class="checkin-summary">[Select date]</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="font-size: 13px; color: #666;">Check out:</span>
                            <span style="font-size: 13px; color: #333;" class="checkout-summary">[Select date]</span>
                        </div>
                    </div>

                    <!-- Guests -->
                    <div style="padding: 15px 0; border-bottom: 1px solid #eee;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="font-size: 13px; color: #666;">Guests:</span>
                            <span style="font-size: 13px; font-weight: 600; color: #333;" class="guests-summary">2</span>
                        </div>
                    </div>

                    <!-- Price -->
                    <div style="padding: 15px 0; border-bottom: 1px solid #eee;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="font-size: 13px; color: #666;">Price per night:</span>
                            <span style="font-size: 13px; font-weight: 600; color: #333;">Rp
                                {{ number_format($villas->first()?->base_price ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div style="padding: 15px 0;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="font-size: 14px; font-weight: 600; color: #333;">Total:</span>
                            <span style="font-size: 14px; font-weight: 700; color: #f05b4f;" class="total-summary">[Will be
                                calculated once dates are selected]</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const basePrice = {{ $villas->first()?->base_price ?? 0 }};

        function updateSummary() {
            const checkinInput = document.getElementById('checkinInput').value;
            const checkoutInput = document.getElementById('checkoutInput').value;
            const guestInput = document.querySelector('[name="guests"]').value;

            // Update guests
            document.querySelector('.guests-summary').textContent = guestInput;

            // Update dates
            if (checkinInput) {
                const checkinDate = new Date(checkinInput + 'T00:00:00');
                document.querySelector('.checkin-summary').textContent =
                    checkinDate.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
            }

            if (checkoutInput) {
                const checkoutDate = new Date(checkoutInput + 'T00:00:00');
                document.querySelector('.checkout-summary').textContent =
                    checkoutDate.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
            }

            // Calculate total
            if (checkinInput && checkoutInput) {
                const checkinDate = new Date(checkinInput + 'T00:00:00');
                const checkoutDate = new Date(checkoutInput + 'T00:00:00');
                const nights = Math.ceil((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24));

                if (nights > 0) {
                    const total = basePrice * nights;
                    document.querySelector('.total-summary').textContent =
                        `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
                }
            }
        }

        document.getElementById('checkinInput').addEventListener('change', updateSummary);
        document.getElementById('checkoutInput').addEventListener('change', updateSummary);
        document.querySelector('[name="guests"]').addEventListener('change', updateSummary);
    </script>
@endsection