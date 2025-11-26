<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard untuk Admin
    public function dashboard()
    {
        $totalKaryawan = User::where('role', 'karyawan')->count();
        $totalPengajuan = LeaveRequest::count();
        $menungguApproval = LeaveRequest::where('status', 'pending')->count();
        $disetujui = LeaveRequest::where('status', 'approved')->count();
        $ditolak = LeaveRequest::where('status', 'rejected')->count();
        
        $recentLeaveRequests = LeaveRequest::with('user')
            ->latest()
            ->take(10)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalKaryawan',
            'totalPengajuan',
            'menungguApproval',
            'disetujui',
            'ditolak',
            'recentLeaveRequests'
        ));
    }

    // Dashboard untuk HRD
    public function hrdDashboard()
    {
        $totalKaryawan = User::where('role', 'karyawan')->count();
        $totalPengajuan = LeaveRequest::count();
        $menungguApproval = LeaveRequest::where('status', 'pending')->count();
        $disetujui = LeaveRequest::where('status', 'approved')->count();
        $ditolak = LeaveRequest::where('status', 'rejected')->count();
        
        $recentLeaveRequests = LeaveRequest::with('user')
            ->latest()
            ->take(10)
            ->get();
        
        return view('hrd.dashboard', compact(
            'totalKaryawan',
            'totalPengajuan',
            'menungguApproval',
            'disetujui',
            'ditolak',
            'recentLeaveRequests'
        ));
    }

    // List semua leave requests untuk Admin dan HRD
    public function leaveRequests()
    {
        $leaveRequests = LeaveRequest::with('user')->latest()->get();
        return view('admin.leave-requests', compact('leaveRequests'));
    }
}