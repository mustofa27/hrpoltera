<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-sm border border-slate-200 p-6 text-center">
        <h1 class="text-lg font-semibold text-slate-800">Welcome</h1>
        <p class="mt-2 text-sm text-slate-600">Please sign in to continue.</p>

        <a href="{{ route('sso.redirect') }}"
           class="mt-5 inline-flex items-center justify-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors">
            Login with SSO
        </a>
    </div>
</body>
</html>
