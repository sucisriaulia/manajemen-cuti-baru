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
        $request->validate([
            'leave_type' => 'required|in:tahunan,sakit',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:255',
            'leave_address'     => 'required|string|max:255',
            'emergency_contact' => 'required|string|max:20',
            'doctor_note' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();
        
        $tempModel = new LeaveRequest();
        $totalDays = $tempModel->calculateWorkDays($request->start_date, $request->end_date);
        
        if ($request->leave_type === 'tahunan') {
            $minDate = Carbon::now()->addDays(3);
            if (Carbon::parse($request->start_date)->lt($minDate)) {
                return back()->withErrors(['start_date' => 'Cuti Tahunan wajib diajukan minimal H-3.']);
            }
            $currentBalance = $user->annual_leave_balance ?? 12;
            if ($currentBalance < $totalDays) {
                return back()->withErrors(['leave_type' => "Kuota tidak cukup. Sisa: $currentBalance, Pengajuan: $totalDays hari kerja."]);
            }
            $user->annual_leave_balance -= $totalDays;
            $user->save();
        }

        $doctorNotePath = null;
        if ($request->leave_type === 'sakit') {
            if (!$request->hasFile('doctor_note')) {
                return back()->withErrors(['doctor_note' => 'Wajib melampirkan surat dokter.']);
            }
            $doctorNotePath = $request->file('doctor_note')->store('doctor_notes', 'public');
        }

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

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $approver = Auth::user();

        if ($approver->role === 'ketua_divisi' && $leaveRequest->user->role === 'karyawan') {
            $leaveRequest->status = 'approved_by_leader';
            $leaveRequest->save();
            return back()->with('success', 'Disetujui Ketua Tim.');
        }

        if ($approver->role === 'hrd') {
            $leaveRequest->status = 'approved';
            $leaveRequest->save();
            return back()->with('success', 'Disetujui Final oleh HRD.');
        }

        return back()->withErrors(['error' => 'Akses ditolak.']);
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->status = 'rejected';
        $leaveRequest->rejection_reason = $request->input('rejection_reason', 'Ditolak');
        $leaveRequest->save();

        if ($leaveRequest->leave_type === 'tahunan') {
            $user = $leaveRequest->user;
            $user->annual_leave_balance += $leaveRequest->total_days;
            $user->save();
        }

        return back()->with('success', 'Pengajuan ditolak.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->user_id !== Auth::id()) abort(403);
        if ($leaveRequest->status !== 'pending') return back()->withErrors(['error' => 'Gagal batalkan.']);

        if ($leaveRequest->leave_type === 'tahunan') {
            $user = Auth::user();
            $user->annual_leave_balance += $leaveRequest->total_days;
            $user->save();
        }

        if ($leaveRequest->doctor_note) Storage::disk('public')->delete($leaveRequest->doctor_note);
        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')->with('success', 'Dibatalkan.');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        return view('leave-requests.show', compact('leaveRequest'));
    }

    // --- FUNGSI BARU: DOWNLOAD PDF ---
    public function downloadPdf(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'approved') {
            return back()->withErrors(['error' => 'Surat hanya bisa didownload jika status sudah Disetujui HRD.']);
        }

        $pdf = Pdf::loadView('pdf.leave_permit', compact('leaveRequest'));
        return $pdf->download('Surat_Izin_Cuti_' . $leaveRequest->user->name . '.pdf');
    }
}