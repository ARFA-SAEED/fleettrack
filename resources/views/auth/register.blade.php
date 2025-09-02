@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ url('/register') }}" class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
        @csrf

        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Create an Account</h2>

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-medium mb-1">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your full name" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name')
                <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-medium mb-1">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('email')
                <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-medium mb-1">Password</label>
            <input type="password" id="password" name="password" placeholder="Create a password" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('password')
                <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-1">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                placeholder="Confirm your password" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('password_confirmation')
                <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Register
        </button>

        <!-- Session Error -->
        @if(session('error'))
            <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <p class="mt-4 text-center text-sm text-gray-600">
            Already have an account? <a href="{{ url('/login') }}" class="text-blue-600 hover:underline">Sign in</a>
        </p>
    </form>
@endsection