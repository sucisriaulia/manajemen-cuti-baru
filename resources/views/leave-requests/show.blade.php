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
                    
                    <div class="flex justify-between items-start mb-6 border-b pb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ $leaveRequest->user->name }}</h3>
                            <p class="text-sm text-gray-500">Divisi: {{ $leaveRequest->user->division ?? '-' }}</p>
                            <p class="text-sm text-gray-500">Email: {{ $leaveRequest->user->email }}</p>
                        </div>
                        
                        <div class="text-right">
                            <p class="text-xs text-gray-400 mb-1 uppercase tracking-wider font-bold">Status Pengajuan</p>
                            
                            @if($leaveRequest->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-bold text-sm">Menunggu Verifikasi</span>
                            @elseif($leaveRequest->status == 'approved_by_leader')
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-bold text-sm">Disetujui Ketua Tim</span>
                            @elseif($leaveRequest->status == 'approved')
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-bold text-sm">Disetujui HRD (Final)</span>
                                
                                <div class="mt-3">
                                    <a href="{{ route('leave-requests.pdf', $leaveRequest->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Cetak Surat Izin
                                    </a>
                                </div>

                            @else
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full font-bold text-sm">Ditolak</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xs font-bold text-gray-500 uppercase">Jenis Cuti</p>
                            <p class="text-lg font-medium text-gray-900">{{ ucfirst($leaveRequest->leave_type) }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xs font-bold text-gray-500 uppercase">Total Durasi</p>
                            <p class="text-lg font-medium text-gray-900">{{ $leaveRequest->total_days }} Hari Kerja</p>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-gray-500">Tanggal Mulai</p>
                            <p class="text-gray-800 font-medium">{{ $leaveRequest->start_date->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Tanggal Selesai</p>
                            <p class="text-gray-800 font-medium">{{ $leaveRequest->end_date->format('d F Y') }}</p>
                        </div>

                        <div class="col-span-1 md:col-span-2 border-t pt-4 mt-2">
                            <h4 class="font-bold text-gray-700 mb-3">Informasi Kontak Selama Cuti</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">Nomor Darurat</p>
                                    <p class="text-gray-800">{{ $leaveRequest->emergency_contact }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500">Alamat</p>
                                    <p class="text-gray-800">{{ $leaveRequest->address_during_leave }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <p class="text-sm font-semibold text-gray-500 mb-1">Alasan Cuti</p>
                            <div class="bg-gray-50 p-4 rounded border border-gray-200 text-gray-700 italic">
                                "{{ $leaveRequest->reason }}"
                            </div>
                        </div>

                        @if($leaveRequest->leave_type === 'sakit')
                        <div class="col-span-1 md:col-span-2 border-t pt-4">
                            <p class="text-sm font-semibold text-gray-500 mb-2">Bukti Surat Dokter</p>
                            
                            @if($leaveRequest->doctor_note)
                                <div class="flex items-center p-4 bg-indigo-50 border border-indigo-100 rounded-lg">
                                    <svg class="w-8 h-8 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-indigo-900">File Surat Dokter Terlampir</p>
                                        <p class="text-xs text-indigo-600">Klik tombol di kanan untuk melihat/unduh.</p>
                                    </div>

                                    <a href="{{ asset('storage/' . $leaveRequest->doctor_note) }}" target="_blank" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 text-sm font-bold transition">
                                        Lihat Surat
                                    </a>
                                </div>
                            @else
                                <div class="p-4 bg-red-50 border border-red-100 rounded-lg text-red-600 text-sm">
                                    ⚠ Tidak ada file surat dokter yang diunggah.
                                </div>
                            @endif
                        </div>
                        @endif

                        @if($leaveRequest->status == 'rejected' && $leaveRequest->rejection_reason)
                        <div class="col-span-1 md:col-span-2 mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm font-bold text-red-700 uppercase">Alasan Penolakan:</p>
                            <p class="text-red-600 mt-1">{{ $leaveRequest->rejection_reason }}</p>
                        </div>
                        @endif

                    </div>

                    <div class="mt-8 border-t pt-6 flex justify-end">
                        <a href="{{ url()->previous() }}" class="bg-gray-200 text-gray-700 px-5 py-2 rounded font-bold hover:bg-gray-300 transition">
                            &larr; Kembali
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>