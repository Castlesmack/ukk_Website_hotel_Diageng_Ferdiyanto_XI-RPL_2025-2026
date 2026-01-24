<!-- Floating Chat Widget -->
<div id="chat-widget" class="fixed bottom-6 right-6 z-50 font-sans">
    <!-- Chat Widget Button -->
    <button id="chat-button" onclick="toggleChat()"
        class="bg-gradient-to-r from-orange-400 to-orange-500 hover:from-orange-500 hover:to-orange-600 text-white rounded-full w-16 h-16 shadow-lg font-bold flex items-center justify-center gap-2 transition hover:shadow-xl hover:scale-110"
        title="Buka Chat">
        <span class="text-2xl">💬</span>
        @auth
            @php
                $unreadCount = \App\Models\Feedback::where('user_id', Auth::id())
                    ->where('status', '!=', 'closed')
                    ->count();
            @endphp
            @if($unreadCount > 0)
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                    {{ $unreadCount }}
                </span>
            @endif
        @endauth
    </button>

    <!-- Chat Window -->
    <div id="chat-window"
        class="hidden absolute bottom-24 right-0 bg-white rounded-xl shadow-2xl w-96 max-h-[600px] min-h-[450px] flex flex-col border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-400 to-orange-500 text-white p-5 rounded-t-xl flex justify-between items-start gap-4 flex-shrink-0">
            <div class="flex-1">
                <h3 class="font-bold text-lg">Chat dengan Kami</h3>
                <p class="text-sm text-orange-100">Kami siap membantu Anda</p>
            </div>
            <button onclick="toggleChat()" class="text-white text-3xl font-light hover:text-orange-100 transition flex-shrink-0 -mt-1 -mr-2">×</button>
        </div>

        <!-- Messages Container -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 bg-gradient-to-b from-gray-50 to-white space-y-4">
            <!-- Welcome Message -->
            <div class="flex gap-3 animate-fadeIn">
                <div class="flex-shrink-0 mt-1">
                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-500 text-white font-bold text-lg shadow-md">
                        A
                    </div>
                </div>
                <div class="flex-1">
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                        <p class="text-sm text-gray-800 leading-relaxed">
                            Halo! 👋 Selamat datang di Ade Villa Kota Bunga.<br class="hidden sm:block">
                            Bagaimana kami bisa membantu Anda hari ini?
                        </p>
                    </div>
                    <span class="text-xs text-gray-400 mt-2 block">Baru saja</span>
                </div>
            </div>

            <!-- Messages will be loaded here -->
            <div id="dynamic-messages"></div>
        </div>

        <!-- Input Area -->
        @auth
            <div class="border-t border-gray-200 p-4 bg-white rounded-b-xl flex-shrink-0 shadow-sm">
                <form id="chat-form" onsubmit="sendMessage(event)" class="flex gap-2 mb-3">
                    <input type="text" id="chat-input" placeholder="Tulis pesan Anda..."
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition text-sm"
                        required>
                    <button type="submit"
                        class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-5 py-3 rounded-lg font-semibold transition shadow-sm hover:shadow-md">
                        Kirim
                    </button>
                </form>
                <p class="text-xs text-gray-500 text-center">
                    📌 Tim kami siap melayani Anda setiap hari 09:00 - 18:00
                </p>
            </div>
        @else
            <div class="border-t border-gray-200 p-4 bg-blue-50 rounded-b-xl flex-shrink-0">
                <p class="text-sm text-gray-700 mb-3">
                    Silakan <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">login</a>
                    terlebih dahulu untuk mengirim pesan.
                </p>
                <a href="{{ route('register') }}"
                    class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition shadow-sm hover:shadow-md">
                    Daftar Akun Baru
                </a>
            </div>
        @endauth
    </div>
</div>

