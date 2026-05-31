<?php

namespace App\Http\Controllers;

use App\Services\PanelPasswordResetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function __construct(
        private readonly PanelPasswordResetService $passwordResetService,
    ) {}

    public function showForgotForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $this->passwordResetService->sendOtp($data['email']);

        $request->session()->put('password_reset_email', strtolower(trim($data['email'])));

        return redirect()
            ->route('password.reset.form')
            ->with('success', 'An 8-digit verification code was sent to your email address.');
    }

    public function showResetForm(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('password_reset_email')) {
            return redirect()
                ->route('password.forgot')
                ->withErrors(['email' => 'Enter your email first to receive a verification code.']);
        }

        return view('auth.reset-password', [
            'email' => $request->session()->get('password_reset_email'),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $email = $request->session()->get('password_reset_email');

        if (!$email) {
            return redirect()
                ->route('password.forgot')
                ->withErrors(['email' => 'Your reset session expired. Request a new code.']);
        }

        $data = $request->validate([
            'otp' => ['required', 'digits:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $this->passwordResetService->resetPassword($email, $data['otp'], $data['password']);

        $request->session()->forget('password_reset_email');

        return redirect()
            ->route('login')
            ->with('success', 'Your password was reset successfully. You can sign in now.');
    }
}
