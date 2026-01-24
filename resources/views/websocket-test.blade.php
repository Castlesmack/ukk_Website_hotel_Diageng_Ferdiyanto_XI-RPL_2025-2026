@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">WebSocket Test Dashboard</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Connection Status</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span>WebSocket Server:</span>
                            <span id="serverStatus"
                                class="px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">Disconnected</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Connected Users:</span>
                            <span id="userCount" class="text-2xl font-bold text-blue-600">0</span>
                        </div>
                    </div>
                </div>

                <!-- Channel Test -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Test Channel</h2>
                    <form id="testForm" class="space-y-3">
                        <input type="text" id="channelName" placeholder="Channel name (e.g., notifications)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="notifications">
                        <textarea id="messageContent" placeholder="Message content"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            rows="3">Test websocket message</textarea>
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition">
                            Broadcast Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Messages Log -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-semibold mb-4">Message Log</h2>
                <div id="messageLog"
                    class="bg-gray-900 text-gray-100 rounded p-4 h-64 overflow-y-auto font-mono text-sm space-y-2">
                    <div class="text-gray-500">Waiting for messages...</div>
                </div>
                <button onclick="clearLog()" class="mt-4 px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md transition">
                    Clear Log
                </button>
            </div>

            <!-- Configuration Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
                <h2 class="text-lg font-semibold mb-3 text-blue-900">WebSocket Configuration</h2>
                <ul class="text-sm text-blue-800 space-y-2">
                    <li><strong>Host:</strong> {{ env('REVERB_HOST', 'localhost') }}</li>
                    <li><strong>Port:</strong> {{ env('REVERB_PORT', 8080) }}</li>
                    <li><strong>App Key:</strong> {{ env('REVERB_APP_KEY') }}</li>
                    <li><strong>Broadcast Driver:</strong> {{ config('broadcasting.default') }}</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.0/dist/echo.iife.js"></script>
    <script>
        // Initialize Laravel Echo with Reverb
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

        // Log function
        function addLog(message) {
            const logDiv = document.getElementById('messageLog');
            const timestamp = new Date().toLocaleTimeString();
            const logEntry = document.createElement('div');
            logEntry.textContent = `[${timestamp}] ${message}`;
            logDiv.appendChild(logEntry);
            logDiv.scrollTop = logDiv.scrollHeight;
        }

        function clearLog() {
            document.getElementById('messageLog').innerHTML = '';
            addLog('Log cleared');
        }

        // Listen for notifications
        window.Echo.channel('notifications')
            .listen('NotificationEvent', (data) => {
                console.log('Received notification:', data);
                addLog(`📨 ${JSON.stringify(data)}`);
            })
            .error((error) => {
                console.error('Channel error:', error);
                addLog(`❌ Channel error: ${error.message}`);
            });

        // Update connection status
        window.Echo.connector.socket.on('connect', () => {
            console.log('WebSocket connected');
            document.getElementById('serverStatus').textContent = 'Connected';
            document.getElementById('serverStatus').className = 'px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800';
            addLog('✅ Connected to WebSocket server');
        });

        window.Echo.connector.socket.on('disconnect', () => {
            console.log('WebSocket disconnected');
            document.getElementById('serverStatus').textContent = 'Disconnected';
            document.getElementById('serverStatus').className = 'px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800';
            addLog('❌ Disconnected from WebSocket server');
        });

        window.Echo.connector.socket.on('connect_error', (error) => {
            console.error('Connection error:', error);
            addLog(`⚠️ Connection error: ${error.message || error}`);
        });

        // Handle test form submission
        document.getElementById('testForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const channel = document.getElementById('channelName').value;
            const message = document.getElementById('messageContent').value;

            // Send to server for broadcasting
            fetch('/api/broadcast-test', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    channel: channel,
                    message: message,
                }),
            })
                .then(response => response.json())
                .then(data => {
                    addLog(`📤 Broadcast sent to "${channel}": ${message}`);
                })
                .catch(error => {
                    addLog(`❌ Broadcast failed: ${error.message}`);
                });
        });

        // Initial log
        addLog('📡 WebSocket Test Dashboard Loaded');
        addLog('🔄 Attempting to connect...');
    </script>
@endsection