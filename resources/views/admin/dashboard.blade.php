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
                    <div class="p-6"><p class="text-sm text-gray-600 mb-1">Sisa Kuota</p><p class="text-3xl font-bold text-gray-800">{{ $sisaCutis }} hari</p></div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500">
                    <div class="p-6"><p class="text-sm text-gray-600 mb-1">Cuti Sakit</p><p class="text-3xl font-bold text-gray-800">{{ $totalCutiSakit }} kali</p></div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-purple-500">
                    <div class="p-6"><p class="text-sm text-gray-600 mb-1">Total Pengajuan</p><p class="text-3xl font-bold text-gray-800">{{ $totalPengajuan }} kali</p></div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6"><p class="text-sm text-gray-600 mb-1">Divisi</p><p class="font-bold text-gray-800">{{ auth()->user()->division ?? '-' }}</p></div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('leave-requests.create') }}" class="bg-blue-50 p-6 rounded-lg border border-blue-100 font-bold text-blue-900 flex items-center justify-center">Ajukan Cuti</a>
                <a href="{{ route('leave-requests.index') }}" class="bg-purple-50 p-6 rounded-lg border border-purple-100 font-bold text-purple-900 flex items-center justify-center">Riwayat Cuti</a>
            </div>

            @else
            
            <div class="grid grid-cols-1 md:grid-cols-2 {{ auth()->user()->role === 'admin' ? 'lg:grid-cols-6' : 'lg:grid-cols-5' }} gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6"><p class="text-sm text-gray-600 mb-1">Total Karyawan</p><p class="text-3xl font-bold text-blue-600">{{ $totalKaryawan }}</p></div>
                </div>

                @if(auth()->user()->role === 'admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6"><p class="text-sm text-gray-600 mb-1">Total Divisi</p><p class="text-3xl font-bold text-indigo-600">{{ $totalDivisi }}</p></div>
                </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-1">{{ auth()->user()->role === 'admin' || auth()->user()->role === 'hrd' ? 'Pengajuan Bulan Ini' : 'Total Pengajuan' }}</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalPengajuan }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6"><p class="text-sm text-gray-600 mb-1">Menunggu</p><p class="text-3xl font-bold text-yellow-600">{{ $menungguApproval }}</p></div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6"><p class="text-sm text-gray-600 mb-1">Disetujui</p><p class="text-3xl font-bold text-green-600">{{ $disetujui }}</p></div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6"><p class="text-sm text-gray-600 mb-1">Ditolak</p><p class="text-3xl font-bold text-red-600">{{ $ditolak }}</p></div>
                </div>
            </div>

            <div class="grid grid-cols-1 {{ (auth()->user()->role === 'admin' || auth()->user()->role === 'hrd') ? 'lg:grid-cols-2' : '' }} gap-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Pengajuan Terbaru</h3>
                        @if($recentLeaveRequests->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-xs text-gray-500">Nama</th>
                                        <th class="px-4 py-2 text-xs text-gray-500">Jenis</th>
                                        <th class="px-4 py-2 text-xs text-gray-500">Status</th>
                                        <th class="px-4 py-2 text-xs text-gray-500">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentLeaveRequests as $request)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ $request->user->name }}</td>
                                        <td class="px-4 py-2 text-xs">{{ ucfirst($request->leave_type) }}</td>
                                        <td class="px-4 py-2 text-xs">
                                            @if($request->status == 'pending') <span class="text-yellow-600 font-bold">Menunggu</span>
                                            @elseif($request->status == 'approved') <span class="text-green-600 font-bold">Disetujui</span>
                                            @elseif($request->status == 'approved_by_leader') <span class="text-blue-600 font-bold">Acc Ketua</span>
                                            @else <span class="text-red-600 font-bold">Ditolak</span> @endif
                                        </td>
                                        <td class="px-4 py-2 text-sm"><a href="{{ route('leave-requests.show', $request) }}" class="text-blue-600 hover:text-blue-900">Detail</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-gray-500 text-sm">Tidak ada data.</p>
                        @endif
                    </div>
                </div>

                @if(auth()->user()->role === 'admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-orange-400">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Karyawan Baru (< 1 Tahun)</h3>
                        <ul class="divide-y divide-gray-200">
                            @forelse($newEmployees as $emp)
                            <li class="py-2 flex justify-between">
                                <span class="text-sm font-medium">{{ $emp->name }}</span>
                                <span class="text-xs text-gray-500">{{ $emp->created_at->format('d M Y') }}</span>
                            </li>
                            @empty
                            <p class="text-sm text-gray-500">Tidak ada karyawan baru.</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
                @endif

                @if(auth()->user()->role === 'hrd')
                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-teal-500">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Sedang Cuti Hari Ini</h3>
                            <ul class="divide-y divide-gray-200">
                                @forelse($employeesOnLeave as $leave)
                                <li class="py-2">
                                    <div class="text-sm font-medium">{{ $leave->user->name }}</div>
                                    <div class="text-xs text-gray-500">Sampai: {{ $leave->end_date->format('d M Y') }}</div>
                                </li>
                                @empty
                                <p class="text-sm text-gray-500">Tidak ada karyawan sedang cuti.</p>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-indigo-500">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Daftar Divisi</h3>
                            <div class="flex flex-wrap gap-2">
                                @forelse($divisionList as $div)
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold">
                                    {{ $div->division }}
                                </span>
                                @empty
                                <p class="text-sm text-gray-500">Belum ada divisi terdaftar.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
            @endif
        </div>
    </div>
</x-app-layout>