@php
    $branding = \App\Support\SystemSettings::branding();
    $appName = $branding['app_name'] ?? config('app.name');
    $primaryColor = $branding['primary_color'] ?? '#6366f1';
    $logoUrl = $branding['logo_url'] ?? asset('assets/logo.png');
    $faviconUrl = $branding['favicon_url'] ?? asset('assets/img/brand-logos/favicon.ico');
@endphp

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }} - Sign In</title>
    <link rel="icon" href="{{ $faviconUrl }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '{{ $primaryColor }}', // Dynamic primary
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b',
                        }
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #0f172a;
            background-image:
                radial-gradient(at 0% 0%, hsla(253, 16%, 7%, 1) 0, transparent 50%),
                radial-gradient(at 50% 0%, hsla(225, 39%, 30%, 1) 0, transparent 50%),
                radial-gradient(at 100% 0%, hsla(339, 49%, 30%, 1) 0, transparent 50%);
        }

        .glass-panel {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glass-input {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.2);
            transition: all 0.3s ease;
        }

        .glass-input:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color:
                {{ $primaryColor }}
            ;
            box-shadow: 0 0 0 2px color-mix(in srgb,
                    {{ $primaryColor }}
                    30%, transparent);
        }
    </style>
</head>

<body class="h-full flex items-center justify-center p-4 sm:p-6 lg:p-8 overflow-hidden relative">

    <!-- Background Effects -->
    <div
        class="absolute top-0 -left-4 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
    </div>
    <div
        class="absolute top-0 -right-4 w-72 h-72 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
    </div>
    <div
        class="absolute -bottom-8 left-20 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000">
    </div>

    <!-- Main Container -->
    <div class="w-full max-w-md relative z-10 animate-fade-in">

        <!-- Login Card -->
        <div class="glass-panel rounded-2xl shadow-2xl p-8 sm:p-10">

            <!-- Header -->
            <div class="text-center mb-8">
                @if ($logoUrl)
                    <div
                        class="mx-auto h-20 w-auto bg-white/10 rounded-xl p-3 backdrop-blur-sm inline-flex items-center justify-center mb-6 ring-1 ring-white/10 shadow-lg">
                        <!-- We use object-contain to ensure the logo fits nicely even if it has a background -->
                        <img src="{{ $logoUrl }}" alt="{{ $appName }}" class="h-full w-auto object-contain">
                    </div>
                @endif
                <h2 class="text-2xl font-bold tracking-tight text-white">
                    Welcome back
                </h2>
                <p class="mt-2 text-sm text-slate-400">
                    Sign in to your dashboard to continue
                </p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-500/10 border border-red-500/20 p-4 text-sm text-red-400" role="alert">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-6 rounded-lg bg-emerald-500/10 border border-emerald-500/20 p-4 text-sm text-emerald-400"
                    role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login.attempt') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="glass-input block w-full pl-10 pr-3 py-2.5 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-0 sm:text-sm"
                            placeholder="name@company.com">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                        <a href="{{ route('password.request') }}"
                            class="text-sm font-medium text-brand-400 hover:text-brand-300 transition-colors">
                            Forgot password?
                        </a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="glass-input block w-full pl-10 pr-3 py-2.5 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-0 sm:text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-brand-600 hover:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-brand-500 transition-all duration-200 shadow-lg shadow-brand-500/20 hover:shadow-brand-500/40">
                        Sign in
                    </button>
                </div>
            </form>

            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span
                            class="px-2 bg-slate-900/50 text-slate-400 glass-panel border-0 rounded-full px-4 text-xs">Or
                            continue with</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <a href="{{ route('login.google') }}"
                        class="flex items-center justify-center w-full px-4 py-2.5 rounded-xl glass-input hover:bg-slate-800 transition-colors group">
                        <img src="https://www.google.com/favicon.ico" alt="Google"
                            class="h-5 w-5 opacity-80 group-hover:opacity-100 transition-opacity">
                        <span
                            class="ml-2 text-sm font-medium text-slate-300 group-hover:text-white transition-colors">Google</span>
                    </a>

                    <a href="{{ route('login.whatsapp') }}"
                        class="flex items-center justify-center w-full px-4 py-2.5 rounded-xl glass-input hover:bg-slate-800 transition-colors group">
                        <svg class="h-5 w-5 text-emerald-500 opacity-80 group-hover:opacity-100 transition-opacity"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                        </svg>
                        <span
                            class="ml-2 text-sm font-medium text-slate-300 group-hover:text-white transition-colors">WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="mt-8 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
        </p>
    </div>
</body>

</html>