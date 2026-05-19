<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-6">
    <div class="max-w-md w-full bg-white shadow-lg rounded-2xl p-8 text-center">
        <div class="mx-auto w-14 h-14 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-2xl mb-4">
            !
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Access denied</h1>
        <p class="text-sm text-gray-600 mb-6">
            This account does not have permission to access the admin panel.
        </p>

        <form method="POST" action="{{ route('logout') }}" class="space-y-3">
            @csrf
            <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white py-3 rounded-xl font-semibold transition-colors">
                Sign out
            </button>
        </form>
    </div>
</body>
</html>
