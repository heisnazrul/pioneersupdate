<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SystemSettings
{
    public const KEY_EMAIL_VERIFICATION_ROLES = 'auth.email_verification.roles';
    public const KEY_SMTP = 'mail.smtp';
    public const KEY_BRANDING = 'app.branding';
    public const KEY_API_CLIENTS = 'api.allowed_clients';
    public const KEY_API_ALLOW_HTTP = 'api.allow_http';
    public const KEY_TWILIO = 'twilio.whatsapp';
    public const KEY_GOOGLE = 'auth.google';
    public const KEY_CONTACT = 'site.contact';

    public static function emailVerificationRoles(): array
    {
        $defaults = config('system.email_verification_roles', []);
        $stored = Setting::get(self::KEY_EMAIL_VERIFICATION_ROLES);

        return array_values(array_unique($stored ?? $defaults));
    }

    public static function setEmailVerificationRoles(array $roles): void
    {
        $uniqueRoles = array_values(array_unique($roles));

        Setting::put(self::KEY_EMAIL_VERIFICATION_ROLES, $uniqueRoles);
    }

    public static function requiresEmailVerification(string $role): bool
    {
        $roles = self::emailVerificationRoles();

        return in_array($role, $roles, true);
    }

    public static function smtp(): array
    {
        $defaults = config('system.smtp', []);
        $stored = Setting::get(self::KEY_SMTP, []);

        return array_merge($defaults, $stored ?? []);
    }

    public static function setSmtp(array $config): void
    {
        Setting::put(self::KEY_SMTP, $config);
    }

    public static function branding(): array
    {
        $defaults = config('system.branding', []);
        $stored = Setting::get(self::KEY_BRANDING, []);
        $branding = array_merge($defaults, $stored ?? []);

        $disk = config('system.logo_disk', 'public');
        $branding['logo_url'] = self::resolveStorageUrl($branding['logo'] ?? null, $disk);
        $branding['favicon_url'] = self::resolveStorageUrl($branding['favicon'] ?? null, $disk);

        return $branding;
    }

    public static function setBranding(array $branding): void
    {
        Setting::put(self::KEY_BRANDING, $branding);
    }

    public static function apiClients(): array
    {
        return Setting::get(self::KEY_API_CLIENTS, []);
    }

    public static function setApiClients(array $clients): void
    {
        $clients = array_values(array_filter(array_map('trim', $clients)));
        Setting::put(self::KEY_API_CLIENTS, $clients);
    }

    public static function apiAllowHttp(): bool
    {
        return (bool) Setting::get(self::KEY_API_ALLOW_HTTP, false);
    }

    public static function setApiAllowHttp(bool $allowed): void
    {
        Setting::put(self::KEY_API_ALLOW_HTTP, $allowed);
    }

    public static function twilio(): array
    {
        $defaults = config('system.twilio', []);
        $stored = Setting::get(self::KEY_TWILIO, []);

        return array_merge($defaults, $stored ?? []);
    }

    public static function setTwilio(array $config): void
    {
        Setting::put(self::KEY_TWILIO, $config);
    }

    public static function google(): array
    {
        $defaults = config('system.google', []);
        $stored = Setting::get(self::KEY_GOOGLE, []);

        return array_merge($defaults, $stored ?? []);
    }

    public static function setGoogle(array $config): void
    {
        Setting::put(self::KEY_GOOGLE, $config);
    }

    /**
     * Resolve a storage path into a publicly accessible URL.
     */
    private static function resolveStorageUrl(?string $path, string $disk): ?string
    {
        if (!$path) {
            return null;
        }

        if ($disk !== 'public') {
            try {
                $url = Storage::disk($disk)->url($path);

                if ($url && str_contains($url, '://')) {
                    return $url;
                }
            } catch (\Throwable $e) {
                // fall back to asset path below
            }
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    public static function contactInfo(): array
    {
        $defaults = [
            'site_name' => 'Pioneers Admissions',
            'site_email' => 'info@pioneers.edu.sa',
            'site_phone' => '+966 50 123 4567',
            'site_address' => 'Riyadh, Saudi Arabia',
            'contact_description' => '',
            'social_links' => [
                ['platform' => 'Facebook', 'url' => '#'],
                ['platform' => 'Twitter', 'url' => '#'],
                ['platform' => 'Instagram', 'url' => '#'],
                ['platform' => 'LinkedIn', 'url' => '#'],
            ],
        ];
        $stored = Setting::get(self::KEY_CONTACT, []);

        return array_merge($defaults, $stored ?? []);
    }

    public static function setContactInfo(array $info): void
    {
        Setting::put(self::KEY_CONTACT, $info);
    }
}
