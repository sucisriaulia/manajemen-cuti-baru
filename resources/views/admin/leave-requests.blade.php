<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Daftar Persetujuan Cuti') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-center">
                    <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500 font-bold">
                            <tr>
                                <th class="p-5">Pemohon</th>
                                <th class="p-5">Jenis</th>
                                <th class="p-5">Tanggal</th>
                                <th class="p-5">Alasan</th>
                                <th class="p-5 text-center">Status Saat Ini</th>
                                <th class="p-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($leaveRequests as $req)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-5">
                                    <div class="font-bold text-gray-900">{{ $req->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $req->user->division ?? '-' }}</div>
                                </td>
                                <td class="p-5">
                                    <span class="px-2 py-1 rounded bg-indigo-50 text-indigo-700 text-xs font-bold uppercase">
                                        {{ $req->leave_type }}
                                    </span>
                                </td>
                                <td class="p-5 text-gray-600">
                                    {{ $req->start_date->format('d M') }} - {{ $req->end_date->format('d M Y') }}
                                    <br>
                                    <span class="text-xs text-gray-400">({{ $req->total_days }} hari)</span>
                                </td>
                                <td class="p-5 text-gray-600 truncate max-w-xs">{{ $req->reason }}</td>
                                <td class="p-5 text-center">
                                    @if($req->status == 'pending')
                                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">Menunggu Verifikasi</span>
                                    @elseif($req->status == 'approved_by_leader')
                                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">Acc Ketua Tim</span>
                                    @elseif($req->status == 'approved')
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">Disetujui HRD</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Ditolak</span>
                                    @endif
                                </td>
                                <td class="p-5 text-right">
                                    
                                    @php
                                        $canApprove = false;
                                        $user = auth()->user();
                                        $requesterRole = $req->user->role;
                                        $currentStatus = $req->status;

                                        // 1. KETUA DIVISI: Hanya bisa approve Karyawan Pending
                                        if ($user->role === 'ketua_divisi' && $requesterRole === 'karyawan' && $currentStatus === 'pending') {
                                            $canApprove = true;
                                        }
                                        
                                        // 2. HRD: Bisa approve jika sudah Acc Ketua ATAU pengajuan dari Ketua
                                        if ($user->role === 'hrd') {
                                            if (($requesterRole === 'karyawan' && $currentStatus === 'approved_by_leader') || ($requesterRole === 'ketua_divisi' && $currentStatus === 'pending')) {
                                                $canApprove = true;
                                            }
                                        }

                                        // 3. ADMIN (SYSTEM OVERRIDE): BISA APPROVE SEMUANYA (KECUALI YG SUDAH FINAL)
                                        if ($user->role === 'admin') {
                                            if ($currentStatus !== 'approved' && $currentStatus !== 'rejected') {
                                                $canApprove = true;
                                            }
                                        }
                                    @endphp

                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('leave-requests.show', $req->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold text-xs hover:underline">Detail</a>

                                        @if($canApprove)
                                            <form action="{{ route(auth()->user()->role . '.approve', $req->id) }}" method="POST">
                                                @csrf
                                                <button class="bg-green-600 text-white px-3 py-1 rounded shadow hover:bg-green-700 text-xs font-bold transition">Setuju</button>
                                            </form>
                                            <form action="{{ route(auth()->user()->role . '.reject', $req->id) }}" method="POST" onsubmit="return confirm('Tolak pengajuan ini?');">
                                                @csrf
                                                <button class="bg-red-600 text-white px-3 py-1 rounded shadow hover:bg-red-700 text-xs font-bold transition">Tolak</button>
                                            </form>
                                        @else
                                            <span class="text-gray-300 text-xs italic ml-2">Tidak ada aksi</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="p-8 text-center text-gray-400">Belum ada data pengajuan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>