@extends('layouts.app')

@section('title', 'Real-Time Orders Dashboard')

@section('content')
    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px; margin: 0 -20px; padding: 0;">
        <!-- Sidebar -->
        <div style="background: #f8f9fa; padding: 20px; min-height: 100vh;">
            <h2 style="margin: 0 0 30px 0; font-size: 18px; font-weight: 600;">Menu</h2>
            <nav style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('admin.dashboard') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">📊
                    Dashboard</a>
                <a href="{{ route('admin.reservations.index') }}"
                    style="padding: 12px; background: #f05b4f; color: white; text-decoration: none; border-radius: 4px;">📅
                    Real-Time Orders</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div style="padding: 20px; background: white;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h1 style="margin: 0; font-size: 28px;">🚀 Real-Time Orders</h1>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span id="connectionStatus"
                        style="display: inline-flex; align-items: center; gap: 6px; background: #f8f9fa; padding: 8px 12px; border-radius: 4px; font-size: 12px;">
                        <span id="statusDot"
                            style="width: 8px; height: 8px; background: #dc3545; border-radius: 50%; display: inline-block;"></span>
                        <span id="statusText">Connecting...</span>
                    </span>
                </div>
            </div>

            <!-- Filter & Stats -->
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 25px;">
                <div style="background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;">
                    <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Pending Orders</p>
                    <p style="margin: 8px 0 0 0; font-size: 24px; font-weight: bold; color: #ffc107;" id="pendingCount">0
                    </p>
                </div>
                <div style="background: #d1ecf1; padding: 15px; border-radius: 8px; border-left: 4px solid #17a2b8;">
                    <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Confirmed</p>
                    <p style="margin: 8px 0 0 0; font-size: 24px; font-weight: bold; color: #17a2b8;" id="confirmedCount">0
                    </p>
                </div>
                <div style="background: #d4edda; padding: 15px; border-radius: 8px; border-left: 4px solid #28a745;">
                    <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase;">Total Today</p>
                    <p style="margin: 8px 0 0 0; font-size: 24px; font-weight: bold; color: #28a745;" id="totalCount">0</p>
                </div>
            </div>

            <!-- Orders Feed -->
            <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px;">
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">📥 New Orders (Live Feed)</h3>

                <div id="ordersFeed"
                    style="max-height: 500px; overflow-y: auto; border: 1px solid #eee; border-radius: 4px; padding: 0;">
                    <div style="padding: 40px; text-align: center; color: #999;">
                        <p>⏳ Waiting for new orders...</p>
                    </div>
                </div>
            </div>

            <!-- All Bookings Table -->
            <div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-top: 20px;">
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">All Orders</h3>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    ID</th>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Guest</th>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Villa</th>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Check In</th>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Check Out</th>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Amount</th>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Status</th>
                                <th
                                    style="padding: 12px; text-align: left; font-weight: 600; font-size: 12px; border-bottom: 1px solid #ddd;">
                                    Created</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTable">
                            @foreach($recentBookings as $booking)
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 12px; font-weight: 600; color: #f05b4f;">{{ $booking->id }}</td>
                                    <td style="padding: 12px;">{{ $booking->guest_name ?? $booking->user->name }}</td>
                                    <td style="padding: 12px;">{{ $booking->villa->name }}</td>
                                    <td style="padding: 12px;">{{ $booking->check_in_date->format('d M Y') }}</td>
                                    <td style="padding: 12px;">{{ $booking->check_out_date->format('d M Y') }}</td>
                                    <td style="padding: 12px; font-weight: 600;">Rp
                                        {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </td>
                                    <td style="padding: 12px;">
                                        <span
                                            style="background: {{ $booking->status == 'pending' ? '#fff3cd' : '#d4edda' }}; color: {{ $booking->status == 'pending' ? '#856404' : '#155724' }}; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px; font-size: 12px; color: #666;">
                                        {{ $booking->created_at->format('H:i:s') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.0/dist/echo.iife.js"></script>
    <script>
        // Initialize Laravel Echo
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ env("REVERB_APP_KEY") }}',
            wsHost: '{{ env("REVERB_HOST") }}',
            wsPort: {{ env("REVERB_PORT") }},
            wssPort: {{ env("REVERB_PORT") }},
            forceTLS: ({{ env("REVERB_SCHEME") }} === 'https'),
            encrypted: true,
            disableStats: true,
        });

        // Track counts
        let pending = 0, confirmed = 0, total = 0;

        // Update stats
        function updateStats() {
            document.getElementById('pendingCount').textContent = pending;
            document.getElementById('confirmedCount').textContent = confirmed;
            document.getElementById('totalCount').textContent = total;
        }

        // Add order to feed
        function addOrderToFeed(data) {
            const feed = document.getElementById('ordersFeed');

            // Remove placeholder if exists
            if (feed.querySelector('p')) {
                feed.innerHTML = '';
            }

            const orderEl = document.createElement('div');
            orderEl.style.cssText = 'padding: 15px; border-bottom: 1px solid #eee; background: #f9f9f9; animation: slideIn 0.3s ease-in-out;';
            orderEl.innerHTML = `
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: #333; margin-bottom: 5px;">
                                    🎉 New Order #${data.id} from <strong>${data.guest_name}</strong>
                                </div>
                                <div style="font-size: 13px; color: #666; line-height: 1.6;">
                                    <p style="margin: 3px 0;">📍 <strong>${data.villa_name}</strong></p>
                                    <p style="margin: 3px 0;">${data.check_in} → ${data.check_out}</p>
                                    <p style="margin: 3px 0;"><strong style="color: #f05b4f;">Rp ${data.total_price}</strong></p>
                                </div>
                            </div>
                            <div style="text-align: right; margin-left: 15px;">
                                <span style="background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                    ${data.status.toUpperCase()}
                                </span>
                                <p style="margin: 8px 0 0 0; font-size: 11px; color: #999;">${data.created_at}</p>
                            </div>
                        </div>
                    `;

            feed.prepend(orderEl);

            // Play notification sound (optional)
            playNotification();
        }

        // Play notification
        function playNotification() {
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAAB9AAACABAAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj==');
            audio.play().catch(() => { });
        }

        // Listen to admin orders channel
        window.Echo.channel('admin.orders')
            .listen('order.created', (data) => {
                console.log('New order:', data);
                total++;
                pending++;
                addOrderToFeed(data);
                updateStats();
            })
            .listen('order.status.changed', (data) => {
                console.log('Order status changed:', data);
                // Update order in table
                // Adjust counts based on status change
                if (data.old_status === 'pending' && data.new_status === 'confirmed') {
                    pending--;
                    confirmed++;
                    updateStats();
                }
            })
            .error((error) => {
                console.error('Channel error:', error);
                document.getElementById('statusText').textContent = 'Disconnected';
                document.getElementById('statusDot').style.background = '#dc3545';
            });

        // Connection status
        window.Echo.connector.socket.on('connect', () => {
            console.log('Connected to WebSocket');
            document.getElementById('statusText').textContent = 'Connected';
            document.getElementById('statusDot').style.background = '#28a745';
        });

        window.Echo.connector.socket.on('disconnect', () => {
            console.log('Disconnected from WebSocket');
            document.getElementById('statusText').textContent = 'Disconnected';
            document.getElementById('statusDot').style.background = '#dc3545';
        });

        // Initial count
        updateStats();

        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
                    @keyframes slideIn {
                        from {
                            opacity: 0;
                            transform: translateX(-20px);
                        }
                        to {
                            opacity: 1;
                            transform: translateX(0);
                        }
                    }
                `;
        document.head.appendChild(style);
    </script>
@endsection