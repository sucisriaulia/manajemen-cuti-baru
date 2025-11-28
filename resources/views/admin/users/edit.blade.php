<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="w-full border-gray-300 rounded shadow-sm" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="w-full border-gray-300 rounded shadow-sm" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru (Opsional)</label>
                                <input type="password" name="password" class="w-full border-gray-300 rounded shadow-sm" placeholder="Kosongkan jika tidak ubah">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                                <select name="role" class="w-full border-gray-300 rounded shadow-sm">
                                    <option value="karyawan" {{ $user->role == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                    <option value="ketua_divisi" {{ $user->role == 'ketua_divisi' ? 'selected' : '' }}>Ketua Divisi</option>
                                    <option value="hrd" {{ $user->role == 'hrd' ? 'selected' : '' }}>HRD</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Divisi</label>
                                <select name="division" class="w-full border-gray-300 rounded shadow-sm">
                                    <option value="">-- Pilih --</option>
                                    <option value="IT" {{ $user->division == 'IT' ? 'selected' : '' }}>IT</option>
                                    <option value="Finance" {{ $user->division == 'Finance' ? 'selected' : '' }}>Finance</option>
                                    <option value="Marketing" {{ $user->division == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                    <option value="HRD" {{ $user->division == 'HRD' ? 'selected' : '' }}>HRD</option>
                                    <option value="Operasional" {{ $user->division == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jatah Cuti Tahunan</label>
                            <input type="number" name="annual_leave_balance" value="{{ $user->annual_leave_balance }}" class="w-full border-gray-300 rounded shadow-sm">
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('users.index') }}" class="text-gray-500 mr-4 mt-2">Batal</a>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>