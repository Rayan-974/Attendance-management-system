<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role.student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::post('/attendance/mark', [StudentController::class, 'markAttendance'])->name('attendance.mark');
    Route::post('/leave/submit', [StudentController::class, 'submitLeave'])->name('leave.submit');
    Route::post('/task/{task}/response', [StudentController::class, 'submitTaskResponse'])->name('task.response');
});

use App\Http\Controllers\AdminController;

Route::middleware(['auth', 'role.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::patch('/leave/{leave}/status', [AdminController::class, 'updateLeaveStatus'])->name('leave.status');
    
    Route::get('/student/{student}/attendance', [AdminController::class, 'manageAttendance'])->name('student.attendance');
    Route::post('/student/{student}/attendance', [AdminController::class, 'storeAttendance'])->name('student.attendance.store');
    Route::delete('/attendance/{attendance}', [AdminController::class, 'deleteAttendance'])->name('attendance.delete');

    Route::get('/task/create', [AdminController::class, 'createTask'])->name('task.create');
    Route::post('/task', [AdminController::class, 'storeTask'])->name('task.store');
    Route::patch('/task/{task}/status', [AdminController::class, 'updateTaskStatus'])->name('task.status');

    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
