<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    {{ __('Manajemen Divisi') }}
                </h2>
                <p class="text-slate-500 text-sm mt-1">Kelola struktur organisasi dan ketua divisi.</p>
            </div>
            
            <a href="{{ route('divisions.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-indigo-500/30 transform hover:-translate-y-1 transition duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Buat Divisi Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between animate-fade-in-down">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="p-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Nama Divisi</th>
                                <th class="p-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Ketua Divisi</th>
                                <th class="p-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Kode</th>
                                <th class="p-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Deskripsi</th>
                                <th class="p-5 text-xs font-extrabold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        
                        <tbody class="divide-y divide-slate-100">
                            @foreach($divisions as $div)
                            <tr class="hover:bg-slate-50 transition duration-150">
                                
                                <td class="p-5">
                                    <span class="font-bold text-slate-800 text-base">{{ $div->name }}</span>
                                </td>

                                <td class="p-5">
                                    @if($div->manager)
                                        <div class="flex items-center gap-2">
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs border border-indigo-200">
                                                {{ substr($div->manager->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-indigo-900">{{ $div->manager->name }}</p>
                                                <p class="text-xs text-slate-500">{{ $div->manager->email }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600 border border-red-200">
                                            Belum Ada Ketua
                                        </span>
                                    @endif
                                </td>

                                <td class="p-5">
                                    <code class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-mono border border-slate-200">
                                        {{ $div->code ?? strtoupper(substr($div->name, 0, 3)) }}
                                    </code>
                                </td>

                                <td class="p-5">
                                    <p class="text-sm text-slate-500 truncate max-w-xs">{{ $div->description ?? '-' }}</p>
                                </td>

                                <td class="p-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        
                                        <a href="{{ route('divisions.show', $div->id) }}" class="p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-indigo-600 hover:text-white transition" title="Lihat Anggota">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>

                                        <a href="{{ route('divisions.edit', $div->id) }}" class="p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-amber-500 hover:text-white transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>

                                        <form action="{{ route('divisions.destroy', $div->id) }}" method="POST" onsubmit="return confirm('Yakin hapus divisi ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-rose-600 hover:text-white transition" title="Hapus">
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
            </div>
        </div>
    </div>
</x-app-layout>