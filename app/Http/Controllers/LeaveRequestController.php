<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf; // Library PDF

class LeaveRequestController extends Controller
{
    // 1. TAMPILKAN RIWAYAT CUTI (INDEX)
    public function index()
    {
        $leaveRequests = LeaveRequest::where('user_id', Auth::id())->latest()->get();
        return view('leave-requests.index', compact('leaveRequests'));
    }

    // 2. FORM PENGAJUAN (CREATE)
    public function create()
    {
        return view('leave-requests.create');
    }

    // 3. SIMPAN PENGAJUAN (STORE) - LOGIKA UTAMA
    public function store(Request $request)
    {
        // A. Validasi Input Dasar
        $request->validate([
            'leave_type' => 'required|in:tahunan,sakit',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:255',
            'leave_address'     => 'required|string|max:255',
            'emergency_contact' => 'required|string|max:20',
            'doctor_note' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
        ]);

        $user = Auth::user();
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // B. VALIDASI OVERLAP (Cek Bentrok Tanggal)
        // Agar user tidak bisa mengajukan cuti di tanggal yang sudah pernah diajukan
        $isOverlapped = LeaveRequest::where('user_id', $user->id)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($subQuery) use ($startDate, $endDate) {
                          $subQuery->where('start_date', '<', $startDate)
                                   ->where('end_date', '>', $endDate);
                      });
            })
            // Hanya cek yang statusnya aktif (sedang diajukan atau sudah disetujui)
            ->whereIn('status', ['pending', 'approved_by_leader', 'approved'])
            ->exists();

        if ($isOverlapped) {
            return back()->withErrors(['start_date' => 'Anda sudah memiliki pengajuan cuti pada rentang tanggal tersebut.']);
        }

        // C. Hitung Durasi Kerja (Menggunakan Model)
        $tempModel = new LeaveRequest();
        $totalDays = $tempModel->calculateWorkDays($request->start_date, $request->end_date);
        
        // D. LOGIKA KHUSUS CUTI TAHUNAN
        if ($request->leave_type === 'tahunan') {
            
            // 1. Cek Masa Kerja (< 1 Tahun Ditolak)
            if ($user->created_at > Carbon::now()->subYear()) {
                return back()->withErrors(['leave_type' => 'Maaf, masa kerja Anda belum 1 tahun. Belum berhak mengambil Cuti Tahunan.']);
            }

            // 2. Cek Aturan H-3
            $minDate = Carbon::now()->addDays(3)->startOfDay();
            if ($startDate->lt($minDate)) {
                return back()->withErrors(['start_date' => 'Cuti Tahunan wajib diajukan minimal H-3 (3 hari sebelum tanggal mulai).']);
            }

            // 3. Cek Sisa Kuota
            $currentBalance = $user->annual_leave_balance ?? 12;
            if ($currentBalance < $totalDays) {
                return back()->withErrors(['leave_type' => "Kuota tidak cukup. Sisa: $currentBalance hari, Pengajuan: $totalDays hari kerja."]);
            }
            
            // Potong Kuota
            $user->annual_leave_balance -= $totalDays;
            $user->save();
        }

        // E. LOGIKA KHUSUS CUTI SAKIT (Upload File)
        $doctorNotePath = null;
        if ($request->leave_type === 'sakit') {
            if (!$request->hasFile('doctor_note')) {
                return back()->withErrors(['doctor_note' => 'Wajib melampirkan surat dokter untuk cuti sakit.']);
            }
            $doctorNotePath = $request->file('doctor_note')->store('doctor_notes', 'public');
        }

        // F. SIMPAN KE DATABASE
        LeaveRequest::create([
            'user_id'    => $user->id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'total_days' => $totalDays,
            'reason'     => $request->reason,
            'status'     => 'pending',
            'address_during_leave' => $request->leave_address, 
            'emergency_contact'    => $request->emergency_contact,
            'doctor_note'          => $doctorNotePath,
        ]);

        return redirect()->route('leave-requests.index')->with('success', 'Pengajuan berhasil dikirim.');
    }

    // 4. FUNGSI APPROVAL (SETUJU)
    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $approver = Auth::user();

        // Logika Ketua Divisi (Level 1)
        if ($approver->role === 'ketua_divisi' && $leaveRequest->user->role === 'karyawan') {
            $leaveRequest->status = 'approved_by_leader';
            $leaveRequest->save();
            return back()->with('success', 'Disetujui Ketua Tim. Menunggu HRD.');
        }

        // Logika HRD (Level 2 / Final)
        if ($approver->role === 'hrd') {
            $leaveRequest->status = 'approved';
            $leaveRequest->save();
            return back()->with('success', 'Disetujui Final oleh HRD.');
        }

        // Logika Admin (Override - Opsional)
        if ($approver->role === 'admin') {
             $leaveRequest->status = 'approved';
             $leaveRequest->save();
             return back()->with('success', 'Disetujui oleh Admin (Override).');
        }

        return back()->withErrors(['error' => 'Akses ditolak.']);
    }

    // 5. FUNGSI REJECT (TOLAK)
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->status = 'rejected';
        $leaveRequest->rejection_reason = $request->input('rejection_reason', 'Ditolak');
        $leaveRequest->save();

        // Kembalikan Kuota (Refund) jika itu Cuti Tahunan
        if ($leaveRequest->leave_type === 'tahunan') {
            $user = $leaveRequest->user;
            $user->annual_leave_balance += $leaveRequest->total_days;
            $user->save();
        }

        return back()->with('success', 'Pengajuan ditolak.');
    }

    // 6. FUNGSI PEMBATALAN (DESTROY)
    public function destroy(LeaveRequest $leaveRequest)
    {
        // Hanya pemilik yang bisa batalkan
        if ($leaveRequest->user_id !== Auth::id()) abort(403);
        
        // Hanya status Pending yang bisa dibatalkan
        if ($leaveRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'Gagal membatalkan. Hanya status Pending yang bisa dibatalkan.']);
        }

        // Kembalikan Kuota
        if ($leaveRequest->leave_type === 'tahunan') {
            $user = Auth::user();
            $user->annual_leave_balance += $leaveRequest->total_days;
            $user->save();
        }

        // Hapus File Surat Dokter
        if ($leaveRequest->doctor_note) {
            Storage::disk('public')->delete($leaveRequest->doctor_note);
        }

        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')->with('success', 'Pengajuan berhasil dibatalkan.');
    }

    // 7. FUNGSI DETAIL (SHOW)
    public function show(LeaveRequest $leaveRequest)
    {
        return view('leave-requests.show', compact('leaveRequest'));
    }

    // 8. FUNGSI DOWNLOAD PDF
    public function downloadPdf(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'approved') {
            return back()->withErrors(['error' => 'Surat hanya bisa didownload jika status sudah Disetujui HRD.']);
        }

        $pdf = Pdf::loadView('pdf.leave_permit', compact('leaveRequest'));
        return $pdf->download('Surat_Izin_Cuti_' . $leaveRequest->user->name . '.pdf');
    }
}