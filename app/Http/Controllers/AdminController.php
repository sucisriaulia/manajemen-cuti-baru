<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        // --- 1. LOGIKA JUDUL DINAMIS (TETAP ADA) ---
        $title = 'Dashboard';
        if ($user->role === 'admin') $title = 'Dashboard Administrator';
        elseif ($user->role === 'hrd') $title = 'Dashboard HRD Manager';
        elseif ($user->role === 'ketua_divisi') $title = 'Dashboard Ketua Divisi ' . $user->division;
        else $title = 'Dashboard Karyawan';

        // ==========================================================
        // 2. LOGIKA DASHBOARD KARYAWAN (TETAP ADA)
        // ==========================================================
        if ($user->role === 'karyawan') {
            $sisaCutis = $user->annual_leave_balance ?? 12;
            $totalCutiSakit = LeaveRequest::where('user_id', $user->id)->where('leave_type', 'sakit')->count();
            $totalPengajuan = LeaveRequest::where('user_id', $user->id)->count();
            
            // Cari Ketua Divisi
            $ketuaDivisiObj = User::where('division', $user->division)->where('role', 'ketua_divisi')->first();
            $namaKetua = $ketuaDivisiObj ? $ketuaDivisiObj->name : '- Belum ada Ketua -';

            return view('admin.dashboard', compact('title', 'sisaCutis', 'totalCutiSakit', 'totalPengajuan', 'namaKetua'));
        }

        // ==========================================================
        // 3. LOGIKA UTAMA (ADMIN / HRD / KETUA DIVISI)
        // ==========================================================
        
        $leavesQuery = LeaveRequest::query();
        $employeesQuery = User::where('role', 'karyawan');

        // Filter Divisi (Jika Ketua Divisi)
        if ($user->role !== 'admin' && $user->role !== 'hrd') {
            $leavesQuery->whereHas('user', function($q) use ($user) {
                $q->where('division', $user->division);
            });
            $employeesQuery->where('division', $user->division);
        }

        // Statistik Umum
        $totalKaryawan = $employeesQuery->count();
        
        // Total Pengajuan (HRD & Admin melihat BULAN INI sesuai soal)
        if ($user->role === 'admin' || $user->role === 'hrd') {
            $totalPengajuan = LeaveRequest::whereMonth('created_at', Carbon::now()->month)->count();
        } else {
            $totalPengajuan = (clone $leavesQuery)->count();
        }

        $menungguApproval = (clone $leavesQuery)->where('status', 'pending')->count();
        $disetujui = (clone $leavesQuery)->where('status', 'approved')->count();
        $ditolak = (clone $leavesQuery)->where('status', 'rejected')->count();
        
        $recentLeaveRequests = $leavesQuery->with('user')->latest()->take(5)->get();

        // ==========================================================
        // 4. DATA TAMBAHAN SESUAI ROLE
        // ==========================================================
        
        // Variabel Default (Supaya tidak error jika role beda)
        $totalDivisi = 0;
        $newEmployees = collect([]);
        $employeesOnLeave = collect([]);
        $divisionList = collect([]);

        // A. KHUSUS ADMIN (TETAP ADA)
        if ($user->role === 'admin') {
            $totalDivisi = User::whereNotNull('division')->distinct('division')->count('division');
            $newEmployees = User::where('role', 'karyawan')
                ->where('created_at', '>', Carbon::now()->subYear())
                ->latest()->take(5)->get();
        }

        // B. KHUSUS HRD (DITAMBAHKAN BARU)
        if ($user->role === 'hrd') {
            // Daftar Karyawan Sedang Cuti (Hari ini ada di antara start dan end date)
            $employeesOnLeave = LeaveRequest::with('user')
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', Carbon::now())
                ->whereDate('end_date', '>=', Carbon::now())
                ->get();

            // Daftar Semua Divisi
            $divisionList = User::select('division')
                ->whereNotNull('division')
                ->where('division', '!=', '')
                ->distinct()
                ->get();
        }

        return view('admin.dashboard', compact(
            'title',
            'totalKaryawan',
            'totalPengajuan',
            'menungguApproval',
            'disetujui',
            'ditolak',
            'recentLeaveRequests',
            // Data Admin
            'totalDivisi',
            'newEmployees',
            // Data HRD
            'employeesOnLeave',
            'divisionList'
        ));
    }

    public function leaveRequests()
    {
        $user = auth()->user();
        $query = LeaveRequest::with('user')->latest();

        if ($user->role !== 'admin' && $user->role !== 'hrd') {
            $query->whereHas('user', function($q) use ($user) {
                $q->where('division', $user->division);
            });
        }

        $leaveRequests = $query->get();
        return view('admin.leave-requests', compact('leaveRequests'));
    }
}