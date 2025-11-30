<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Detail Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="bg-gray-50 px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center text-2xl font-bold text-indigo-600 border-2 border-white shadow-sm">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700">
                        {{ str_replace('_', ' ', $user->role) }}
                    </span>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        
                        <div class="border-b border-gray-100 pb-4">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Divisi</p>
                            <p class="text-gray-800 font-medium">{{ $user->division ?? '-' }}</p>
                        </div>

                        <div class="border-b border-gray-100 pb-4">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Sisa Cuti Tahunan</p>
                            <p class="text-gray-800 font-medium">{{ $user->annual_leave_balance }} Hari</p>
                        </div>

                        <div class="border-b border-gray-100 pb-4">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Nomor Telepon</p>
                            <p class="text-gray-800 font-medium">{{ $user->phone ?? '-' }}</p>
                        </div>

                        <div class="border-b border-gray-100 pb-4">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Bergabung Sejak</p>
                            <p class="text-gray-800 font-medium">{{ $user->created_at->format('d F Y') }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Alamat</p>
                            <p class="text-gray-800 font-medium">{{ $user->address ?? '-' }}</p>
                        </div>

                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('users.index') }}" class="text-gray-500 font-bold text-sm hover:text-gray-700 px-4">
                            Kembali
                        </a>
                        <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg text-sm font-bold shadow transition">
                            Edit User
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>