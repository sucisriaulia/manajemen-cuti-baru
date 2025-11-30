<x-app-layout>
    <x-slot name="header">
        Dashboard Overview
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl shadow-lg shadow-blue-500/30 text-white p-6 relative overflow-hidden transform hover:-translate-y-1 transition duration-300">
            <div class="relative z-10">
                <p class="text-blue-100 text-xs font-bold uppercase tracking-wider mb-1">
                    {{ auth()->user()->role == 'karyawan' ? 'Sisa Kuota Cuti' : 'Total Karyawan' }}
                </p>
                <h3 class="text-4xl font-bold">{{ $sisaCutis ?? $totalKaryawan }}</h3>
            </div>
            <div class="absolute right-0 top-0 opacity-20 transform translate-x-2 -translate-y-2">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
            </div>
        </div>

        @php
            $routePrefix = 'ketua_divisi';
            if(auth()->user()->role === 'admin') $routePrefix = 'admin';
            elseif(auth()->user()->role === 'hrd') $routePrefix = 'hrd';
            $linkUrl = route($routePrefix . '.leave-requests');
        @endphp

        @if(auth()->user()->role === 'admin')
            <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl shadow-lg shadow-orange-500/30 text-white p-6 relative overflow-hidden transform hover:-translate-y-1 transition duration-300">
                <div class="relative z-10">
                    <p class="text-orange-100 text-xs font-bold uppercase tracking-wider mb-1">Total Divisi</p>
                    <h3 class="text-4xl font-bold">{{ $totalDivisi ?? 0 }}</h3>
                </div>
                <div class="absolute right-0 top-0 opacity-20">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
        @else
            <a href="{{ $linkUrl }}" class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl shadow-lg shadow-orange-500/30 text-white p-6 relative overflow-hidden transform hover:-translate-y-1 transition duration-300 group cursor-pointer">
                <div class="relative z-10">
                    <p class="text-orange-100 text-xs font-bold uppercase tracking-wider mb-1">Menunggu Verifikasi</p>
                    <h3 class="text-4xl font-bold">{{ $menungguApproval ?? 0 }}</h3>
                    <p class="text-xs text-white mt-2 font-medium flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        Proses Sekarang &rarr;
                    </p>
                </div>
                <div class="absolute right-0 top-0 opacity-20">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                </div>
            </a>
        @endif

        <div class="bg-gradient-to-br from-emerald-400 to-green-600 rounded-2xl shadow-lg shadow-emerald-500/30 text-white p-6 relative overflow-hidden transform hover:-translate-y-1 transition duration-300">
            <div class="relative z-10">
                <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider mb-1">
                    {{ auth()->user()->role === 'admin' ? 'Pengajuan Bulan Ini' : 'Disetujui' }}
                </p>
                <h3 class="text-4xl font-bold">
                    {{ auth()->user()->role === 'admin' ? $totalPengajuan : ($disetujui ?? 0) }}
                </h3>
            </div>
            <div class="absolute right-0 top-0 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-rose-400 to-red-600 rounded-2xl shadow-lg shadow-rose-500/30 text-white p-6 relative overflow-hidden transform hover:-translate-y-1 transition duration-300">
            <div class="relative z-10">
                <p class="text-rose-100 text-xs font-bold uppercase tracking-wider mb-1">
                    {{ auth()->user()->role === 'admin' ? 'Menunggu Approval' : 'Ditolak' }}
                </p>
                <h3 class="text-4xl font-bold">
                    {{ auth()->user()->role === 'admin' ? $menungguApproval : ($ditolak ?? 0) }}
                </h3>
            </div>
            <div class="absolute right-0 top-0 opacity-20">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            
            @if(auth()->user()->role === 'admin')
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-orange-50">
                    <div>
                        <h3 class="font-bold text-lg text-orange-800">Monitoring Karyawan Baru</h3>
                        <p class="text-xs text-orange-600">Daftar karyawan < 1 tahun (Belum eligible cuti tahunan)</p>
                    </div>
                    <a href="{{ route('users.index') }}" class="text-sm text-orange-600 font-bold hover:underline">Kelola User &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs">
                            <tr>
                                <th class="p-4">Nama Karyawan</th>
                                <th class="p-4">Masa Kerja</th>
                                <th class="p-4">Divisi</th>
                                <th class="p-4 text-right">Bergabung</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($newEmployees as $emp)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                            {{ substr($emp->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $emp->name }}</p>
                                            <p class="text-xs text-slate-400">{{ $emp->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded font-bold">
                                        {{ $emp->created_at->diffForHumans(null, true) }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="text-xs font-semibold bg-slate-100 px-2 py-1 rounded">{{ $emp->division ?? '-' }}</span>
                                </td>
                                <td class="p-4 text-right text-slate-500 text-xs">
                                    {{ $emp->created_at->format('d M Y') }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="p-8 text-center text-slate-400">Tidak ada karyawan baru (< 1 tahun).</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            @else
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-800">Aktivitas Pengajuan Terbaru</h3>
                    <a href="{{ $linkUrl }}" class="text-sm text-blue-600 font-bold hover:text-blue-800 transition">Lihat Semua &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs">
                            <tr>
                                <th class="p-4">Karyawan</th>
                                <th class="p-4">Jenis & Tanggal</th>
                                <th class="p-4 text-center">Status</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentLeaveRequests as $req)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4">
                                    <p class="font-bold text-slate-900">{{ $req->user->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $req->user->division ?? '-' }}</p>
                                </td>
                                <td class="p-4">
                                    <span class="font-bold text-slate-700">{{ ucfirst($req->leave_type) }}</span>
                                    <br> {{ $req->start_date->format('d M') }} - {{ $req->end_date->format('d M Y') }}
                                </td>
                                <td class="p-4 text-center">
                                    @if($req->status == 'pending') <span class="px-3 py-1 rounded-full bg-yellow-50 text-yellow-700 text-xs font-bold border border-yellow-100">Menunggu</span>
                                    @elseif($req->status == 'approved_by_leader') <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">Acc Ketua</span>
                                    @elseif($req->status == 'approved') <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold border border-green-100">Disetujui</span>
                                    @elseif($req->status == 'rejected') <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold border border-red-100">Ditolak</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('leave-requests.show', $req->id) }}" class="text-slate-400 hover:text-blue-600 font-bold transition">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="p-6 text-center text-slate-400">Belum ada data pengajuan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-20 bg-gradient-to-r from-blue-500 to-cyan-400"></div>
                <div class="relative z-10">
                    @if(auth()->user()->avatar)
                        <img class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-md mx-auto mb-4" src="{{ asset('storage/' . auth()->user()->avatar) }}">
                    @else
                        <div class="h-24 w-24 rounded-full bg-slate-200 border-4 border-white shadow-md mx-auto mb-4 flex items-center justify-center text-3xl font-bold text-slate-400">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                    <h3 class="text-xl font-bold text-slate-800">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-slate-500">{{ auth()->user()->email }}</p>
                    
                    <div class="mt-6 grid grid-cols-2 gap-4 text-left">
                        <div class="bg-slate-50 p-3 rounded-lg">
                            <p class="text-xs text-slate-400 font-bold uppercase">Divisi</p>
                            <p class="font-semibold text-slate-700">{{ auth()->user()->division ?? '-' }}</p>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-lg">
                            <p class="text-xs text-slate-400 font-bold uppercase">Role</p>
                            <p class="font-semibold text-slate-700 capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('profile.edit') }}" class="block mt-4 w-full bg-slate-800 text-white py-2.5 rounded-xl font-bold text-sm hover:bg-slate-700 transition">Edit Profil</a>
                </div>
            </div>

            @if(auth()->user()->role === 'admin')
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h4 class="font-bold text-slate-800 mb-4">Akses Cepat</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('users.create') }}" class="flex flex-col items-center justify-center p-3 bg-indigo-50 rounded-xl text-indigo-600 hover:bg-indigo-100 transition">
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span class="text-xs font-bold">Add User</span>
                        </a>
                        <a href="{{ route('divisions.index') }}" class="flex flex-col items-center justify-center p-3 bg-cyan-50 rounded-xl text-cyan-600 hover:bg-cyan-100 transition">
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="text-xs font-bold">Divisi</span>
                        </a>
                    </div>
                </div>
            @endif

            @if(auth()->user()->role === 'hrd')
                <div class="bg-white rounded-2xl shadow-sm border-t-4 border-teal-500 p-6">
                    <h3 class="font-bold text-slate-800 mb-2">Cuti Hari Ini</h3>
                    <ul class="divide-y divide-slate-100 text-sm">
                        @forelse($employeesOnLeave as $leave)
                            <li class="py-2 flex justify-between">
                                <span>{{ $leave->user->name }}</span>
                                <span class="text-xs bg-teal-100 text-teal-700 px-2 py-0.5 rounded">s/d {{ $leave->end_date->format('d/m') }}</span>
                            </li>
                        @empty <p class="text-xs text-slate-400">Tidak ada.</p> @endforelse
                    </ul>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>