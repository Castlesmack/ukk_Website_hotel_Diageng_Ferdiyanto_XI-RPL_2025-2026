@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">Messages & Feedback</h1>
            <a href="{{ route('feedback.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + New Message
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($feedbacks->count() > 0)
            <div class="space-y-4">
                @foreach ($feedbacks as $feedback)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $feedback->user->name }}
                                    </span>
                                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full
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
                                    <span class="inline-block px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                        {{ ucfirst($feedback->channel) }}
                                    </span>
                                </div>
                                @if ($feedback->booking)
                                    <p class="text-sm text-gray-500 mt-1">
                                        Booking #{{ $feedback->booking->id }}
                                    </p>
                                @endif
                            </div>
                            <time class="text-sm text-gray-500">
                                {{ $feedback->created_at->diffForHumans() }}
                            </time>
                        </div>

                        <p class="text-gray-700 mb-4 line-clamp-2">{{ $feedback->message }}</p>

                        @if ($feedback->response)
                            <div class="bg-green-50 border-l-4 border-green-500 p-3 mb-4">
                                <p class="text-sm font-semibold text-gray-700 mb-1">Response:</p>
                                <p class="text-gray-600 text-sm">{{ Str::limit($feedback->response, 200) }}</p>
                            </div>
                        @endif

                        <a href="{{ route('feedback.show', $feedback->id) }}"
                            class="inline-block mt-3 text-blue-600 hover:text-blue-800 font-semibold">
                            View Details →
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $feedbacks->links() }}
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <p class="text-gray-500 mb-4">No messages or feedback yet.</p>
                <a href="{{ route('feedback.create') }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                    Send Your First Message
                </a>
            </div>
        @endif
    </div>
@endsection