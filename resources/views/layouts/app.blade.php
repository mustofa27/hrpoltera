<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HR Poltera') – HR Poltera</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 font-sans antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- ===== SIDEBAR ===== --}}
    <aside
        id="sidebar"
        class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-800 text-slate-200 flex flex-col transform -translate-x-full transition-transform duration-200 ease-in-out lg:relative lg:translate-x-0 lg:flex"
    >
        {{-- Brand --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-700">
            <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center text-white font-bold text-sm select-none">HR</div>
            <span class="text-lg font-semibold text-white tracking-tight">HR Poltera</span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

            {{-- Presensi --}}
            <p class="px-3 pt-3 pb-1 text-xs font-semibold uppercase tracking-widest text-slate-500">Presensi</p>
            <a href="{{ url('/absensis') }}"
               class="nav-link {{ request()->is('absensis*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Absensi
            </a>
            <a href="{{ url('/lupa_absens') }}"
               class="nav-link {{ request()->is('lupa_absens*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Lupa Absen
            </a>

            {{-- Personalia --}}
            <p class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-widest text-slate-500">Personalia</p>
            <a href="{{ url('/cutis') }}"
               class="nav-link {{ request()->is('cutis*') || request()->is('monitor_cuti*') || request()->is('validasi_cuti*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                Cuti & Ijin
            </a>
            <a href="{{ url('/tugas_dinas') }}"
               class="nav-link {{ request()->is('tugas_dinas*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Tugas Dinas
            </a>

            {{-- Produktivitas --}}
            <p class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-widest text-slate-500">Produktivitas</p>
            <a href="{{ url('/aktivitas_harians') }}"
               class="nav-link {{ request()->is('aktivitas_harians*') || request()->is('monitor*') || request()->is('aktivitas*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Aktivitas Harian
            </a>

            {{-- Master Data --}}
            <p class="px-3 pt-4 pb-1 text-xs font-semibold uppercase tracking-widest text-slate-500">Data Master</p>
            <a href="{{ url('/pegawais') }}"
               class="nav-link {{ request()->is('pegawais*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pegawai
            </a>
            <a href="{{ url('/jabatans') }}"
               class="nav-link {{ request()->is('jabatans*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Jabatan
            </a>
            <a href="{{ url('/golongans') }}"
               class="nav-link {{ request()->is('golongans*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Golongan
            </a>
            <a href="{{ url('/pangkats') }}"
               class="nav-link {{ request()->is('pangkats*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                Pangkat
            </a>
            <a href="{{ url('/unit_kerjas') }}"
               class="nav-link {{ request()->is('unit_kerjas*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                Unit Kerja
            </a>
            <a href="{{ url('/kampuses') }}"
               class="nav-link {{ request()->is('kampuses*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Kampus
            </a>
            <a href="{{ url('/shifts') }}"
               class="nav-link {{ request()->is('shifts*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Shift
            </a>
            <a href="{{ url('/liburs') }}"
               class="nav-link {{ request()->is('liburs*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Hari Libur
            </a>
        </nav>

        {{-- Footer / user info --}}
        <div class="px-4 py-3 border-t border-slate-700 text-xs text-slate-400">
            @auth
                <p class="font-medium text-slate-300 truncate">{{ auth()->user()->nama ?? auth()->user()->email }}</p>
                <p class="truncate">{{ auth()->user()->username ?? '' }}</p>
            @endauth
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Top bar --}}
        <header class="h-14 bg-white border-b border-slate-200 flex items-center justify-between px-4 flex-shrink-0 shadow-sm z-20">
            {{-- Hamburger --}}
            <button
                id="sidebar-toggle"
                class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none"
                aria-label="Toggle sidebar"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page title --}}
            <h1 class="text-sm font-semibold text-slate-700 ml-2 lg:ml-0 truncate">@yield('title', 'Dashboard')</h1>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-1.5 text-sm text-slate-600 hover:text-red-600 font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </header>

        {{-- Flash messages --}}
        @if(session('success') || session('error') || $errors->any())
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="flex items-center gap-2 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm mb-1">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-2 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm mb-1">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm mb-1">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        @endif

        {{-- Scrollable main area --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

{{-- Overlay for mobile sidebar --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-20 hidden lg:hidden"></div>

<style>
.nav-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.45rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    transition: background-color 0.15s, color 0.15s;
}
</style>

<script>
(function () {
    const sidebar  = document.getElementById('sidebar');
    const toggle   = document.getElementById('sidebar-toggle');
    const overlay  = document.getElementById('sidebar-overlay');

    function open()  { sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); }
    function close() { sidebar.classList.add('-translate-x-full');    overlay.classList.add('hidden'); }

    if (toggle)  toggle.addEventListener('click', () => sidebar.classList.contains('-translate-x-full') ? open() : close());
    if (overlay) overlay.addEventListener('click', close);
})();
</script>

</body>
</html>
