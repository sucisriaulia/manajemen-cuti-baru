<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Tambah User Baru') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm">
                            <strong class="font-bold">Gagal!</strong> Silakan periksa kembali input Anda.
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-green-500 focus:ring focus:ring-green-200" required>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-green-500 focus:ring focus:ring-green-200" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                                <input type="password" name="password" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-green-500 focus:ring focus:ring-green-200" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-green-500 focus:ring focus:ring-green-200" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Role</label>
                                <select name="role" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                                    <option value="karyawan">Karyawan</option>
                                    <option value="ketua_divisi">Ketua Divisi</option>
                                    <option value="hrd">HRD</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Divisi (Opsional)</label>
                                <select name="division" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                                    <option value="">-- Pilih --</option>
                                    <option value="IT">IT</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="HRD">HRD</option>
                                    <option value="Operasional">Operasional</option>
                                </select>
                            </div>
                        </div>

                        <div class="pt-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jatah Cuti Tahunan (Hari)</label>
                            <input type="number" name="annual_leave_balance" value="12" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                        </div>

                        <div class="flex justify-end pt-4">
                            <a href="{{ route('users.index') }}" class="text-gray-500 mr-4 mt-2 font-bold hover:text-gray-700">Batal</a>
                            <button type="submit" class="bg-green-700 text-white px-6 py-3 rounded-xl shadow-md hover:bg-green-800 transition font-bold">Simpan User Baru</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>