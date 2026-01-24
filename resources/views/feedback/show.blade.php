@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">Message #{{ $feedback->id }}</h1>
            <a href="{{ route('feedback.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                ← Back to Messages
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-8">
            <!-- Status and Info -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <div class="flex flex-wrap gap-3 mb-3">
                    <span class="inline-block px-3 py-1 text-sm font-bold rounded-full
                        @if ($feedback->status === 'open')
                            bg-red-100 text-red-800
                        @elseif ($feedback->status === 'answered')
                            bg-yellow-100 text-yellow-800
                        @else
                            bg-green-100 text-green-800
                        @endif
                    ">
                        {{ ucfirst($feedback->status) }}
                    </span>
                    <span class="inline-block px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
                        {{ ucfirst($feedback->channel) }}
                    </span>
                </div>
                <p class="text-sm text-gray-500">
                    From: <span class="font-semibold">{{ $feedback->user->name }}</span> •
                    {{ $feedback->created_at->format('M d, Y \a\t h:i A') }}
                </p>
                @if ($feedback->booking)
                    <p class="text-sm text-gray-500 mt-1">
                        Related Booking: <span class="font-semibold">#{{ $feedback->booking->id }}</span>
                    </p>
                @endif
            </div>

            <!-- Original Message -->
            <div class="mb-8">
                <h3 class="font-bold text-gray-800 mb-3">Your Message:</h3>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $feedback->message }}</p>
                </div>
            </div>

            <!-- Response Section -->
            @if ($feedback->response)
                <div class="mb-8">
                    <h3 class="font-bold text-gray-800 mb-3">Response:</h3>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $feedback->response }}</p>
                        @if ($feedback->responder)
                            <p class="text-sm text-gray-500 mt-3">
                                Responded by: <span class="font-semibold">{{ $feedback->responder->name }}</span> •
                                {{ $feedback->updated_at->format('M d, Y \a\t h:i A') }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="mt-8 flex gap-4">
                @if (auth()->user()->role !== 'guest' && auth()->can('update', $feedback))
                    <a href="{{ route('feedback.edit', $feedback->id) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        {{ $feedback->response ? 'Edit Response' : 'Send Response' }}
                    </a>
                @endif

                @if ($feedback->status !== 'closed')
                    <form action="{{ route('feedback.close', $feedback->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg"
                            onclick="return confirm('Close this message?')">
                            Close Message
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if (auth()->user()->role !== 'guest')
            <div class="mt-8 p-6 bg-yellow-50 rounded-lg border border-yellow-200">
                <h3 class="font-bold text-yellow-900 mb-2">👨‍💼 Admin Tools</h3>
                <p class="text-yellow-800 text-sm mb-3">You are viewing this message as staff member.</p>
                <a href="{{ route('feedback.edit', $feedback->id) }}"
                    class="inline-block bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    {{ $feedback->response ? 'Update' : 'Respond to' }} This Message
                </a>
            </div>
        @endif
    </div>
@endsection