<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome | Director of Studies Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css') {{-- if using Laravel Breeze with Vite/Tailwind --}}
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center">

    <div class="w-full max-w-md bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold text-center mb-4">Welcome to the School Portal</h1>
        <p class="text-center text-sm text-gray-600 mb-6">Director of Studies Login</p>

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input id="email" type="email" name="email" required autofocus
                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required
                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
            </div>

            <div class="flex items-center justify-between mb-4">
                <label class="flex items-center text-sm">
                    <input type="checkbox" name="remember" class="mr-2">
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">
                    Forgot Password?
                </a>
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-black py-2 px-4 rounded hover:bg-indigo-700 transition">
                Log In
            </button>
        </form>
    </div>

    <p class="mt-6 text-sm text-gray-500">&copy; {{ date('Y') }} King SS. All rights reserved.</p>

</body>
</html>
