<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Detail Divisi: ') }} {{ $division->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6 flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $division->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $division->description ?? 'Tidak ada deskripsi' }}</p>
                    <div class="mt-4 flex items-center gap-3">
                        <span class="text-xs font-bold text-gray-400 uppercase">Ketua Divisi:</span>
                        @if($division->manager)
                            <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold">{{ $division->manager->name }}</span>
                        @else
                            <span class="text-red-500 text-xs italic">Belum ditentukan</span>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-3xl font-bold text-gray-800">{{ $division->members->count() }}</span>
                    <p class="text-xs text-gray-500 uppercase font-bold">Total Anggota</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Daftar Anggota</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-xs">
                            <tr>
                                <th class="p-4">Nama Anggota</th>
                                <th class="p-4">Email</th>
                                <th class="p-4">Role</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($division->members as $member)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 font-medium text-gray-900">{{ $member->name }}</td>
                                <td class="p-4">{{ $member->email }}</td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded text-xs font-bold uppercase bg-gray-100 text-gray-600">
                                        {{ str_replace('_', ' ', $member->role) }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <form action="{{ route('divisions.remove-member', $member->id) }}" method="POST" onsubmit="return confirm('Keluarkan {{ $member->name }} dari divisi ini?');">
                                        @csrf @method('PATCH')
                                        <button class="text-red-600 hover:text-red-800 font-bold text-xs border border-red-200 px-3 py-1 rounded hover:bg-red-50 transition">
                                            Keluarkan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-400">
                                    Belum ada anggota di divisi ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-6">
                <a href="{{ route('divisions.index') }}" class="text-gray-500 font-bold text-sm hover:underline">&larr; Kembali ke Daftar Divisi</a>
            </div>

        </div>
    </div>
</x-app-layout>