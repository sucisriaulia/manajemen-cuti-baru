<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistem Cuti') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=roboto:400,500,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-[#2f353a] text-white flex-shrink-0 hidden md:flex flex-col">
            <div class="h-16 flex items-center justify-center bg-[#23282c] shadow-md">
                <h1 class="text-xl font-bold tracking-wider">SISTEM CUTI</h1>
            </div>

            <div class="p-4 border-b border-gray-700 flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-gray-500 flex items-center justify-center text-sm font-bold">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <div>
                    <p class="text-sm font-semibold truncate w-32">{{ Auth::user()->name }}</p>
                    <div class="flex items-center gap-1 text-xs text-green-400">
                        <div class="w-2 h-2 rounded-full bg-green-400"></div> Online
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 hover:bg-[#3a4248] transition {{ request()->routeIs('dashboard') ? 'bg-[#20a8d8] text-white' : 'text-gray-300' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            Dashboard
                        </a>
                    </li>

                    @if(Auth::user()->role === 'admin')
                    <li class="px-6 py-2 text-xs font-bold text-gray-500 uppercase mt-2">Admin Master</li>
                    <li>
                        <a href="{{ route('users.index') }}" class="flex items-center px-6 py-2 hover:bg-[#3a4248] text-gray-300 {{ request()->routeIs('users.*') ? 'bg-[#3a4248] text-white' : '' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Data User
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('divisions.index') }}" class="flex items-center px-6 py-2 hover:bg-[#3a4248] text-gray-300 {{ request()->routeIs('divisions.*') ? 'bg-[#3a4248] text-white' : '' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Data Divisi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('holidays.index') }}" class="flex items-center px-6 py-2 hover:bg-[#3a4248] text-gray-300 {{ request()->routeIs('holidays.*') ? 'bg-[#3a4248] text-white' : '' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Hari Libur
                        </a>
                    </li>
                    @endif

                    <li class="px-6 py-2 text-xs font-bold text-gray-500 uppercase mt-2">Main Menu</li>
                    
                    @if(Auth::user()->role !== 'karyawan')
                    <li>
                        <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.leave-requests' : (Auth::user()->role === 'hrd' ? 'hrd.leave-requests' : 'ketua_divisi.leave-requests')) }}" 
                           class="flex items-center px-6 py-2 hover:bg-[#3a4248] text-gray-300">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Approval Cuti
                        </a>
                    </li>
                    @endif

                    <li>
                        <a href="{{ route('leave-requests.index') }}" class="flex items-center px-6 py-2 hover:bg-[#3a4248] text-gray-300 {{ request()->routeIs('leave-requests.index') ? 'bg-[#3a4248] text-white' : '' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Riwayat Cuti
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('leave-requests.create') }}" class="flex items-center px-6 py-2 hover:bg-[#3a4248] text-gray-300 {{ request()->routeIs('leave-requests.create') ? 'bg-[#3a4248] text-white' : '' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Ajukan Cuti
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 bg-[#23282c]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center text-gray-400 hover:text-white w-full transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex justify-between items-center py-4 px-6 bg-white border-b border-gray-200">
                <div class="flex items-center">
                    <button class="text-gray-500 focus:outline-none md:hidden">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800 ml-4">{{ $header ?? 'Dashboard' }}</h2>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>