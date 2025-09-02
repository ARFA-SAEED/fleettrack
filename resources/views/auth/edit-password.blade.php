@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 h-screen">
    <h2 class="text-2xl font-bold mb-4">Update Password</h2>

    @if(session('success'))
        <div class="text-green-600 mb-3">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.password.update') }}">
        @csrf

        <div class="mb-4">
            <label>Current Password</label>
            <input type="password" name="current_password" class="border p-2 w-full" required>
            @error('current_password') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label>New Password</label>
            <input type="password" name="password" class="border p-2 w-full" required>
            @error('password') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="border p-2 w-full" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2">Update Password</button>
    </form>
</div>
@endsection
