<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin and HR routes
    Route::middleware('role:admin,hr')->group(function () {
        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/', [EmployeeController::class, 'index'])->name('index');
            Route::get('/create', [EmployeeController::class, 'create'])->name('create');
            Route::post('/', [EmployeeController::class, 'store'])->name('store');
            Route::get('/{employee}', [EmployeeController::class, 'show'])->name('show');
            Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
            Route::patch('/{employee}', [EmployeeController::class, 'update'])->name('update');
            Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('departments')->name('departments.')->group(function () {
            Route::get('/', [DepartmentController::class, 'index'])->name('index');
            Route::get('/create', [DepartmentController::class, 'create'])->name('create');
            Route::post('/', [DepartmentController::class, 'store'])->name('store');
            Route::get('/{department}', [DepartmentController::class, 'show'])->name('show');
            Route::get('/{department}/edit', [DepartmentController::class, 'edit'])->name('edit');
            Route::patch('/{department}', [DepartmentController::class, 'update'])->name('update');
            Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/employees', [ReportController::class, 'employeeReport'])->name('employees');
            Route::get('/attendance', [ReportController::class, 'attendanceReport'])->name('attendance');
            Route::get('/leaves', [ReportController::class, 'leaveReport'])->name('leaves');
            Route::get('/departments', [ReportController::class, 'departmentReport'])->name('departments');
            Route::get('/activity', [ReportController::class, 'activityReport'])->name('activity');
            Route::get('/{type}', [ReportController::class, 'show'])->name('show');
        });
    });

    // Manager and HR routes - Leave request approval
    Route::middleware('role:admin,hr,manager')->group(function () {
        Route::prefix('leave-requests')->name('leave-requests.')->group(function () {
            Route::patch('/{leaveRequest}/status', [LeaveRequestController::class, 'updateStatus'])->name('update-status');
        });
    });

    // Admin and HR - Attendance marking
    Route::middleware('role:admin,hr')->group(function () {
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::post('/mark', [AttendanceController::class, 'markAttendance'])->name('mark');
        });
    });

    // Employee routes
    Route::middleware('role:admin,hr,manager,employee')->group(function () {
        // Attendance
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('index');
            Route::post('/time-in', [AttendanceController::class, 'timeIn'])->name('time-in');
            Route::post('/time-out', [AttendanceController::class, 'timeOut'])->name('time-out');
        });

        // Leave requests - submit
        Route::prefix('leave-requests')->name('leave-requests.')->group(function () {
            Route::get('/', [LeaveRequestController::class, 'index'])->name('index');
            Route::get('/create', [LeaveRequestController::class, 'create'])->name('create');
            Route::post('/', [LeaveRequestController::class, 'store'])->name('store');
            Route::get('/{leaveRequest}', [LeaveRequestController::class, 'show'])->name('show');
            Route::patch('/{leaveRequest}/cancel', [LeaveRequestController::class, 'cancel'])->name('cancel');
        });
    });
});

require __DIR__.'/auth.php';