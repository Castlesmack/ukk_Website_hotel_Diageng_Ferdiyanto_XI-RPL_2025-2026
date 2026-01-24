<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.0/dist/echo.iife.js"></script>
<script>
    // Initialize Laravel Echo for WebSocket
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Echo === 'undefined') {
            console.log('Laravel Echo not loaded');
            return;
        }

        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ env("REVERB_APP_KEY") }}',
            wsHost: '{{ env("REVERB_HOST") }}',
            wsPort: {{ env("REVERB_PORT") }},
            wssPort: {{ env("REVERB_PORT") }},
            forceTLS: ({{ env("REVERB_SCHEME") }} === 'https'),
            encrypted: true,
            disableStats: true,
            enabledTransports: ['ws', 'wss']
        });

        console.log('WebSocket connected to Reverb');

        // Listen to admin orders channel for new orders
        window.Echo.channel('admin.orders')
            .listen('order.created', (data) => {
                console.log('New order received in real-time:', data);
                showNotification('New Order', `Order from ${data.guest_name} for ${data.villa_name}`);

                // Update reservations table in real-time
                fetchAndUpdateReservations();

                // Update orders feed in real-time dashboard
                fetchAndUpdateOrdersFeed();
            })
            .listen('order.status.changed', (data) => {
                console.log('Order status changed in real-time:', data);
                showNotification('Order Status Updated', `Order is now ${data.new_status}`);

                // Refresh tables when status changes
                fetchAndUpdateReservations();
                fetchAndUpdateOrdersFeed();
            })
            .error((error) => {
                console.error('Channel subscription error:', error);
            });

        console.log('Listening to admin.orders channel for real-time updates');
    });

    // Fetch and update reservations table
    function fetchAndUpdateReservations() {
        console.log('Fetching latest reservations...');
        fetch('/api/reservations/latest')
            .then(response => response.json())
            .then(data => {
                console.log('Received updated reservations:', data);
                const tableBody = document.querySelector('table tbody');
                if (tableBody) {
                    // Add new orders at the top
                    data.reservations.forEach(reservation => {
                        const existingRow = document.querySelector(`tr[data-booking-id="${reservation.id}"]`);
                        if (!existingRow) {
                            const row = createReservationRow(reservation);
                            tableBody.insertBefore(row, tableBody.firstChild);
                        }
                    });
                }
            })
            .catch(error => console.error('Error fetching reservations:', error));
    }

    // Fetch and update orders feed
    function fetchAndUpdateOrdersFeed() {
        console.log('Fetching latest orders feed...');
        fetch('/api/orders/latest')
            .then(response => response.json())
            .then(data => {
                console.log('Received updated orders feed:', data);
                const ordersContainer = document.querySelector('.orders-feed');
                if (ordersContainer) {
                    // Update with new orders (implementation depends on your dashboard structure)
                    updateOrdersDisplay(data.orders);
                }
            })
            .catch(error => console.error('Error fetching orders feed:', error));
    }

    // Create a reservation table row
    function createReservationRow(reservation) {
        const row = document.createElement('tr');
        row.setAttribute('data-booking-id', reservation.id);
        row.innerHTML = `
            <td>${reservation.booking_code}</td>
            <td>${reservation.guest_name}</td>
            <td>${reservation.villa_name}</td>
            <td>${reservation.check_in_date}</td>
            <td>${reservation.check_out_date}</td>
            <td><span class="badge badge-${reservation.status}">${reservation.status}</span></td>
            <td>Rp ${new Intl.NumberFormat('id-ID').format(reservation.total_price)}</td>
        `;
        return row;
    }

    // Update orders display
    function updateOrdersDisplay(orders) {
        const feedContainer = document.querySelector('.orders-feed');
        if (feedContainer && orders.length > 0) {
            const firstOrder = orders[0];
            const orderHtml = `
                <div class="order-item" style="padding: 15px; border-bottom: 1px solid #eee; animation: slideIn 0.3s ease;">
                    <p><strong>${firstOrder.guest_name}</strong> booked <strong>${firstOrder.villa_name}</strong></p>
                    <p style="color: #666; font-size: 13px;">${firstOrder.check_in} → ${firstOrder.check_out}</p>
                    <p style="color: #f05b4f; font-weight: bold;">Rp ${firstOrder.total_price}</p>
                </div>
            `;
            // Insert new order at the top
            feedContainer.insertAdjacentHTML('afterbegin', orderHtml);
        }
    }

    // Notification helper
    function showNotification(title, message) {
        if (Notification.permission === 'granted') {
            new Notification(title, { body: message, icon: '/favicon.ico' });
        }
    }

    // Request notification permission on page load
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }

    // CSS animation for sliding in new orders
    if (!document.querySelector('style[data-websocket-animation]')) {
        const style = document.createElement('style');
        style.setAttribute('data-websocket-animation', 'true');
        style.textContent = `
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    }

    // Auto-refresh page every 5 seconds for admin and reception pages
    const isAdminOrReception = window.location.pathname.includes('/admin') ||
        window.location.pathname.includes('/reception');
    if (isAdminOrReception) {
        console.log('Auto-refresh enabled: Page will refresh every 5 seconds');
        setInterval(function () {
            console.log('Auto-refreshing page...');
            location.reload();
        }, 5000); // 5000 ms = 5 seconds
    }
</script>