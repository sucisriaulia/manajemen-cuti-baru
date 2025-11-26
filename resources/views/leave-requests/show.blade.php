<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pengajuan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Status Badge -->
                    <div class="mb-6">
                        @if($leaveRequest->status == 'pending')
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Menunggu Approval
                            </span>
                        @elseif($leaveRequest->status == 'approved')
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Disetujui
                            </span>
                        @else
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Ditolak
                            </span>
                        @endif
                    </div>

                    <!-- Informasi Cuti -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Karyawan</label>
                            <p class="mt-1 text-base text-gray-900">{{ $leaveRequest->user->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Cuti</label>
                            <p class="mt-1 text-base text-gray-900">{{ ucfirst($leaveRequest->leave_type) }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                <p class="mt-1 text-base text-gray-900">{{ $leaveRequest->start_date->format('d F Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                                <p class="mt-1 text-base text-gray-900">{{ $leaveRequest->end_date->format('d F Y') }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total Hari</label>
                            <p class="mt-1 text-base text-gray-900">{{ $leaveRequest->total_days }} hari kerja</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alasan Cuti</label>
                            <p class="mt-1 text-base text-gray-900">{{ $leaveRequest->reason }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat Selama Cuti</label>
                            <p class="mt-1 text-base text-gray-900">{{ $leaveRequest->address_during_leave }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kontak Darurat</label>
                            <p class="mt-1 text-base text-gray-900">{{ $leaveRequest->emergency_contact }}</p>
                        </div>

                        @if($leaveRequest->status == 'rejected' && $leaveRequest->rejection_reason)
                            <div class="p-4 bg-red-50 rounded-lg">
                                <label class="block text-sm font-medium text-red-700">Alasan Penolakan</label>
                                <p class="mt-1 text-base text-red-900">{{ $leaveRequest->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Tombol Kembali -->
                    <div class="mt-6">
                        <a href="{{ route('leave-requests.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>