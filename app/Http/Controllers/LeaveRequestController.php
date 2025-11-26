<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveQuota;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $leaveRequests = $user->leaveRequests()->latest()->get();
        
        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        $user = auth()->user();
        $leaveQuota = $user->leaveQuota;
        
        return view('leave-requests.create', compact('leaveQuota'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type' => 'required|in:tahunan,sakit',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'address_during_leave' => 'required|string',
            'emergency_contact' => 'required|string',
        ]);

        $user = auth()->user();
        $leaveQuota = $user->leaveQuota;

        // Hitung total hari kerja
        $totalDays = $this->calculateWorkDays($validated['start_date'], $validated['end_date']);

        // Cek apakah kuota cukup
        if ($leaveQuota->remaining_days < $totalDays) {
            return back()->with('error', 'Kuota cuti tidak mencukupi. Sisa kuota: ' . $leaveQuota->remaining_days . ' hari.');
        }

        // Buat leave request
        LeaveRequest::create([
            'user_id' => $user->id,
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'address_during_leave' => $validated['address_during_leave'],
            'emergency_contact' => $validated['emergency_contact'],
            'status' => 'pending',
        ]);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil diajukan!');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        // Pastikan user hanya bisa melihat cuti miliknya sendiri
        if ($leaveRequest->user_id !== auth()->id()) {
            abort(403);
        }

        return view('leave-requests.show', compact('leaveRequest'));
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update(['status' => 'approved']);

        // Update kuota cuti
        $leaveQuota = $leaveRequest->user->leaveQuota;
        $leaveQuota->used_days += $leaveRequest->total_days;
        $leaveQuota->remaining_days -= $leaveRequest->total_days;
        $leaveQuota->save();

        return back()->with('success', 'Pengajuan cuti berhasil disetujui!');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Pengajuan cuti ditolak.');
    }

    private function calculateWorkDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $workDays = 0;

        while ($start->lte($end)) {
            // Hitung hanya hari Senin-Jumat
            if ($start->isWeekday()) {
                $workDays++;
            }
            $start->addDay();
        }

        return $workDays;
    }
}