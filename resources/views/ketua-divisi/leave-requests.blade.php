<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelola Pengajuan Cuti Divisi {{ auth()->user()->division }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($leaveRequests->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($leaveRequests as $request)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $request->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $request->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $request->leave_type == 'tahunan' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                    {{ ucfirst($request->leave_type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $request->start_date->format('d/m/Y') }}<br>
                                                {{ $request->end_date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $request->total_days }} hari
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($request->status == 'pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Menunggu
                                                    </span>
                                                @elseif($request->status == 'approved')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Disetujui
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Ditolak
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium space-x-2">
                                                <a href="{{ route('leave-requests.show', $request) }}" 
                                                    class="text-blue-600 hover:text-blue-900">Detail</a>
                                                
                                                @if($request->status == 'pending')
                                                    <form method="POST" action="{{ route('ketua-divisi.approve', $request) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                            onclick="return confirm('Setujui pengajuan cuti ini?')"
                                                            class="text-green-600 hover:text-green-900">
                                                            Setujui
                                                        </button>
                                                    </form>
                                                    
                                                    <button onclick="showRejectModal({{ $request->id }})" 
                                                        class="text-red-600 hover:text-red-900">
                                                        Tolak
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Belum ada pengajuan cuti dari divisi {{ auth()->user()->division }}.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-semibold mb-4">Alasan Penolakan</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <textarea name="rejection_reason" required rows="4" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Masukkan alasan penolakan..."></textarea>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="closeRejectModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showRejectModal(id) {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectForm').action = `/ketua-divisi/leave-requests/${id}/reject`;
        }
        
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
</x-app-layout>