<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        // --- 1. JUDUL DINAMIS ---
        $title = 'Dashboard';
        if ($user->role === 'admin') $title = 'Dashboard Administrator';
        elseif ($user->role === 'hrd') $title = 'Dashboard HRD Manager';
        elseif ($user->role === 'ketua_divisi') $title = 'Dashboard Ketua Divisi ' . $user->division;
        else $title = 'Dashboard Karyawan';

        // --- 2. VARIABEL DEFAULT (Agar tidak error undefined) ---
        $sisaCutis = 0;
        $totalCutiSakit = 0;
        $namaKetua = '-';
        $recentLeaveRequests = collect([]); // Default kosong agar tidak error
        $totalKaryawan = 0;
        $totalPengajuan = 0;
        $menungguApproval = 0;
        $disetujui = 0;
        $ditolak = 0;
        $totalDivisi = 0;
        $newEmployees = collect([]);
        $employeesOnLeave = collect([]);
        $divisionList = collect([]);
        $divisionMembers = collect([]);
        $onLeaveThisWeek = collect([]);

        // ==========================================================
        // 3. LOGIKA KARYAWAN
        // ==========================================================
        if ($user->role === 'karyawan') {
            $sisaCutis = $user->annual_leave_balance ?? 12;
            $totalCutiSakit = LeaveRequest::where('user_id', $user->id)->where('leave_type', 'sakit')->count();
            $totalPengajuan = LeaveRequest::where('user_id', $user->id)->count();
            
            $ketuaDivisiObj = User::where('division', $user->division)->where('role', 'ketua_divisi')->first();
            $namaKetua = $ketuaDivisiObj ? $ketuaDivisiObj->name : '- Belum ada Ketua -';
        } 
        
        // ==========================================================
        // 4. LOGIKA ADMIN / HRD / KETUA DIVISI (JIKA BUKAN KARYAWAN)
        // ==========================================================
        else {
            $leavesQuery = LeaveRequest::query();
            $employeesQuery = User::where('role', 'karyawan');

            // Filter Divisi (Jika Ketua Divisi)
            if ($user->role === 'ketua_divisi') {
                $leavesQuery->whereHas('user', function($q) use ($user) {
                    $q->where('division', $user->division);
                });
                $employeesQuery->where('division', $user->division);
            }

            // Statistik Umum
            $totalKaryawan = $employeesQuery->count();
            
            // Total Pengajuan
            if ($user->role === 'admin' || $user->role === 'hrd') {
                $totalPengajuan = LeaveRequest::whereMonth('created_at', Carbon::now()->month)->count();
            } else {
                $totalPengajuan = (clone $leavesQuery)->count();
            }

            $menungguApproval = (clone $leavesQuery)->where('status', 'pending')->count();
            $disetujui = (clone $leavesQuery)->where('status', 'approved')->count();
            $ditolak = (clone $leavesQuery)->where('status', 'rejected')->count();
            
            // --- INI YANG TADI ERROR: KITA ISI DATANYA ---
            $recentLeaveRequests = $leavesQuery->with('user')->latest()->take(5)->get();
        }

        // ==========================================================
        // 5. DATA SPESIFIK PER ROLE
        // ==========================================================
        
        // A. KHUSUS ADMIN
        if ($user->role === 'admin') {
            // Cek apakah class Division ada, jika tidak hitung manual dari User
            if (class_exists(Division::class)) {
                $totalDivisi = Division::count(); 
            } else {
                $totalDivisi = User::whereNotNull('division')->distinct('division')->count('division');
            }
            
            $newEmployees = User::where('role', 'karyawan')
                ->where('created_at', '>', Carbon::now()->subYear())
                ->latest()->take(5)->get();
        }

        // B. KHUSUS HRD
        if ($user->role === 'hrd') {
            $employeesOnLeave = LeaveRequest::with('user')
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', Carbon::now())
                ->whereDate('end_date', '>=', Carbon::now())
                ->get();

            if (class_exists(Division::class)) {
                $divisionList = Division::all();
            }
        }

        // C. KHUSUS KETUA DIVISI
        if ($user->role === 'ketua_divisi') {
            $divisionMembers = User::where('division', $user->division)
                ->where('role', 'karyawan')
                ->get();

            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $onLeaveThisWeek = LeaveRequest::with('user')
                ->whereHas('user', function($q) use ($user) {
                    $q->where('division', $user->division);
                })
                ->where('status', 'approved')
                ->where(function($query) use ($startOfWeek, $endOfWeek) {
                    $query->whereBetween('start_date', [$startOfWeek, $endOfWeek])
                          ->orWhereBetween('end_date', [$startOfWeek, $endOfWeek]);
                })
                ->get();
        }

        // ==========================================================
        // 6. KIRIM SEMUA KE VIEW
        // ==========================================================
        return view('admin.dashboard', compact(
            'title',
            'sisaCutis', 'totalCutiSakit', 'namaKetua', // Data Karyawan
            'totalKaryawan', 'totalPengajuan', 'menungguApproval', 'disetujui', 'ditolak', 'recentLeaveRequests', // Data Umum
            'totalDivisi', 'newEmployees', // Data Admin
            'employeesOnLeave', 'divisionList', // Data HRD
            'divisionMembers', 'onLeaveThisWeek' // Data Ketua
        ));
    }

    public function leaveRequests()
    {
        $user = auth()->user();
        $query = LeaveRequest::with('user')->latest();

        if ($user->role === 'ketua_divisi') {
            $query->whereHas('user', function($q) use ($user) {
                $q->where('division', $user->division);
            });
        }

        $leaveRequests = $query->get();
        return view('admin.leave-requests', compact('leaveRequests'));
    }

    // Fungsi Assign Divisi untuk Admin
    public function assignDivision(Request $request, User $user)
    {
        $request->validate([
            'division' => 'required|string'
        ]);

        $user->division = $request->division;
        $user->save();

        return back()->with('success', "Berhasil memasukkan {$user->name} ke divisi {$request->division}.");
    }
}