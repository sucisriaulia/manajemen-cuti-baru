<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\HolidayController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// --- ROUTE DASHBOARD ---
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'hrd') {
        return redirect()->route('hrd.dashboard');
    }
    
    return app(AdminController::class)->dashboard();
})->middleware(['auth', 'verified'])->name('dashboard');

// --- GRUP MIDDLEWARE AUTH ---
Route::middleware('auth')->group(function () {
    
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // --- KARYAWAN (Leave Requests) ---
    Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave-requests.create');
    Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');
    Route::get('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'show'])->name('leave-requests.show');
    Route::delete('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'destroy'])->name('leave-requests.destroy');
    Route::get('/leave-requests/{leaveRequest}/pdf', [LeaveRequestController::class, 'downloadPdf'])->name('leave-requests.pdf');
    
    // --- ADMIN ROUTES ---
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/leave-requests', [AdminController::class, 'leaveRequests'])->name('admin.leave-requests');
        Route::post('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('admin.approve');
        Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('admin.reject');
        
        // Fitur Assign Divisi
        Route::patch('/users/{user}/assign-division', [AdminController::class, 'assignDivision'])->name('admin.assign-division');
        
        // Resource Controllers
        Route::resource('users', UserController::class);
        Route::resource('divisions', DivisionController::class);
        
        // Perhatikan baris ini yang tadi error (Pastikan lengkap)
        Route::resource('holidays', HolidayController::class)->only(['index', 'store', 'destroy']);
    });
    
    // --- HRD ROUTES ---
    Route::prefix('hrd')->middleware('hrd')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('hrd.dashboard');
        Route::get('/leave-requests', [AdminController::class, 'leaveRequests'])->name('hrd.leave-requests');
        Route::post('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('hrd.approve');
        Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('hrd.reject');
    });

    // --- KETUA DIVISI ROUTES ---
    Route::prefix('ketua-divisi')->group(function () {
        Route::get('/leave-requests', [AdminController::class, 'leaveRequests'])->name('ketua_divisi.leave-requests');
        Route::post('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('ketua_divisi.approve');
        Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('ketua_divisi.reject');
    });

}); // Tutup Middleware Auth

require __DIR__.'/auth.php';