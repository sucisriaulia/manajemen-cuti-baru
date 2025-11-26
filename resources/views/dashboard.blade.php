<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Pesan Error/Success -->
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Kuota Cuti Tahunan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Kuota Cuti Tahunan {{ date('Y') }}</h3>
                    
                    @if(auth()->user()->leaveQuota)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Total Pengajuan -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Total Pengajuan</p>
                                <p class="text-3xl font-bold text-gray-800">
                                    {{ auth()->user()->leaveRequests()->count() }}
                                </p>
                            </div>

                            <!-- Menunggu Approval -->
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Menunggu Approval</p>
                                <p class="text-3xl font-bold text-yellow-600">
                                    {{ auth()->user()->leaveRequests()->where('status', 'pending')->count() }}
                                </p>
                            </div>

                            <!-- Disetujui -->
                            <div class="bg-green-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Disetujui</p>
                                <p class="text-3xl font-bold text-green-600">
                                    {{ auth()->user()->leaveRequests()->where('status', 'approved')->count() }}
                                </p>
                            </div>
                        </div>

                        <!-- Progress Bar Kuota -->
                        <div class="mt-6">
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Sisa Kuota Cuti</span>
                                <span class="text-sm font-medium text-gray-700">
                                    {{ auth()->user()->leaveQuota->remaining_days }} / {{ auth()->user()->leaveQuota->total_days }} hari
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-blue-600 h-4 rounded-full" 
                                    style="width: {{ (auth()->user()->leaveQuota->remaining_days / auth()->user()->leaveQuota->total_days) * 100 }}%">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-yellow-800">Belum ada kuota cuti untuk tahun ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Ajukan Cuti -->
                        <a href="{{ route('leave-requests.create') }}" 
                            class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-150">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-semibold text-gray-900">Ajukan Cuti</p>
                                <p class="text-sm text-gray-600">Buat pengajuan cuti baru</p>
                            </div>
                        </a>

                        <!-- Riwayat Cuti -->
                        <a href="{{ route('leave-requests.index') }}" 
                            class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition duration-150">
                            <div class="flex-shrink-0 w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-semibold text-gray-900">Riwayat Cuti</p>
                                <p class="text-sm text-gray-600">Lihat semua pengajuan cuti</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>