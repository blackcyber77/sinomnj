<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StoreLeaderController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::middleware('role:owner')->prefix('owner')->name('owner.')->group(function (): void {
        Route::get('/staff', [OwnerController::class, 'staffIndex'])->name('staff.index');
        Route::post('/staff', [OwnerController::class, 'staffStore'])->name('staff.store');
        Route::put('/staff/{staff}', [OwnerController::class, 'staffUpdate'])->name('staff.update');
        Route::patch('/staff/{staff}/credentials', [OwnerController::class, 'staffUpdateCredentials'])->name('staff.credentials');
        Route::delete('/staff/{staff}', [OwnerController::class, 'staffDelete'])->name('staff.delete');

        Route::get('/kpi-variables', [OwnerController::class, 'kpiIndex'])->name('kpi.index');
        Route::post('/kpi-variables', [OwnerController::class, 'kpiStore'])->name('kpi.store');
        Route::put('/kpi-variables/{variable}', [OwnerController::class, 'kpiUpdate'])->name('kpi.update');

        Route::get('/validations', [OwnerController::class, 'validations'])->name('validations.index');
        Route::patch('/validations/expenses/{expense}', [OwnerController::class, 'validateExpense'])->name('validations.expense');
        Route::patch('/validations/kpi/{entry}', [OwnerController::class, 'validateKpi'])->name('validations.kpi');

        Route::get('/finance', [OwnerController::class, 'financeReport'])->name('finance');
        Route::get('/sales-analytics', [OwnerController::class, 'salesAnalytics'])->name('sales-analytics');
        Route::get('/menu-variants', [OwnerController::class, 'menuVariantsIndex'])->name('menu-variants.index');
        Route::post('/menu-variants', [OwnerController::class, 'menuVariantsStore'])->name('menu-variants.store');
        Route::delete('/menu-variants/{variant}', [OwnerController::class, 'menuVariantsDelete'])->name('menu-variants.delete');

        Route::get('/payroll', [OwnerController::class, 'payrollIndex'])->name('payroll.index');
        Route::post('/payroll/generate', [OwnerController::class, 'payrollGenerate'])->name('payroll.generate');
    });

    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function (): void {
        Route::get('/attendance', [StaffController::class, 'attendance'])->name('attendance');
        Route::post('/attendance/check-in', [StaffController::class, 'checkIn'])->name('attendance.checkin');
        Route::post('/attendance/check-out', [StaffController::class, 'checkOut'])->name('attendance.checkout');

        Route::get('/shifts', [StaffController::class, 'shifts'])->name('shifts');
        Route::post('/shifts', [StaffController::class, 'shiftStore'])->name('shifts.store');

        Route::get('/expenses', [StaffController::class, 'expenses'])->name('expenses');
        Route::post('/expenses', [StaffController::class, 'expenseStore'])->name('expenses.store');

        Route::get('/kpi', [StaffController::class, 'kpi'])->name('kpi');
        Route::post('/kpi', [StaffController::class, 'kpiStore'])->name('kpi.store');

        Route::get('/store-leader', [StoreLeaderController::class, 'index'])->name('store-leader');
        Route::post('/store-leader/quantities', [StoreLeaderController::class, 'quantitiesUpsert'])->name('store-leader.quantities');
        Route::delete('/store-leader/items/{item}', [StoreLeaderController::class, 'itemDelete'])->name('store-leader.items.delete');
    });
});
