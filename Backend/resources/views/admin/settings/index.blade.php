@extends('admin.layouts.layout')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">System Settings</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Contact & General Settings -->
            <!-- Contact & General Settings Wrapped in Form (using contents to preserve grid layout) -->
            <form action="{{ route('admin.settings.contact') }}" method="POST" class="contents">
                @csrf

                <!-- Card 1: General Information -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">General Information</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Basic contact details.</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Site Email</label>
                            <input type="email" name="site_email" value="{{ $contact['site_email'] ?? '' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Site Phone</label>
                            <input type="text" name="site_phone" value="{{ $contact['site_phone'] ?? '' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                            <input type="text" name="site_address" value="{{ $contact['site_address'] ?? '' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">App
                                Description</label>
                            <textarea name="contact_description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">{{ $contact['contact_description'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Social Links & Save -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Social Media</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Social platform links.</p>
                        </div>
                        <div class="p-6 space-y-4">
                            @php
                                $defaults = [
                                    ['platform' => 'Facebook', 'url' => '#'],
                                    ['platform' => 'Twitter', 'url' => '#'],
                                    ['platform' => 'Instagram', 'url' => '#'],
                                    ['platform' => 'LinkedIn', 'url' => '#'],
                                    ['platform' => 'TikTok', 'url' => '#'],
                                    ['platform' => 'YouTube', 'url' => '#'],
                                ];
                                $existing = collect($contact['social_links'] ?? [])->keyBy('platform');
                                $socials = collect($defaults)->map(function ($default) use ($existing) {
                                    return [
                                        'platform' => $default['platform'],
                                        'url' => $existing->has($default['platform']) ? $existing[$default['platform']]['url'] : $default['url']
                                    ];
                                });
                            @endphp
                            @foreach($socials as $index => $social)
                                <div class="flex items-center gap-4">
                                    <div class="w-1/3">
                                        <input type="text" name="social_links[{{ $index }}][platform]"
                                            value="{{ $social['platform'] }}"
                                            class="bg-gray-50 block w-full rounded-md border-gray-300 px-3 py-2 text-sm font-medium text-gray-700"
                                            readonly>
                                    </div>
                                    <div class="w-2/3">
                                        <input type="text" name="social_links[{{ $index }}][url]" value="{{ $social['url'] }}"
                                            placeholder="https://..."
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition duration-150 ease-in-out transform hover:scale-[1.02]">
                            Save Contact Information
                        </button>
                    </div>
                </div>
            </form>

            <!-- Branding Settings -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Branding</h3>
                    </div>
                    <form action="{{ route('admin.settings.branding') }}" method="POST" enctype="multipart/form-data"
                        class="contents">
                        <div class="p-6 space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">App
                                        Name</label>
                                    <input type="text" name="app_name"
                                        value="{{ $branding['app_name'] ?? config('app.name') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Primary
                                        Color</label>
                                    <input type="color" name="primary_color"
                                        value="{{ $branding['primary_color'] ?? '#000000' }}"
                                        class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo</label>
                                    <input type="file" name="logo"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @if(!empty($branding['logo_url']))
                                        <img src="{{ $branding['logo_url'] }}" alt="Logo" class="mt-2 h-10 w-auto">
                                    @endif
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Favicon</label>
                                    <input type="file" name="favicon"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @if(!empty($branding['favicon_url']))
                                        <img src="{{ $branding['favicon_url'] }}" alt="Favicon" class="mt-2 h-8 w-8">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition duration-150 ease-in-out transform hover:scale-[1.02]">
                                Save Branding
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- User Role Verification Settings -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">User Role Verification</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Require OTP verification for specific
                            roles.</p>
                    </div>
                    <form action="{{ route('admin.settings.email') }}" method="POST" class="contents">
                        @csrf
                        <div class="p-6 space-y-4">
                            <div class="space-y-2">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach($roles as $role)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="roles[]" value="{{ $role }}"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                {{ in_array($role, $emailVerificationRoles) ? 'checked' : '' }}>
                                            <span
                                                class="ml-2 text-sm text-gray-600 dark:text-gray-400 capitalize">{{ $role }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition duration-150 ease-in-out transform hover:scale-[1.02]">
                                Save Verification Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SMTP Settings -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">SMTP Configuration</h3>
                    </div>
                    <form action="{{ route('admin.settings.smtp') }}" method="POST" class="contents">
                        @csrf
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Host</label>
                                    <input type="text" name="host" value="{{ $smtp['host'] ?? '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Port</label>
                                    <input type="number" name="port" value="{{ $smtp['port'] ?? '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Encryption</label>
                                    <select name="encryption"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                                        <option value="" {{ empty($smtp['encryption']) ? 'selected' : '' }}>None</option>
                                        <option value="tls" {{ ($smtp['encryption'] ?? '') == 'tls' ? 'selected' : '' }}>TLS
                                        </option>
                                        <option value="ssl" {{ ($smtp['encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                                    <input type="text" name="username" value="{{ $smtp['username'] ?? '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                                    <input type="password" name="password"
                                        placeholder="{{ !empty($smtp['password']) ? 'Current Password Set' : '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">From
                                        Address</label>
                                    <input type="email" name="from_address" value="{{ $smtp['from_address'] ?? '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">From
                                        Name</label>
                                    <input type="text" name="from_name" value="{{ $smtp['from_name'] ?? '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition duration-150 ease-in-out transform hover:scale-[1.02]">
                                Save SMTP
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Google Settings -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Google Authentication</h3>
                    </div>
                    <form action="{{ route('admin.settings.google') }}" method="POST" class="contents">
                        @csrf
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client
                                        ID</label>
                                    <input type="text" name="client_id" value="{{ $google['client_id'] ?? '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client
                                        Secret</label>
                                    <input type="password" name="client_secret" value="{{ $google['client_secret'] ?? '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Redirect
                                        URI</label>
                                    <input type="text" name="redirect_uri" value="{{ $google['redirect_uri'] ?? '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition duration-150 ease-in-out transform hover:scale-[1.02]">
                                Save Google Auth
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Twilio Settings -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Twilio (WhatsApp)</h3>
                    </div>
                    <form action="{{ route('admin.settings.twilio') }}" method="POST" class="contents">
                        @csrf
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account
                                        SID</label>
                                    <input type="text" name="account_sid" value="{{ $twilio['account_sid'] ?? '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Auth
                                        Token</label>
                                    <input type="password" name="auth_token" value="{{ $twilio['auth_token'] ?? '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">From Number
                                        (WhatsApp)</label>
                                    <input type="text" name="from_whatsapp" value="{{ $twilio['from_whatsapp'] ?? '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition duration-150 ease-in-out transform hover:scale-[1.02]">
                                Save Twilio
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- API Access Settings -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">API Access</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Components allowed to access the API.</p>
                    </div>
                    <form action="{{ route('admin.settings.api-clients') }}" method="POST" class="contents">
                        @csrf
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Allowed API
                                    Clients (One per line)</label>
                                <textarea name="clients" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white px-3 py-2">{{ implode("\n", $apiClients) }}</textarea>
                            </div>
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="allow_http" name="allow_http" type="checkbox" value="1" {{ $apiAllowHttp ? 'checked' : '' }}
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="allow_http" class="font-medium text-gray-700 dark:text-gray-300">Allow HTTP
                                        (Insecure)</label>
                                    <p class="text-gray-500 dark:text-gray-400">Enable only for local development.</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition duration-150 ease-in-out transform hover:scale-[1.02]">
                                Save API Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection