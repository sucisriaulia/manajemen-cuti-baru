<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HR System') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        .sidebar-gradient { background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); }
        .nav-active { background: rgba(255, 255, 255, 0.1); border-left: 4px solid #38bdf8; color: white; }
        .nav-item:hover { background: rgba(255, 255, 255, 0.05); color: white; transition: all 0.3s; }
    </style>
</head>
<body class="antialiased text-slate-800">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 sidebar-gradient text-slate-400 flex-shrink-0 hidden md:flex flex-col shadow-2xl z-50">
            <div class="h-20 flex items-center justify-center border-b border-slate-700/50">
                <div class="flex items-center gap-2 font-bold text-white text-xl tracking-wider">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/50">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    HR SYSTEM
                </div>
            </div>

            <div class="p-6 border-b border-slate-700/50 flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-slate-600 flex items-center justify-center text-white font-bold shadow-md">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="h-full w-full rounded-full object-cover">
                    @else
                        {{ substr(Auth::user()->name, 0, 2) }}
                    @endif
                </div>
                <div>
                    <p class="text-sm font-semibold text-white truncate w-32">{{ Auth::user()->name }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <div class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                        <span class="text-xs text-emerald-400 font-medium">Online</span>
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
                
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg nav-item {{ request()->routeIs('dashboard') ? 'nav-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>

                @if(Auth::user()->role === 'admin')
                <div class="mt-6 mb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Master Data</div>
                <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2.5 rounded-lg nav-item {{ request()->routeIs('users.*') ? 'nav-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Data User
                </a>
                <a href="{{ route('divisions.index') }}" class="flex items-center px-4 py-2.5 rounded-lg nav-item {{ request()->routeIs('divisions.*') ? 'nav-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Divisi
                </a>
                <a href="{{ route('holidays.index') }}" class="flex items-center px-4 py-2.5 rounded-lg nav-item {{ request()->routeIs('holidays.*') ? 'nav-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Hari Libur
                </a>
                @endif

                @if(Auth::user()->role !== 'karyawan')
                <div class="mt-6 mb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Persetujuan</div>
                <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.leave-requests' : (Auth::user()->role === 'hrd' ? 'hrd.leave-requests' : 'ketua_divisi.leave-requests')) }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg nav-item {{ request()->routeIs('*.leave-requests') ? 'nav-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Approval Cuti
                </a>
                @endif

                @if(Auth::user()->role === 'karyawan' || Auth::user()->role === 'ketua_divisi')
                <div class="mt-6 mb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Menu Saya</div>
                
                <a href="{{ route('leave-requests.index') }}" class="flex items-center px-4 py-2.5 rounded-lg nav-item {{ request()->routeIs('leave-requests.index') ? 'nav-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat Cuti
                </a>
                <a href="{{ route('leave-requests.create') }}" class="flex items-center px-4 py-2.5 rounded-lg nav-item {{ request()->routeIs('leave-requests.create') ? 'nav-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Ajukan Cuti
                </a>
                @endif

            </nav>

            <div class="p-4 border-t border-slate-700/50 bg-[#0f172a]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 text-slate-400 hover:text-red-400 font-medium text-sm w-full transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-10">
                <h2 class="text-xl font-bold text-slate-800">{{ $header ?? 'Dashboard' }}</h2>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-slate-500 bg-slate-100 px-3 py-1 rounded-full font-medium">{{ now()->format('d F Y') }}</span>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#f1f5f9] p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>