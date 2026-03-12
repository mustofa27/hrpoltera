<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-sm">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 pt-6 pb-5 border-b border-slate-100">
                <h1 class="text-xl font-semibold text-slate-800">Sign in</h1>
                <p class="mt-1 text-sm text-slate-600">Use your SSO account to access the system.</p>
            </div>

            <div class="px-6 py-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Single Sign-On</label>

                <a href="{{ route('sso.redirect') }}"
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Login with SSO
                </a>

                <p class="mt-4 text-xs text-slate-500 leading-relaxed">
                    If you cannot sign in, please contact your administrator.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
