<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-screen w-screen px-10 flex items-center justify-center">

    <form method="POST" action="/login"
        class="w-full max-w-[400px] mx-auto bg-red p-8 rounded-lg shadow-sm border border-gray-100">
        @csrf

        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Welcome back</h2>

        <!-- Email Field -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-medium mb-1">Email</label>
            <input type="email" id="email" name="email" placeholder="your@email.com" required value="{{ old("email") }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            @error('email')
                <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="block text-gray-700 text-sm font-medium">Password</label>
                <!-- <a href="/forgot-password" class="text-xs text-blue-600 hover:underline">Forgot password?</a> -->
            </div>
            <input type="password" id="password" name="password" placeholder="••••••••" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            @error('password')
                <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Sign in
        </button>

        <!-- Session Error Message -->
        @if(session('error'))
            <div class="mt-4 p-3 bg-red-50 border border-red-200 text-red-600 text-sm rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <!-- <div class="mt-6 text-center text-sm text-gray-600">
            <p>Don't have an account? <a href="/register" class="text-blue-600 font-medium hover:underline">Sign up</a>
            </p>
        </div> -->
    </form>

</body>

</html>