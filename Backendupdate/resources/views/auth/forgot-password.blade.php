@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8 border border-gray-100 dark:border-gray-700 backdrop-blur-sm">
    <h2 class="text-2xl font-semibold mb-2 text-center">Reset your password</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">
        Enter your panel email address. We will send an 8-digit verification code.
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

    <form method="POST" action="{{ route('password.forgot.send') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors duration-200"
                placeholder="Enter your email address">
        </div>

        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 shadow-lg shadow-primary-500/30">
            Send Verification Code
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
        <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400">Back to sign in</a>
    </p>
</div>
@endsection
