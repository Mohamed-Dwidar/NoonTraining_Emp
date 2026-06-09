<?php

use Illuminate\Support\Facades\Route;
use Modules\EmployeeModule\App\Http\Controllers\Admin\EmployeeAdminController;

Route::prefix('admin/employee')->name('admin.employees.')->middleware(['auth:admin'])->group(function () {
    Route::get('/', [EmployeeAdminController::class, 'index'])->name('index');
    Route::get('create', [EmployeeAdminController::class, 'create'])->name('create');
    Route::post('/', [EmployeeAdminController::class, 'store'])->name('store');
    Route::get('edit/{id}', [EmployeeAdminController::class, 'edit'])->name('edit');
    Route::post('update', [EmployeeAdminController::class, 'update'])->name('update');
    Route::post('delete/{id}', [EmployeeAdminController::class, 'destroy'])->name('destroy');
    Route::get('departments-by-branch/{branchId}', [EmployeeAdminController::class, 'departmentsByBranch'])->name('departments-by-branch');
    Route::post('{id}/status', [EmployeeAdminController::class, 'updateStatus'])->name('update-status');
    Route::post('{id}/commission', [EmployeeAdminController::class, 'updateCommission'])->name('update-commission');
    Route::get('{id}', [EmployeeAdminController::class, 'show'])->name('show');
});
