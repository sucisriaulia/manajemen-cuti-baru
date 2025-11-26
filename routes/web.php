<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// --- PERBAIKAN ROUTE DASHBOARD ---
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    // 1. Jika Admin, arahkan ke route khusus admin
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } 
    // 2. Jika HRD, arahkan ke route khusus hrd
    elseif ($user->role === 'hrd') {
        return redirect()->route('hrd.dashboard');
    }
    
    // 3. Jika Ketua Divisi ATAU Karyawan:
    // Panggil function dashboard() di AdminController.
    // Ini PENTING agar logika judul, statistik, dan filter divisi berjalan.
    return app(AdminController::class)->dashboard();
    
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Leave Request Routes untuk Karyawan
    Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave-requests.create');
    Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');
    Route::get('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'show'])->name('leave-requests.show');
    
    // Admin Routes
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/leave-requests', [AdminController::class, 'leaveRequests'])->name('admin.leave-requests');
        Route::post('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('admin.approve');
        Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('admin.reject');
    });
    
    // HRD Routes
    Route::prefix('hrd')->middleware('hrd')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('hrd.dashboard');
        Route::get('/leave-requests', [AdminController::class, 'leaveRequests'])->name('hrd.leave-requests');
        Route::post('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('hrd.approve');
        Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('hrd.reject');
    });

    // Ketua Divisi Routes
    Route::prefix('ketua-divisi')->group(function () {
        Route::get('/leave-requests', [AdminController::class, 'leaveRequests'])->name('ketua-divisi.leave-requests');
        Route::post('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('ketua-divisi.approve');
        Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('ketua-divisi.reject');
    });
});

require __DIR__.'/auth.php';