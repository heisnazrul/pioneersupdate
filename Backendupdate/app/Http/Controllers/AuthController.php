<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if ($redirect = self::redirectForUser(auth()->user())) {
            return redirect()->to($redirect);
        }
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
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

        Auth::login($user, $request->boolean('remember'));
        $user->forceFill(['last_login_at' => now()])->save();

        return redirect()->to(self::redirectForUser($user) ?? route('home'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public static function redirectForUser(?User $user): ?string
    {
        if (!$user) {
            return null;
        }

        $routeMap = [
            'admin'      => 'admin.dashboard',
            'team'       => 'team.dashboard',
            'counsellor' => 'counsellor.dashboard',
        ];

        $routeName = $routeMap[$user->role] ?? null;

        if ($routeName && Route::has($routeName)) {
            return route($routeName);
        }

        Auth::logout();
        request()->session()?->invalidate();
        request()->session()?->regenerateToken();

        return Route::has('login') ? route('login') : url('/');
    }
}
