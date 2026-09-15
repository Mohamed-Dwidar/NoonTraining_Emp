<?php

use Illuminate\Support\Facades\Route;
use Modules\PayrollModule\App\Http\Controllers\Admin\PayrollAdminController;
use Modules\PayrollModule\App\Http\Controllers\PayrollModuleController;

Route::prefix('admin/payroll')->name('admin.payrolls.')->middleware(['auth:admin'])->group(function () {
    Route::get('/',                       [PayrollAdminController::class, 'index'])->name('index');
    Route::get('/{id}/payslip',           [PayrollAdminController::class, 'payslip'])->name('payslip');
    Route::get('/{id}/payslip/print',     [PayrollAdminController::class, 'payslipPrint'])->name('payslip.print');
    Route::get('/{id}/details',           [PayrollAdminController::class, 'payrollDetails'])->name('details');
    Route::get('/{id}/details/pdf',       [PayrollAdminController::class, 'payrollDetailsPdf'])->name('details.pdf');
});

Route::prefix('employee/payroll')->name('employee.payrolls.')->middleware(['auth:employee'])->group(function () {
    Route::get('/',                       [PayrollModuleController::class, 'myPayrolls'])->name('index');
    Route::get('/{id}/payslip',           [PayrollModuleController::class, 'myPayslip'])->name('payslip');
    Route::get('/{id}/payslip/print',     [PayrollModuleController::class, 'myPayslipPrint'])->name('payslip.print');
    Route::get('/{id}/details',           [PayrollModuleController::class, 'myPayrollDetails'])->name('details');
    Route::get('/{id}/details/pdf',       [PayrollModuleController::class, 'myPayrollDetailsPdf'])->name('details.pdf');
});
