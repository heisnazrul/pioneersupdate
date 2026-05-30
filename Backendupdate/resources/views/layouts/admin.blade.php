<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Pioneers Edu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa',
                            500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a', 950: '#172554',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            let sidebarOpen = true;
            try {
                const saved = localStorage.getItem('adminSidebarOpen');
                if (saved !== null) sidebarOpen = saved === 'true';
            } catch (e) {}

            Alpine.store('adminLayout', {
                sidebarOpen,
                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                    try { localStorage.setItem('adminSidebarOpen', this.sidebarOpen); } catch (e) {}
                },
            });
        });
    </script>
</head>
<body class="h-full flex overflow-hidden bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm">

    <!-- Sidebar -->
    <aside class="flex-shrink-0 bg-slate-900 text-slate-300 flex flex-col transition-all duration-300"
           :class="$store.adminLayout.sidebarOpen ? 'w-64' : 'w-[4.5rem]'">
        <!-- Logo -->
        <div class="h-16 flex items-center px-4 bg-slate-950/50 shrink-0">
            <i class="fa-solid fa-graduation-cap text-primary-500 text-xl w-6 text-center shrink-0"></i>
            <span class="text-white font-bold text-lg tracking-wide whitespace-nowrap overflow-hidden ml-3 transition-opacity"
                  x-show="$store.adminLayout.sidebarOpen" x-cloak>Pioneers Edu</span>
        </div>

        <!-- Nav Links -->
        <div class="flex-1 overflow-y-auto py-4 custom-scrollbar">
            @include('layouts.partials.admin-nav')
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 bg-gray-50 dark:bg-gray-900">
        
        <!-- Header -->
        <header class="bg-white dark:bg-gray-800 shadow-sm h-16 flex items-center justify-between px-6 z-10">
            <div class="flex items-center">
                <button @click="$store.adminLayout.toggleSidebar()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none mr-4">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 hidden sm:block">@yield('header', 'Overview')</h2>
            </div>

            <div class="flex items-center gap-3">
                <form method="POST" action="{{ route('admin.cache.flush') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                            title="Clear cached catalog API responses">
                        <i class="fa-solid fa-arrows-rotate text-primary-600"></i>
                        <span class="hidden sm:inline">Flush Cache</span>
                    </button>
                </form>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 focus:outline-none">
                    @if(auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                    @else
                        <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                    <span class="hidden md:block">{{ auth()->user()->name }}</span>
                    <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" x-transition.opacity class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 border border-gray-200 dark:border-gray-700 z-50" x-cloak>
                    <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('admin.users.edit', auth()->id()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Profile</a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 m-6 mb-0 rounded shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-check text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 dark:text-green-400">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 m-6 mb-0 rounded shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 dark:text-red-400">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 m-6 mb-0 rounded shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0 pt-0.5">
                        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-red-800 dark:text-red-300">Please fix the following errors:</p>
                        <ul class="mt-2 list-disc list-inside space-y-1 text-sm text-red-700 dark:text-red-400">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
        
    </div>

</body>
</html>
