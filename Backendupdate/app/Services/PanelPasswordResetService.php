<?php

namespace App\Services;

use App\Mail\PanelPasswordResetOtpMail;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PanelPasswordResetService
{
    public const PANEL_ROLES = ['admin', 'team', 'counsellor'];

    public const PURPOSE = 'panel_password_reset';

    public function sendOtp(string $email): void
    {
        $user = User::query()
            ->where('email', Str::lower(trim($email)))
            ->first();

        if (!$user || !in_array($user->role, self::PANEL_ROLES, true)) {
            throw ValidationException::withMessages([
                'email' => 'No panel account was found for this email address.',
            ]);
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => 'This account is not active. Contact an administrator.',
            ]);
        }

        $code = (string) random_int(10000000, 99999999);

        UserOtp::query()
            ->where('user_id', $user->id)
            ->where('purpose', self::PURPOSE)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        UserOtp::create([
            'user_id' => $user->id,
            'purpose' => self::PURPOSE,
            'code' => $code,
            'channel' => 'email',
            'expires_at' => now()->addMinutes(15),
        ]);

        Mail::to($user->email)->send(new PanelPasswordResetOtpMail($code, $user->name ?: $user->email));
    }

    public function resetPassword(string $email, string $otp, string $password): void
    {
        $user = User::query()
            ->where('email', Str::lower(trim($email)))
            ->first();

        if (!$user || !in_array($user->role, self::PANEL_ROLES, true)) {
            throw ValidationException::withMessages([
                'email' => 'No panel account was found for this email address.',
            ]);
        }

        $record = UserOtp::query()
            ->where('user_id', $user->id)
            ->where('purpose', self::PURPOSE)
            ->whereNull('used_at')
            ->latest('id')
            ->first();

        if (!$record || $record->isExpired()) {
            throw ValidationException::withMessages([
                'otp' => 'This verification code has expired. Request a new one.',
            ]);
        }

        if (!hash_equals($record->code, trim($otp))) {
            throw ValidationException::withMessages([
                'otp' => 'The verification code is incorrect.',
            ]);
        }

        $user->forceFill(['password' => Hash::make($password)])->save();
        $record->markAsUsed();
    }
}
