@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            {{ $feedback->response ? 'Edit Response' : 'Send Response' }} to Message #{{ $feedback->id }}
        </h1>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-8">
            <!-- Original Message Context -->
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h3 class="font-bold text-gray-800 mb-3">Original Message from {{ $feedback->user->name }}:</h3>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $feedback->message }}</p>
                    <p class="text-sm text-gray-500 mt-3">
                        {{ $feedback->created_at->format('M d, Y \a\t h:i A') }}
                    </p>
                </div>
            </div>

            <!-- Response Form -->
            <form action="{{ route('feedback.update', $feedback->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="response" class="block text-gray-700 font-bold mb-2">
                        Your Response <span class="text-red-500">*</span>
                    </label>
                    <textarea name="response" id="response" rows="8" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('response') border-red-500 @enderror"
                        placeholder="Write your response here...">{{ old('response', $feedback->response) }}</textarea>
                    @error('response')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Minimum 5 characters, Maximum 5000 characters</p>
                </div>

                <div class="mb-6">
                    <label for="status" class="block text-gray-700 font-bold mb-2">
                        Message Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('status') border-red-500 @enderror">
                        <option value="answered" {{ old('status', 'answered') === 'answered' ? 'selected' : '' }}>
                            Answered (Still Open)
                        </option>
                        <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>
                            Closed (Resolved)
                        </option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        {{ $feedback->response ? 'Update Response' : 'Send Response' }}
                    </button>
                    <a href="{{ route('feedback.show', $feedback->id) }}"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection