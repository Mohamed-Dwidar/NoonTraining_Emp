<?php

use Illuminate\Support\Facades\Route;
use Modules\EmployeeModule\App\Http\Controllers\Admin\EmployeeAdminController;
use Modules\EmployeeModule\App\Http\Controllers\EmployeeModuleController;

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

// Route::prefix('employee')->group(function () {
//     Route::get('/', [EmployeeModuleController::class, 'dashboard'])->name('employee.dashboard');
// });


Route::group(['prefix' => 'employee', 'middleware' => ['auth:employee']], function () {
    Route::get('/', [EmployeeModuleController::class, 'dashboard'])->name('employee.dashboard');
    Route::get('dashboard', [EmployeeModuleController::class, 'dashboard'])->name('employee.employee_dashboard');
    Route::get('logout', [EmployeeModuleController::class, 'logout'])->name('employee.logout');
    Route::get('changePassword', [EmployeeModuleController::class, 'changePassword'])->name('employee.changePassword');
    Route::post('updatePassword', [EmployeeModuleController::class, 'updatePassword'])->name('employee.updatePassword');
});
