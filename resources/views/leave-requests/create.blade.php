<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Pengajuan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong class="font-bold">Gagal!</strong> Periksa inputan Anda:<br>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('leave-requests.store') }}">
                        @csrf <div class="mb-4">
                            <label for="leave_type" class="block text-gray-700 text-sm font-bold mb-2">Jenis Cuti</label>
                            <select name="leave_type" id="leave_type" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="tahunan">Cuti Tahunan (Mengurangi Kuota)</option>
                                <option value="sakit">Cuti Sakit (Perlu Surat Dokter)</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </div>
                            <div>
                                <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end_date" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="leave_address" class="block text-gray-700 text-sm font-bold mb-2">Alamat Selama Cuti</label>
                            <textarea name="leave_address" id="leave_address" rows="2" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Masukkan alamat lengkap Anda selama menjalani cuti..." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="emergency_contact" class="block text-gray-700 text-sm font-bold mb-2">Nomor Telepon Darurat</label>
                            <input type="text" name="emergency_contact" id="emergency_contact" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Contoh: 0812xxxx (Istri/Orang Tua)" required>
                        </div>

                        <div class="mb-6">
                            <label for="reason" class="block text-gray-700 text-sm font-bold mb-2">Alasan Pengajuan</label>
                            <textarea name="reason" id="reason" rows="3" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('leave-requests.index') }}" class="text-gray-500 hover:text-gray-700 mr-4 font-bold">Batal</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Kirim Pengajuan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>