<style>
    #chat-messages::-webkit-scrollbar {
        width: 8px;
    }

    #chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    #chat-messages::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #ff9500, #ff7f00);
        border-radius: 10px;
        transition: background 0.3s;
    }

    #chat-messages::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #e67e22, #d97b1f);
    }

    .message-user {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.5s ease-in;
    }

    /* Message styling improvements */
    .chat-message-sent {
        display: flex;
        justify-content: flex-end;
    }

    .chat-message-received {
        display: flex;
        justify-content: flex-start;
    }

    .chat-bubble-sent {
        background: linear-gradient(135deg, #ff9500 0%, #ff7f00 100%);
        color: white;
        max-width: 75%;
        word-wrap: break-word;
    }

    .chat-bubble-received {
        background: white;
        color: #1f2937;
        max-width: 75%;
        word-wrap: break-word;
        border: 1px solid #e5e7eb;
    }

    @media (max-width: 640px) {
        #chat-window {
            width: calc(100vw - 2rem) !important;
            max-height: calc(100vh - 6rem) !important;
            min-height: 400px !important;
        }

        #chat-button {
            width: 14 !important;
            height: 14 !important;
        }

        .chat-bubble-sent,
        .chat-bubble-received {
            max-width: 85%;
        }
    }

    /* Smooth transitions */
    #chat-window {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    #chat-window.hidden {
        opacity: 0;
        transform: scale(0.95) translateY(20px);
        pointer-events: none;
    }

    #chat-window:not(.hidden) {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
</style>

<script>
    function toggleChat() {
        const chatWindow = document.getElementById('chat-window');
        const chatButton = document.getElementById('chat-button');

        chatWindow.classList.toggle('hidden');

        // Auto-scroll to bottom
        if (!chatWindow.classList.contains('hidden')) {
            setTimeout(() => {
                const messagesContainer = document.getElementById('chat-messages');
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }, 100);
        }
    }

    @auth
        function sendMessage(event) {
            event.preventDefault();

            const input = document.getElementById('chat-input');
            const message = input.value.trim();

            if (!message) return;

            // Show message in UI immediately
            const messagesContainer = document.getElementById('dynamic-messages');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex gap-3 justify-end message-user';
            messageDiv.innerHTML = `
                <div class="flex-1">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-4 rounded-xl shadow-sm ml-auto" style="max-width: 80%; word-wrap: break-word;">
                        <p class="text-sm leading-relaxed">${escapeHtml(message)}</p>
                    </div>
                    <span class="text-xs text-gray-400 mt-2 block text-right">Sekarang</span>
                </div>
            `;
            messagesContainer.appendChild(messageDiv);

            // Auto-scroll
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Send to server
            fetch('{{ route("feedback.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    message: message,
                    channel: 'livechat'
                })
            })
                .then(response => response.json())
                .then(data => {
                    // Clear input
                    input.value = '';

                    // Show response after delay
                    setTimeout(() => {
                        const responseDiv = document.createElement('div');
                        responseDiv.className = 'flex gap-3 message-user';
                        responseDiv.innerHTML = `
                            <div class="flex-shrink-0 mt-1">
                                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-500 text-white font-bold text-lg shadow-md">
                                    A
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100" style="max-width: 80%; word-wrap: break-word;">
                                    <p class="text-sm text-gray-800 leading-relaxed">
                                        Terima kasih atas pesan Anda! 😊<br>
                                        Tim kami akan merespons dalam waktu singkat.
                                    </p>
                                </div>
                                <span class="text-xs text-gray-400 mt-2 block">Sekarang</span>
                            </div>
                        `;
                        messagesContainer.appendChild(responseDiv);
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }, 500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal mengirim pesan. Silakan coba lagi.');
                });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Auto-close chat when clicking outside (optional)
        document.addEventListener('click', function (event) {
            const chatWidget = document.getElementById('chat-widget');
            const chatWindow = document.getElementById('chat-window');

            if (!chatWidget.contains(event.target) && !chatWindow.classList.contains('hidden')) {
                // Optional: uncomment to auto-close
                // toggleChat();
            }
        });
    @endauth
</script>