<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-[#17a2b8] rounded shadow text-white p-4 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-3xl font-bold">{{ $sisaCutis ?? $totalKaryawan }}</h3>
                <p class="text-sm uppercase font-semibold opacity-80">
                    {{ auth()->user()->role == 'karyawan' ? 'Sisa Kuota Cuti' : 'Total Karyawan' }}
                </p>
            </div>
            <div class="absolute right-2 top-2 opacity-20">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
            </div>
        </div>

        <div class="bg-[#ffc107] rounded shadow text-white p-4 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-3xl font-bold">{{ $menungguApproval ?? 0 }}</h3>
                <p class="text-sm uppercase font-semibold opacity-80">Menunggu Approval</p>
            </div>
            <div class="absolute right-2 top-2 opacity-20">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
            </div>
        </div>

        <div class="bg-[#28a745] rounded shadow text-white p-4 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-3xl font-bold">{{ $disetujui ?? 0 }}</h3>
                <p class="text-sm uppercase font-semibold opacity-80">Disetujui</p>
            </div>
            <div class="absolute right-2 top-2 opacity-20">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            </div>
        </div>

        <div class="bg-[#dc3545] rounded shadow text-white p-4 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-3xl font-bold">{{ $ditolak ?? 0 }}</h3>
                <p class="text-sm uppercase font-semibold opacity-80">Ditolak</p>
            </div>
            <div class="absolute right-2 top-2 opacity-20">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white rounded-sm shadow-sm border-t-4 border-blue-500 p-6 flex flex-col items-center text-center">
            
            @if(auth()->user()->avatar)
                <img class="h-24 w-24 rounded-full object-cover border-4 border-gray-200 mb-4" src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
            @else
                <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center text-3xl font-bold text-gray-500 mb-4 border-4 border-gray-300">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            @endif

            <h3 class="text-xl font-bold text-gray-800">{{ auth()->user()->name }}</h3>
            <p class="text-sm text-gray-500 mb-4">{{ auth()->user()->email }}</p>

            <a href="{{ route('profile.edit') }}" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">
                EDIT PROFILE
            </a>
        </div>

        <div class="lg:col-span-2 bg-white rounded-sm shadow-sm p-0 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="font-bold text-gray-700">Profil Saya</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 bg-gray-50 w-1/3 text-sm font-bold text-gray-600">Nama Lengkap</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ auth()->user()->name }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 bg-gray-50 w-1/3 text-sm font-bold text-gray-600">Divisi</td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">
                                {{ auth()->user()->division ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 bg-gray-50 w-1/3 text-sm font-bold text-gray-600">Kontak (HP)</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ auth()->user()->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 bg-gray-50 w-1/3 text-sm font-bold text-gray-600">Alamat</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ auth()->user()->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 bg-gray-50 w-1/3 text-sm font-bold text-gray-600">Role Akun</td>
                        <td class="px-6 py-4 text-sm text-gray-800 uppercase">{{ auth()->user()->role }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>