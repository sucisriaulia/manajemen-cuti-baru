<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Hari Libur & Cuti Bersama') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Tambah Hari Libur</h3>
                    
                    @if(session('success'))
                        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ $errors->first() }}</div>
                    @endif

                    <form action="{{ route('holidays.store') }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
                        @csrf
                        <div class="flex-1 w-full">
                            <label class="block text-sm font-bold text-gray-700">Nama Libur</label>
                            <input type="text" name="name" class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Tahun Baru Islam" required>
                        </div>
                        <div class="w-full md:w-auto">
                            <label class="block text-sm font-bold text-gray-700">Tanggal</label>
                            <input type="date" name="holiday_date" class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded font-bold hover:bg-blue-700 w-full md:w-auto">Simpan</button>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Daftar Hari Libur</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($holidays as $holiday)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-800">
                                        {{ $holiday->holiday_date->format('d F Y') }}
                                        <span class="text-xs text-gray-500 block">{{ $holiday->holiday_date->format('l') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $holiday->name }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:text-red-900 text-sm font-bold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-8 text-gray-500">Belum ada data hari libur.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>