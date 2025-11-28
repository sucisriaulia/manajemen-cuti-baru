<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Divisi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('divisions.update', $division->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Divisi</label>
                            <input type="text" name="name" value="{{ $division->name }}" class="w-full border-gray-300 rounded shadow-sm" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Ketua Divisi</label>
                            <select name="manager_id" class="w-full border-gray-300 rounded shadow-sm" required>
                                @if($division->manager)
                                    <option value="{{ $division->manager_id }}" selected>{{ $division->manager->name }} (Saat Ini)</option>
                                @else
                                    <option value="" disabled selected>-- Pilih Ketua Baru --</option>
                                @endif

                                @foreach($candidates as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                            <textarea name="description" rows="3" class="w-full border-gray-300 rounded shadow-sm">{{ $division->description }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('divisions.index') }}" class="text-gray-500 mr-4 mt-2 font-bold">Batal</a>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-bold">Update Divisi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>