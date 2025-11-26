<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard HRD
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <!-- Total Karyawan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-1">Total Karyawan</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $totalKaryawan }}</p>
                    </div>
                </div>

                <!-- Total Pengajuan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-1">Total Pengajuan</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalPengajuan }}</p>
                    </div>
                </div>

                <!-- Menunggu Approval -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-1">Menunggu Approval</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $menungguApproval }}</p>
                    </div>
                </div>

                <!-- Disetujui -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-1">Disetujui</p>
                        <p class="text-3xl font-bold text-green-600">{{ $disetujui }}</p>
                    </div>
                </div>

                <!-- Ditolak -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-1">Ditolak</p>
                        <p class="text-3xl font-bold text-red-600">{{ $ditolak }}</p>
                    </div>
                </div>
            </div>

            <!-- Pengajuan Cuti Terbaru Divisi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Pengajuan Cuti Divisi {{ auth()->user()->division }}</h3>
                        <a href="{{ route('hrd.leave-requests') }}" 
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Semua →
                        </a>
                    </div>

                    @if($recentLeaveRequests->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentLeaveRequests as $request)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $request->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $request->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $request->leave_type == 'tahunan' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                    {{ ucfirst($request->leave_type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $request->start_date->format('d/m/Y') }} - {{ $request->end_date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $request->total_days }} hari
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($request->status == 'pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Menunggu
                                                    </span>
                                                @elseif($request->status == 'approved')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Disetujui
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Ditolak
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('leave-requests.show', $request) }}" class="text-blue-600 hover:text-blue-900">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Belum ada pengajuan cuti.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>