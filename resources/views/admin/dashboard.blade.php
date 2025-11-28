<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(auth()->user()->role === 'karyawan')
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6"><p class="text-sm text-gray-600 mb-1">Sisa Kuota</p><p class="text-3xl font-bold text-gray-800">{{ $sisaCutis }}</p></div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500">
                    <div class="p-6"><p class="text-sm text-gray-600 mb-1">Cuti Sakit</p><p class="text-3xl font-bold text-gray-800">{{ $totalCutiSakit }}</p></div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-purple-500">
                    <div class="p-6"><p class="text-sm text-gray-600 mb-1">Total Pengajuan</p><p class="text-3xl font-bold text-gray-800">{{ $totalPengajuan }}</p></div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6"><p class="text-sm text-gray-600 mb-1">Divisi</p><p class="font-bold text-gray-800">{{ auth()->user()->division ?? '-' }}</p></div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('leave-requests.create') }}" class="bg-blue-600 text-white p-4 rounded-lg shadow hover:bg-blue-700 font-bold text-center transition">
                    + Ajukan Cuti Baru
                </a>
                <a href="{{ route('leave-requests.index') }}" class="bg-white border border-gray-300 text-gray-700 p-4 rounded-lg shadow hover:bg-gray-50 font-bold text-center transition">
                    Lihat Riwayat Cuti
                </a>
            </div>

            @else
            
            @php
                $routePrefix = 'ketua_divisi';
                if(auth()->user()->role === 'admin') $routePrefix = 'admin';
                elseif(auth()->user()->role === 'hrd') $routePrefix = 'hrd';
                
                $linkUrl = route($routePrefix . '.leave-requests');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 {{ auth()->user()->role === 'admin' ? 'lg:grid-cols-6' : 'lg:grid-cols-5' }} gap-4 mb-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-1">Total Karyawan</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $totalKaryawan }}</p>
                    </div>
                </div>

                @if(auth()->user()->role === 'admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-1">Total Divisi</p>
                        <p class="text-3xl font-bold text-indigo-600">{{ $totalDivisi }}</p>
                    </div>
                </div>
                @endif

                <a href="{{ $linkUrl }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition cursor-pointer">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-1">
                            {{ auth()->user()->role === 'admin' || auth()->user()->role === 'hrd' ? 'Pengajuan Bulan Ini' : 'Total Pengajuan' }}
                        </p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalPengajuan }}</p>
                    </div>
                </a>

                <a href="{{ $linkUrl }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition cursor-pointer border-l-4 border-yellow-400">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-1">Menunggu</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $menungguApproval }}</p>
                    </div>
                </a>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-400">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-1">Disetujui</p>
                        <p class="text-3xl font-bold text-green-600">{{ $disetujui }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-400">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-1">Ditolak</p>
                        <p class="text-3xl font-bold text-red-600">{{ $ditolak }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Pengajuan Terbaru</h3>
                            <a href="{{ $linkUrl }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua &rarr;</a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($recentLeaveRequests as $request)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $request->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $request->user->division ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $request->leave_type == 'tahunan' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                {{ ucfirst($request->leave_type) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($request->status == 'pending') <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-semibold">Menunggu</span>
                                            @elseif($request->status == 'approved_by_leader') <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 font-semibold">Acc Ketua</span>
                                            @elseif($request->status == 'approved') <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 font-semibold">Disetujui</span>
                                            @else <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 font-semibold">Ditolak</span> @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('leave-requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900 font-bold hover:underline">Detail</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-sm text-gray-500 text-center">Belum ada pengajuan masuk.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    
                    @if(auth()->user()->role === 'admin')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-orange-400">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Karyawan Baru</h3>
                            <p class="text-xs text-gray-500 mb-3">Pilih divisi untuk karyawan:</p>
                            
                            @if(session('success'))
                                <div class="mb-2 text-xs text-green-600 bg-green-100 p-2 rounded">{{ session('success') }}</div>
                            @endif

                            <ul class="divide-y divide-gray-200">
                                @forelse($newEmployees as $emp)
                                <li class="py-3">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm font-medium">{{ $emp->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $emp->created_at->format('d M') }}</span>
                                    </div>
                                    
                                    <form action="{{ route('admin.assign-division', $emp->id) }}" method="POST" class="flex space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        
                                        @if(!$emp->division)
                                            <select name="division" class="text-xs border-gray-300 rounded p-1 flex-1">
                                                <option value="" disabled selected>Pilih...</option>
                                                <option value="IT">IT</option>
                                                <option value="Finance">Finance</option>
                                                <option value="Marketing">Marketing</option>
                                                <option value="HRD">HRD</option>
                                            </select>
                                            <button type="submit" class="bg-blue-600 text-white text-xs px-2 py-1 rounded hover:bg-blue-700">OK</button>
                                        @else
                                            <span class="text-xs bg-gray-100 px-2 py-1 rounded w-full text-center">{{ $emp->division }}</span>
                                        @endif
                                    </form>
                                </li>
                                @empty
                                <p class="text-sm text-gray-500 text-center py-2">Kosong.</p>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    @endif

                    @if(auth()->user()->role === 'ketua_divisi')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-cyan-500">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Anggota Divisi</h3>
                            <ul class="divide-y divide-gray-200">
                                @forelse($divisionMembers as $member)
                                <li class="py-2 flex justify-between">
                                    <span class="text-sm font-medium">{{ $member->name }}</span>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">Aktif</span>
                                </li>
                                @empty <p class="text-sm text-gray-500">Belum ada anggota.</p> @endforelse
                            </ul>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-pink-500">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Cuti Minggu Ini</h3>
                            <ul class="divide-y divide-gray-200">
                                @forelse($onLeaveThisWeek as $leave)
                                <li class="py-2 text-sm">{{ $leave->user->name }} <span class="text-xs text-gray-500 float-right">{{ $leave->start_date->format('d M') }}</span></li>
                                @empty <p class="text-sm text-gray-500">Tidak ada.</p> @endforelse
                            </ul>
                        </div>
                    </div>
                    @endif

                    @if(auth()->user()->role === 'hrd')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-teal-500">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Sedang Cuti Hari Ini</h3>
                            <ul class="divide-y divide-gray-200">
                                @forelse($employeesOnLeave as $leave)
                                <li class="py-2 text-sm">{{ $leave->user->name }} <span class="text-xs text-gray-500 float-right">s/d {{ $leave->end_date->format('d M') }}</span></li>
                                @empty <p class="text-sm text-gray-500">Tidak ada.</p> @endforelse
                            </ul>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-indigo-500">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Daftar Divisi</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($divisionList as $div) <span class="px-2 py-1 bg-gray-100 text-xs rounded font-medium">{{ $div->division }}</span> @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>