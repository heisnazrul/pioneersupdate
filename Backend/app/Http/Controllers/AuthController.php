<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite; // Added
use App\Models\Setting; // Added
use Illuminate\Support\Str; // Added

use App\Support\SystemSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm(): View|\Illuminate\Http\RedirectResponse
    {
        if ($redirect = self::redirectForUser(auth()->user())) {
            return redirect()->to($redirect);
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::validate($credentials)) {
            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->onlyInput('email');
        }

        $user = User::where('email', $credentials['email'])->firstOrFail();

        if ($user->status === 'banned') {
            return back()->withErrors([
                'email' => 'This account has been banned. Contact support for assistance.',
            ])->onlyInput('email');
        }

        if ($user->status !== 'active') {
            return back()->withErrors([
                'email' => $user->role === 'agent'
                    ? 'Your agent account is waiting for approval. We will notify you when it is active.'
                    : 'Your account is inactive. Please contact an administrator.',
            ])->onlyInput('email');
        }

        // Simpler login for now, skipping extensive context/OTP checks for MVP
        Auth::login($user, $request->boolean('remember'));
        $user->forceFill(['last_login_at' => now()])->save();

        $destination = self::redirectForUser($user) ?? route('home');

        return redirect()->to($destination);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function redirectToGoogle()
    {
        $google = Setting::get('google', []);

        $config = [
            'client_id' => $google['client_id'] ?? config('services.google.client_id'),
            'client_secret' => $google['client_secret'] ?? config('services.google.client_secret'),
            'redirect' => $google['redirect_uri'] ?? config('services.google.redirect'),
        ];

        // Dynamically set config for this request
        config(['services.google' => $config]);

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $google = Setting::get('google', []);

        $config = [
            'client_id' => $google['client_id'] ?? config('services.google.client_id'),
            'client_secret' => $google['client_secret'] ?? config('services.google.client_secret'),
            'redirect' => $google['redirect_uri'] ?? config('services.google.redirect'),
        ];

        config(['services.google' => $config]);

        try {
            $socialUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Google login failed. Please try again.']);
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        // If user doesn't exist, you might want to create them or reject
        if (!$user) {
            // For now, let's auto-register basic students:
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'role' => 'student', // Default role for social login
                'status' => 'active',
                'google_id' => $socialUser->getId(),
            ]);
        } else {
            // Update google_id if missed
            if (empty($user->google_id)) {
                $user->update(['google_id' => $socialUser->getId()]);
            }
        }

        Auth::login($user);

        return redirect()->to(self::redirectForUser($user) ?? route('home'));
    }

    public static function redirectForUser(?User $user): ?string
    {
        if (!$user) {
            return null;
        }

        $routeMap = [
            'admin' => 'admin.dashboard',
            'agent' => 'agent.dashboard',
            'uni_agent' => 'agent.dashboard',
            'lg_agent' => 'agent.dashboard',
            'team' => 'team.dashboard',
            'counselor' => 'counselor.dashboard',
            'counsellor' => 'counselor.dashboard',
            'school' => 'school.dashboard',
            'student' => 'student.dashboard',
            'lg_student' => 'student.dashboard',
            'uni_student' => 'student.dashboard',
        ];

        $routeName = $routeMap[$user->role] ?? null;

        if ($routeName && Route::has($routeName)) {
            return route($routeName);
        }

        return Route::has('home') ? route('home') : url('/');
    }
}
