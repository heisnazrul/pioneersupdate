@php
    $branding = \App\Support\SystemSettings::branding();
    $appName = $branding['app_name'] ?? config('app.name', 'Admin');
    $faviconUrl = $branding['favicon_url'] ?? asset('assets/img/brand-logos/favicon.ico');
@endphp

<!DOCTYPE html>
<html lang="en" dir="ltr" class="light" data-header-styles="light" data-menu-styles="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }} Dashboard</title>
    <meta name="description" content="Admin Dashboard Template">
    <meta name="keywords" content="admin dashboard">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ $faviconUrl }}">

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/@simonwep/pickr/themes/nano.min.css') }}">
    @stack('styles')
</head>

<body>

  <div class="page">

    @include('admin.layouts.sidebar', ['branding' => $branding])
    @include('admin.layouts.header', ['branding' => $branding])

    <div class="content">
        <div class="main-content">
            @yield('content') <!-- Content will be inserted here -->
        </div>
    </div>

    @include('admin.layouts.headersearch_modal')
    @include('admin.layouts.footer')

  </div>

  <!-- Scripts -->
  <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/js/index-12.js') }}"></script>

  <!-- Scroll To Top -->
  <div class="scrollToTop">
      <span class="arrow"><i class="ri-arrow-up-s-fill text-xl"></i></span>
  </div>

  <div id="responsive-overlay"></div>

  <!-- Popper.js -->
  <script src="{{ asset('assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>

  <!-- Pickr JS -->
  <script src="{{ asset('assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

  <!-- Custom JS Files -->
  <script src="{{ asset('assets/js/defaultmenu.js') }}"></script>
  <script src="{{ asset('assets/js/sticky.js') }}"></script>
  <script src="{{ asset('assets/js/switch.js') }}"></script>
  <script src="{{ asset('assets/libs/preline/preline.js') }}"></script>
  <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
  <script src="{{ asset('assets/js/custom.js') }}"></script>
  <script src="{{ asset('assets/js/custom-switcher.js') }}"></script>

  @stack('scripts')

</body>
</html>
