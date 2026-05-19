<!-- resources/views/admin/layouts/menu.blade.php -->

@php
    $branding = $branding ?? \App\Support\SystemSettings::branding();
    $logoUrl = $branding['logo_url'] ?? asset('assets/logo.png');
    $appName = $branding['app_name'] ?? config('app.name', 'Dashboard');
@endphp

<aside class="app-sidebar" id="sidebar">
    <div class="main-sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="header-logo">
            <img src="{{ $logoUrl }}" alt="{{ $appName }}" class="main-logo desktop-logo h-10 w-auto">
        </a>
    </div>
    <div class="main-sidebar" id="sidebar-scroll">
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path></svg></div>
            <ul class="main-menu">
                <!-- Add your menu items here -->
            </ul>
        </nav>
    </div>
</aside>
