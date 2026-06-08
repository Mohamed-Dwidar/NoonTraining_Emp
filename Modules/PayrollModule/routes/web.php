<?php

use Illuminate\Support\Facades\Route;
use Modules\PayrollModule\App\Http\Controllers\Admin\PayrollAdminController;

Route::prefix('admin/payroll')->name('admin.payrolls.')->middleware(['auth:admin'])->group(function () {
    Route::get('/',    [PayrollAdminController::class, 'index'])->name('index');
});
