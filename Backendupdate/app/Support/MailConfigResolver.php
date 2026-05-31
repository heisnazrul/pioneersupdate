<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class MailConfigResolver
{
    public static function applyFromSettings(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        $smtp = Setting::get('smtp');

        if (!is_array($smtp) || empty($smtp['host'])) {
            return;
        }

        $encryption = $smtp['encryption'] ?? null;
        if ($encryption === 'none' || $encryption === '') {
            $encryption = null;
        }

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => $smtp['host'],
            'mail.mailers.smtp.port' => (int) ($smtp['port'] ?? 587),
            'mail.mailers.smtp.username' => $smtp['username'] ?? null,
            'mail.mailers.smtp.password' => $smtp['password'] ?? null,
            'mail.mailers.smtp.encryption' => $encryption,
            'mail.from.address' => $smtp['from_address'] ?? config('mail.from.address'),
            'mail.from.name' => $smtp['from_name'] ?? config('mail.from.name'),
        ]);
    }
}
