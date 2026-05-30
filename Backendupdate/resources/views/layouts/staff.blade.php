@php
    $panelTitle = $panelTitle ?? 'Staff Panel';
    $dashboardRoute = $dashboardRoute ?? 'counsellor.dashboard';
@endphp
<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $panelTitle) - Pioneers Edu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full flex overflow-hidden bg-gray-50 text-gray-900 text-sm">
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col flex-shrink-0">
        <div class="h-16 flex items-center px-6 bg-slate-950/50">
            <i class="fa-solid fa-graduation-cap text-blue-500 text-xl mr-3"></i>
            <span class="text-white font-bold text-lg">{{ $panelTitle }}</span>
        </div>
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            @yield('sidebar')
        </nav>
        <div class="p-4 border-t border-slate-800">
            <p class="text-xs text-slate-500 truncate">{{ auth()->user()->name }}</p>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="text-xs text-slate-400 hover:text-white">Sign out</button>
            </form>
        </div>
    </aside>
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center px-6">
            <h1 class="text-lg font-semibold text-gray-800">@yield('header', 'Dashboard')</h1>
        </header>
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 m-6 mb-0 rounded text-green-700 text-sm">{{ session('success') }}</div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 m-6 mb-0 rounded text-red-700 text-sm">
                <ul class="list-disc pl-4">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <main class="flex-1 overflow-y-auto p-6">@yield('content')</main>
    </div>
</body>
</html>
