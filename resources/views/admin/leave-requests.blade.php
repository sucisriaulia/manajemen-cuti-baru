<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Persetujuan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pemohon</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alasan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Saat Ini</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($leaveRequests as $req)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $req->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $req->user->division }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2 py-1 rounded-full text-xs {{ $req->leave_type == 'tahunan' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ ucfirst($req->leave_type) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        {{ $req->start_date->format('d M') }} - {{ $req->end_date->format('d M Y') }}<br>
                                        <span class="text-xs text-gray-500">({{ $req->total_days }} hari)</span>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600 truncate max-w-xs">
                                        {{ $req->reason }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($req->status == 'pending')
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-bold">Menunggu Verifikasi</span>
                                        @elseif($req->status == 'approved_by_leader')
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-bold">Disetujui Ketua Tim</span>
                                        @elseif($req->status == 'approved')
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-bold">Disetujui HRD</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-bold">Ditolak</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm font-medium">
                                        
                                        @php
                                            $canApprove = false;
                                            $user = auth()->user();

                                            // 1. KETUA DIVISI: Hanya bisa approve punya Karyawan status 'pending'
                                            if ($user->role === 'ketua_divisi' && $req->status === 'pending' && $req->user->role === 'karyawan') {
                                                $canApprove = true;
                                            }
                                            
                                            // 2. HRD: 
                                            // - Bisa approve punya Karyawan jika status 'approved_by_leader'
                                            // - Bisa approve punya Ketua Divisi jika status 'pending'
                                            if ($user->role === 'hrd') {
                                                if ($req->user->role === 'karyawan' && $req->status === 'approved_by_leader') $canApprove = true;
                                                if ($req->user->role === 'ketua_divisi' && $req->status === 'pending') $canApprove = true;
                                            }
                                        @endphp

                                        @if($canApprove)
                                            <div class="flex items-center space-x-2">
                                                <form action="{{ route(auth()->user()->role . '.approve', $req->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                        Setujui
                                                    </button>
                                                </form>

                                                <form action="{{ route(auth()->user()->role . '.reject', $req->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak pengajuan ini?');">
                                                    @csrf
                                                    <input type="hidden" name="rejection_reason" value="Ditolak oleh {{ auth()->user()->role }}">
                                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Tidak ada aksi</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada data pengajuan cuti.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>