<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajukan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($leaveQuota)
                        <!-- Info Kuota Cuti -->
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                            <h3 class="font-semibold text-lg mb-2">Kuota Cuti Tahun {{ date('Y') }}</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Total Kuota</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ $leaveQuota->total_days }} hari</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Sudah Digunakan</p>
                                    <p class="text-2xl font-bold text-orange-600">{{ $leaveQuota->used_days }} hari</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Sisa Kuota</p>
                                    <p class="text-2xl font-bold text-green-600">{{ $leaveQuota->remaining_days }} hari</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Pengajuan Cuti -->
                        <form method="POST" action="{{ route('leave-requests.store') }}">
                            @csrf

                            <!-- Jenis Cuti -->
                            <div class="mb-4">
                                <label for="leave_type" class="block text-sm font-medium text-gray-700">Jenis Cuti</label>
                                <select id="leave_type" name="leave_type" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="tahunan">Cuti Tahunan</option>
                                    <option value="sakit">Cuti Sakit</option>
                                </select>
                                @error('leave_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Mulai -->
                            <div class="mb-4">
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                <input type="date" id="start_date" name="start_date" required
                                    min="{{ date('Y-m-d') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Selesai -->
                            <div class="mb-4">
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                                <input type="date" id="end_date" name="end_date" required
                                    min="{{ date('Y-m-d') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alasan Cuti -->
                            <div class="mb-4">
                                <label for="reason" class="block text-sm font-medium text-gray-700">Alasan Cuti</label>
                                <textarea id="reason" name="reason" rows="3" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('reason')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alamat Selama Cuti -->
                            <div class="mb-4">
                                <label for="address_during_leave" class="block text-sm font-medium text-gray-700">Alamat Selama Cuti</label>
                                <textarea id="address_during_leave" name="address_during_leave" rows="2" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('address_during_leave')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kontak Darurat -->
                            <div class="mb-4">
                                <label for="emergency_contact" class="block text-sm font-medium text-gray-700">Kontak Darurat</label>
                                <input type="text" id="emergency_contact" name="emergency_contact" required
                                    placeholder="Contoh: 08123456789"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('emergency_contact')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tombol Submit -->
                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('dashboard') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Ajukan Cuti
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-yellow-800 mb-4">Belum ada kuota cuti untuk tahun ini. Silakan hubungi HR.</p>
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>