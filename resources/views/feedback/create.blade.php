@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Send Message or Feedback</h1>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('feedback.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-8">
            @csrf

            @if ($bookings && $bookings->count() > 0 && auth()->user()->role === 'guest')
                <div class="mb-6">
                    <label for="booking_id" class="block text-gray-700 font-bold mb-2">
                        Related Booking (Optional)
                    </label>
                    <select name="booking_id" id="booking_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">-- Select a booking --</option>
                        @foreach ($bookings as $booking)
                            <option value="{{ $booking->id }}">
                                Booking #{{ $booking->id }} - {{ $booking->villa_room_type->villa->name ?? 'Villa' }}
                                ({{ $booking->check_in_date->format('M d, Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="mb-6">
                <label for="channel" class="block text-gray-700 font-bold mb-2">
                    Channel <span class="text-red-500">*</span>
                </label>
                <select name="channel" id="channel" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('channel') border-red-500 @enderror">
                    <option value="">-- Select communication method --</option>
                    <option value="web" {{ old('channel') === 'web' ? 'selected' : '' }}>Website Form</option>
                    <option value="email" {{ old('channel') === 'email' ? 'selected' : '' }}>Email</option>
                    <option value="livechat" {{ old('channel') === 'livechat' ? 'selected' : '' }}>Live Chat</option>
                </select>
                @error('channel')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="message" class="block text-gray-700 font-bold mb-2">
                    Your Message <span class="text-red-500">*</span>
                </label>
                <textarea name="message" id="message" rows="6" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('message') border-red-500 @enderror"
                    placeholder="Please describe your feedback, question, or concern...">{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-1">Minimum 10 characters, Maximum 5000 characters</p>
            </div>

            <div class="flex gap-4">
                <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    Send Message
                </button>
                <a href="{{ route('feedback.index') }}"
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition text-center">
                    Cancel
                </a>
            </div>
        </form>

        <div class="mt-8 p-6 bg-blue-50 rounded-lg border border-blue-200">
            <h3 class="font-bold text-blue-900 mb-2">📧 Response Time</h3>
            <p class="text-blue-800 text-sm">Our team typically responds to messages within 24 hours during business hours.
            </p>
        </div>
    </div>
@endsection