<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocket Test - UKK Villa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            padding: 30px;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .status-box {
            background: #f5f5f5;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .status-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            align-items: center;
        }

        .status-item:last-child {
            margin-bottom: 0;
        }

        .status-label {
            font-weight: 600;
            color: #333;
        }

        .status-value {
            color: #666;
            font-size: 14px;
        }

        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-left: 8px;
        }

        .status-indicator.connected {
            background: #4caf50;
        }

        .status-indicator.disconnected {
            background: #f44336;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 8px;
        }

        .channel-list {
            list-style: none;
        }

        .channel-item {
            background: #f9f9f9;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 5px;
            border-left: 3px solid #667eea;
            font-size: 14px;
        }

        .channel-item.connected {
            border-left-color: #4caf50;
        }

        .test-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        button {
            flex: 1;
            min-width: 150px;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #764ba2;
            color: white;
        }

        .btn-secondary:hover {
            background: #6a3d96;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4);
        }

        .btn-danger {
            background: #f44336;
            color: white;
        }

        .btn-danger:hover {
            background: #da190b;
        }

        .events-log {
            background: #1e1e1e;
            color: #0f0;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            height: 250px;
            overflow-y: auto;
            border: 1px solid #333;
        }

        .log-entry {
            margin-bottom: 8px;
            padding: 5px;
            border-left: 2px solid #0f0;
            padding-left: 10px;
        }

        .log-time {
            color: #888;
        }

        .log-event {
            color: #0f0;
        }

        .log-data {
            color: #0ff;
            font-size: 11px;
            margin-top: 3px;
        }

        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 12px;
            border-radius: 5px;
            font-size: 13px;
            color: #1565c0;
            margin-bottom: 20px;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 24px;
            }

            .test-buttons {
                flex-direction: column;
            }

            button {
                min-width: unset;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🚀 WebSocket Test Dashboard</h1>
        <p class="subtitle">Real-time Communication with Laravel Reverb</p>

        <div class="info-box">
            ℹ️ This page demonstrates real-time WebSocket functionality using Laravel Reverb.
        </div>

        <!-- Connection Status -->
        <div class="section">
            <div class="section-title">Connection Status</div>
            <div class="status-box">
                <div class="status-item">
                    <span class="status-label">Connection:</span>
                    <span class="status-value">
                        <span id="connectionStatus">Connecting...</span>
                        <span class="status-indicator" id="connectionIndicator"></span>
                    </span>
                </div>
                <div class="status-item">
                    <span class="status-label">Messages Received:</span>
                    <span class="status-value" id="messageCount">0</span>
                </div>
                <div class="status-item">
                    <span class="status-label">Last Update:</span>
                    <span class="status-value" id="lastUpdate">-</span>
                </div>
            </div>
        </div>

        <!-- Subscribed Channels -->
        <div class="section">
            <div class="section-title">Subscribed Channels</div>
            <ul class="channel-list" id="channelList">
                <li class="channel-item">bookings</li>
                <li class="channel-item">villa-availability</li>
            </ul>
        </div>

        <!-- Test Actions -->
        <div class="section">
            <div class="section-title">Test Actions</div>
            <div class="test-buttons">
                <button class="btn-primary" onclick="testBookingEvent()">📝 Test Booking Event</button>
                <button class="btn-secondary" onclick="testVillaEvent()">🏠 Test Villa Event</button>
                <button class="btn-danger" onclick="clearLog()">🗑️ Clear Log</button>
            </div>
        </div>

        <!-- Events Log -->
        <div class="section">
            <div class="section-title">Events Log</div>
            <div class="events-log" id="eventLog"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.1/dist/echo.iife.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.1.0/dist/web/pusher.min.js"></script>

    <script>
        let messageCount = 0;

        // Initialize Echo (WebSocket client)
        const echo = new Echo({
            broadcaster: 'reverb',
            key: "{{ env('REVERB_APP_KEY') }}",
            wsHost: "{{ env('REVERB_HOST', 'localhost') }}",
            wsPort: "{{ env('REVERB_PORT', 8080) }}",
            wssPort: "{{ env('REVERB_PORT', 8080) }}",
            forceTLS: false,
            enabledTransports: ['ws', 'wss'],
            auth: {
                headers: {
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            }
        });

        // Handle connection
        echo.connector.socket.on('connect', () => {
            updateConnectionStatus(true);
            addLog('✅ Connected to WebSocket server', 'success');
        });

        echo.connector.socket.on('disconnect', () => {
            updateConnectionStatus(false);
            addLog('❌ Disconnected from WebSocket server', 'error');
        });

        // Subscribe to channels
        echo.channel('bookings')
            .listen('booking.updated', (data) => {
                addLog('📝 Booking Updated', data);
                messageCount++;
                updateMessageCount();
            });

        echo.channel('villa-availability')
            .listen('villa.availability_changed', (data) => {
                addLog('🏠 Villa Availability Changed', data);
                messageCount++;
                updateMessageCount();
            });

        // Helper functions
        function updateConnectionStatus(connected) {
            const status = document.getElementById('connectionStatus');
            const indicator = document.getElementById('connectionIndicator');

            if (connected) {
                status.textContent = 'Connected';
                indicator.className = 'status-indicator connected';
            } else {
                status.textContent = 'Disconnected';
                indicator.className = 'status-indicator disconnected';
            }
        }

        function updateMessageCount() {
            document.getElementById('messageCount').textContent = messageCount;
            document.getElementById('lastUpdate').textContent = new Date().toLocaleTimeString();
        }

        function addLog(title, data = {}) {
            const eventLog = document.getElementById('eventLog');
            const timestamp = new Date().toLocaleTimeString();

            const entry = document.createElement('div');
            entry.className = 'log-entry';
            entry.innerHTML = `
                <span class="log-time">[${timestamp}]</span>
                <span class="log-event">${title}</span>
                <div class="log-data">${JSON.stringify(data, null, 2)}</div>
            `;

            eventLog.insertBefore(entry, eventLog.firstChild);

            // Keep only last 50 entries
            while (eventLog.children.length > 50) {
                eventLog.removeChild(eventLog.lastChild);
            }
        }

        function clearLog() {
            document.getElementById('eventLog').innerHTML = '';
            messageCount = 0;
            updateMessageCount();
            addLog('🗑️ Log cleared');
        }

        function testBookingEvent() {
            addLog('🧪 Testing Booking Event', {
                booking_id: Math.floor(Math.random() * 100),
                status: 'confirmed',
                message: 'Booking test event fired'
            });
        }

        function testVillaEvent() {
            addLog('🧪 Testing Villa Event', {
                villa_id: Math.floor(Math.random() * 10),
                villa_name: 'Sample Villa',
                is_available: Math.random() > 0.5
            });
        }

        // Initial setup
        addLog('🚀 WebSocket dashboard initialized');
    </script>
</body>

</html>