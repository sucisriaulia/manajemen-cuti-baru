<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; 

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::where('user_id', Auth::id())->latest()->get();
        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        return view('leave-requests.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'leave_type' => 'required|in:tahunan,sakit',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:255',
            'leave_address'     => 'required|string|max:255', 
            'emergency_contact' => 'required|string|max:20',
            // Validasi Surat Dokter: Wajib jika sakit, boleh kosong jika tahunan
            'doctor_note' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', 
        ]);

        $user = Auth::user();
        
        // Hitung Hari Kerja
        $tempModel = new LeaveRequest();
        $totalDays = $tempModel->calculateWorkDays($request->start_date, $request->end_date);
        
        if ($totalDays == 0) {
             // Bisa tambahkan error jika ingin melarang cuti di hari libur
        }

        // 2. Cek Aturan Cuti Tahunan
        if ($request->leave_type === 'tahunan') {
            // Cek H-3
            $minDate = Carbon::now()->addDays(3);
            if (Carbon::parse($request->start_date)->lt($minDate)) {
                return back()->withErrors(['start_date' => 'Cuti Tahunan wajib diajukan minimal H-3.']);
            }

            // Cek Kuota
            $currentBalance = $user->annual_leave_balance ?? 12;
            if ($currentBalance < $totalDays) {
                return back()->withErrors(['leave_type' => "Kuota tidak cukup. Sisa: $currentBalance, Pengajuan: $totalDays hari kerja."]);
            }
            
            // Kurangi Kuota
            $user->annual_leave_balance -= $totalDays;
            $user->save();
        }

        // 3. Proses Upload File (Cuti Sakit)
        $doctorNotePath = null;
        if ($request->leave_type === 'sakit') {
            if (!$request->hasFile('doctor_note')) {
                return back()->withErrors(['doctor_note' => 'Wajib melampirkan surat dokter untuk cuti sakit.']);
            }
            // Simpan file ke folder 'public/doctor_notes'
            $doctorNotePath = $request->file('doctor_note')->store('doctor_notes', 'public');
        }

        // 4. Simpan ke Database
        LeaveRequest::create([
            'user_id'    => $user->id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'total_days' => $totalDays,
            'reason'     => $request->reason,
            'status'     => 'pending',
            
            // MAPPING: Form 'leave_address' masuk ke Kolom 'address_during_leave'
            'address_during_leave' => $request->leave_address, 
            'emergency_contact'    => $request->emergency_contact,
            'doctor_note'          => $doctorNotePath,
        ]);

        return redirect()->route('leave-requests.index')->with('success', 'Pengajuan berhasil dikirim.');
    }

    // Fungsi Approval
    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $approver = Auth::user();

        // ALUR 1: Ketua Divisi
        if ($approver->role === 'ketua_divisi' && $leaveRequest->user->role === 'karyawan') {
            $leaveRequest->status = 'approved_by_leader';
            $leaveRequest->save();
            return back()->with('success', 'Disetujui Ketua Tim. Menunggu HRD.');
        }

        // ALUR 2: HRD
        if ($approver->role === 'hrd') {
            $leaveRequest->status = 'approved';
            $leaveRequest->save();
            return back()->with('success', 'Persetujuan Final berhasil.');
        }

        return back()->withErrors(['error' => 'Akses ditolak.']);
    }

    // Fungsi Reject
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->status = 'rejected';
        $leaveRequest->rejection_reason = $request->input('rejection_reason', 'Ditolak');
        $leaveRequest->save();

        // Kembalikan Kuota jika Cuti Tahunan
        if ($leaveRequest->leave_type === 'tahunan') {
            $user = $leaveRequest->user;
            $user->annual_leave_balance += $leaveRequest->total_days;
            $user->save();
        }

        return back()->with('success', 'Pengajuan ditolak.');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        return view('leave-requests.show', compact('leaveRequest'));
    }
}