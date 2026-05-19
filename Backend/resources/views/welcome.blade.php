<!-- resources/views/welcome.blade.php -->

@php
    $branding = \App\Support\SystemSettings::branding();
    $appName = $branding['app_name'] ?? config('app.name');
    $primaryColor = $branding['primary_color'] ?? '#6200ea';
    $logoUrl = $branding['logo_url'] ?? asset('assets/logo.png');
    $faviconUrl = $branding['favicon_url'] ?? asset('assets/img/brand-logos/favicon.ico');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }}</title>
    <link rel="icon" href="{{ $faviconUrl }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #121212;
            color: #fff;
            font-family: Arial, sans-serif;
        }
        .container {
            height: 100vh;
        }
        .row {
            height: 100%;
        }
        .col-md-6 {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .text-center {
            text-align: center;
        }
        .btn-primary {
            background-color: {{ $primaryColor }};
            border-color: {{ $primaryColor }};
        }
        .btn-primary:hover {
            background-color: color-mix(in srgb, {{ $primaryColor }} 85%, white 15%);
            border-color: color-mix(in srgb, {{ $primaryColor }} 80%, transparent 20%);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center">
                    @if ($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $appName }} logo" style="max-height: 90px; object-fit: contain; margin-bottom: 1.5rem;">
                    @endif
                    <h1>Welcome to {{ $appName }}</h1>
                    <p class="lead">
                        @auth
                            Dive back into your workspace below.
                        @else
                            Please login to access your dashboard.
                        @endauth
                    </p>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    @auth
                        <a href="{{ \App\Http\Controllers\AuthController::redirectForUser(auth()->user()) }}" class="btn btn-primary">Go to Dashboard</a>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
