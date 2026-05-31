@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8 border border-gray-100 dark:border-gray-700 backdrop-blur-sm">
    <h2 class="text-2xl font-semibold mb-2 text-center">Enter verification code</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">
        Check your email for the 8-digit code, then choose a new password.
    </p>

    @if (session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 p-4 rounded-lg mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 p-4 rounded-lg mb-6 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.reset') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
            <input type="email" id="email" value="{{ $email }}" readonly
                class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
        </div>

        <div>
            <label for="otp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">8-Digit Code</label>
            <input type="text" name="otp" id="otp" value="{{ old('otp') }}" required maxlength="8" inputmode="numeric" pattern="[0-9]{8}"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white tracking-widest text-center text-lg"
                placeholder="12345678">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
            <input type="password" name="password" id="password" required minlength="8"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                placeholder="Minimum 8 characters">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                placeholder="Repeat new password">
        </div>

        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 shadow-lg shadow-primary-500/30">
            Reset Password
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
        <a href="{{ route('password.forgot') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400">Request a new code</a>
    </p>
</div>
@endsection
