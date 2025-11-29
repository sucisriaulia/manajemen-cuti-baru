<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-green-900 leading-tight">
                {{ __('Manajemen Pengguna') }}
            </h2>
            
            <a href="{{ route('users.create') }}" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg transform hover:-translate-y-0.5 transition duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah User Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="p-5 text-xs font-extrabold text-gray-500 uppercase tracking-wider">Nama & Email</th>
                                <th class="p-5 text-xs font-extrabold text-gray-500 uppercase tracking-wider">Role (Jabatan)</th>
                                <th class="p-5 text-xs font-extrabold text-gray-500 uppercase tracking-wider">Divisi</th>
                                <th class="p-5 text-xs font-extrabold text-gray-500 uppercase tracking-wider text-center">Sisa Cuti</th>
                                <th class="p-5 text-xs font-extrabold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        
                        <tbody class="divide-y divide-gray-100">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                
                                <td class="p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-5">
                                    @php
                                        $colors = [
                                            'admin' => 'bg-gray-800 text-white',
                                            'hrd' => 'bg-purple-100 text-purple-700',
                                            'ketua_divisi' => 'bg-blue-100 text-blue-700',
                                            'karyawan' => 'bg-green-100 text-green-700'
                                        ];
                                        $colorClass = $colors[$user->role] ?? 'bg-gray-100 text-gray-600';
                                        $roleName = ucfirst(str_replace('_', ' ', $user->role));
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $colorClass }}">
                                        {{ $roleName }}
                                    </span>
                                </td>

                                <td class="p-5">
                                    @if($user->division)
                                        <span class="text-sm font-medium text-gray-700 bg-gray-100 px-2 py-1 rounded border border-gray-200">
                                            {{ $user->division }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">- Belum Ada -</span>
                                    @endif
                                </td>

                                <td class="p-5 text-center">
                                    <span class="font-bold text-gray-800">{{ $user->annual_leave_balance }}</span> 
                                    <span class="text-xs text-gray-500">Hari</span>
                                </td>

                                <td class="p-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('users.show', $user->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>

                                        <a href="{{ route('users.edit', $user->id) }}" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition" title="Edit User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>

                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition" title="Hapus User">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.86 11.23A2 2 0 0116.14 21H7.86a2 2 0 01-1.99-1.77L5 7m1 0h12M9 7v-3a1 1 0 011-1h4a1 1 0 011 1v3m-6 0h6"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500">Menampilkan seluruh pengguna terdaftar.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>