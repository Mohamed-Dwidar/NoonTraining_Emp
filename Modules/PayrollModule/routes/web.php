<?php

use Illuminate\Support\Facades\Route;
use Modules\PayrollModule\App\Http\Controllers\Admin\PayrollAdminController;

Route::prefix('admin/payroll')->name('admin.payrolls.')->middleware(['auth:admin'])->group(function () {
    Route::get('/',                       [PayrollAdminController::class, 'index'])->name('index');
    Route::get('/{id}/payslip',           [PayrollAdminController::class, 'payslip'])->name('payslip');
    Route::get('/{id}/payslip/print',     [PayrollAdminController::class, 'payslipPrint'])->name('payslip.print');
    Route::get('/{id}/details',           [PayrollAdminController::class, 'payrollDetails'])->name('details');
    Route::get('/{id}/details/pdf',       [PayrollAdminController::class, 'payrollDetailsPdf'])->name('details.pdf');
});
