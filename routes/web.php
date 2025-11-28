<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// --- ROUTE DASHBOARD (LOGIKA SENTRAL) ---
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    // 1. Jika Admin -> Redirect ke route admin
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } 
    // 2. Jika HRD -> Redirect ke route hrd
    elseif ($user->role === 'hrd') {
        return redirect()->route('hrd.dashboard');
    }
    
    // 3. Jika Ketua Divisi ATAU Karyawan -> Panggil Controller Dashboard
    return app(AdminController::class)->dashboard();
    
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Leave Request Routes (Karyawan)
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
        
        // --- TAMBAHAN BARU: Route untuk Admin memilih Divisi User ---
        Route::patch('/users/{user}/assign-division', [AdminController::class, 'assignDivision'])->name('admin.assign-division');
        Route::resource('users', UserController::class);
        Route::resource('divisions', \App\Http\Controllers\DivisionController::class);
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
        Route::get('/leave-requests', [AdminController::class, 'leaveRequests'])->name('ketua_divisi.leave-requests');
        Route::post('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('ketua_divisi.approve');
        Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('ketua_divisi.reject');
    });
});

require __DIR__.'/auth.php';