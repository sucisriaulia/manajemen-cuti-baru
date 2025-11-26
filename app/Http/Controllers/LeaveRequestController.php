<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    /**
     * Menampilkan daftar riwayat cuti karyawan.
     */
    public function index()
    {
        // Mengambil data cuti milik user yang sedang login saja
        $leaveRequests = LeaveRequest::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('leave-requests.index', compact('leaveRequests'));
    }

    /**
     * Menampilkan form untuk membuat pengajuan cuti baru.
     */
    public function create()
    {
        return view('leave-requests.create');
    }

    /**
     * Menyimpan data pengajuan cuti ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input sesuai ketentuan soal (Halaman 44)
        $request->validate([
            'leave_type' => 'required|in:tahunan,sakit',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
            // Kolom baru wajib diisi sesuai soal
            'leave_address' => 'required|string|max:255',
            'emergency_contact' => 'required|string|max:20',
        ]);

        $user = Auth::user();

        // 2. Hitung Durasi Cuti (Total Hari)
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        // diffInDays menghitung selisih, tambah 1 agar hari pertama terhitung
        $totalDays = $startDate->diffInDays($endDate) + 1;

        // 3. Validasi Kuota Cuti Tahunan (Halaman 44)
        if ($request->leave_type === 'tahunan') {
            // Ambil sisa cuti user, default 12 jika belum diatur
            $currentBalance = $user->annual_leave_balance ?? 12;
            
            if ($currentBalance < $totalDays) {
                return back()->withErrors(['leave_type' => 'Sisa cuti tahunan Anda tidak mencukupi. Sisa: ' . $currentBalance . ' hari.']);
            }
        }

        // 4. Simpan ke Database
        LeaveRequest::create([
            'user_id' => $user->id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'status' => 'pending', // Status awal selalu pending
            // Data tambahan sesuai soal
            'leave_address' => $request->leave_address,
            'emergency_contact' => $request->emergency_contact,
        ]);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dikirim dan menunggu persetujuan.');
    }

    /**
     * Menampilkan detail satu pengajuan cuti.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        // Pastikan user hanya bisa melihat cutinya sendiri (kecuali admin/hrd)
        if (Auth::user()->role === 'karyawan' && $leaveRequest->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return view('leave-requests.show', compact('leaveRequest'));
    }

    // --- Fungsi Approval untuk Ketua Divisi & HRD akan kita tambahkan nanti ---
    // (Fokus Karyawan dulu)
    public function approve(Request $request, LeaveRequest $leaveRequest) { /* ... */ }
    public function reject(Request $request, LeaveRequest $leaveRequest) { /* ... */ }
}