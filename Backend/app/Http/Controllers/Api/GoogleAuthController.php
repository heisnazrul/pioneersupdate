<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        \Illuminate\Support\Facades\Log::info('Google Auth Config:', [
            'client_id' => config('services.google.client_id'), // Safe to log (public)
            'redirect' => config('services.google.redirect'),
            'has_secret' => !empty(config('services.google.client_secret')),
        ]);
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Determine names
                $name = $googleUser->getName();
                $nameParts = explode(' ', $name, 2);
                $firstName = $nameParts[0] ?? $name;
                $lastName = $nameParts[1] ?? '';

                $user = User::create([
                    'name' => $name,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(16)),
                    'role' => 'uni_student', // Default role
                    'email_verified_at' => now(),
                ]);
            }

            // Create token (Passport)
            $tokenResult = $user->createToken('Google Login');
            $token = $tokenResult->accessToken;

            // Prepare user data for frontend
            $userData = json_encode($user->load('profile'));

            // Return a view that posts the message to parent window
            return view('auth.google-callback', compact('token', 'userData'));

        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed: ' . $e->getMessage()], 500);
        }
    }
}
