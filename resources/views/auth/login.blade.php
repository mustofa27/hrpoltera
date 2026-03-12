<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-xs">
        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
            <div class="px-7 pt-7 pb-6 border-b border-slate-100 space-y-2">
                <h1 class="text-xl font-semibold text-slate-800">Sign in</h1>
                <p class="text-sm text-slate-600">Use your SSO account to access the system.</p>
            </div>

            <div class="px-7 py-7 space-y-4">
                <label class="block text-sm font-medium text-slate-700">Single Sign-On</label>

                <a href="{{ route('sso.redirect') }}"
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Login with SSO
                </a>

                <p class="text-xs text-slate-500 leading-relaxed">
                    If you cannot sign in, please contact your administrator.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
