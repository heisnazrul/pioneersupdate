<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\MailConfigResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $smtp = is_array($settings['smtp'] ?? null) ? $settings['smtp'] : [];

        return view('admin.settings.index', compact('settings', 'smtp'));
    }

    public function update(Request $request): RedirectResponse
    {
        $generalKeys = [
            'site_name',
            'contact_email',
            'facebook_url',
            'instagram_url',
        ];

        foreach ($generalKeys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        if ($request->has('smtp')) {
            $smtpData = $request->validate([
                'smtp.host' => ['nullable', 'string', 'max:255'],
                'smtp.port' => ['nullable', 'integer', 'min:1', 'max:65535'],
                'smtp.username' => ['nullable', 'string', 'max:255'],
                'smtp.password' => ['nullable', 'string', 'max:255'],
                'smtp.encryption' => ['nullable', 'in:tls,ssl,none'],
                'smtp.from_address' => ['nullable', 'email', 'max:255'],
                'smtp.from_name' => ['nullable', 'string', 'max:255'],
            ])['smtp'];

            $existing = Setting::get('smtp', []);
            if (!is_array($existing)) {
                $existing = [];
            }

            if (blank($smtpData['password'] ?? null)) {
                $smtpData['password'] = $existing['password'] ?? null;
            }

            Setting::set('smtp', array_merge($existing, $smtpData));
            MailConfigResolver::applyFromSettings();
        }

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('settings', 'public');
            Setting::set('logo', $logoPath);
        }

        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            Setting::set('favicon', $faviconPath);
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    public function testSmtp(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'test_email' => ['required', 'email'],
        ]);

        MailConfigResolver::applyFromSettings();

        try {
            Mail::raw('This is a test email from Pioneers Edu admin SMTP settings.', function ($message) use ($data) {
                $message->to($data['test_email'])
                    ->subject('SMTP Test - Pioneers Edu');
            });
        } catch (\Throwable $e) {
            return back()->withErrors([
                'test_email' => 'Failed to send test email: ' . $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Test email sent to ' . $data['test_email'] . '.');
    }
}
