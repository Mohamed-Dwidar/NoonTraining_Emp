<?php

use Illuminate\Support\Facades\Route;
use Modules\AttendanceModule\App\Http\Controllers\Admin\AttendanceAdminController;

Route::prefix('admin/attendance')->name('admin.attendances.')->middleware(['auth:admin'])->group(function () {
    Route::get('/',    [AttendanceAdminController::class, 'index'])->name('index');
    Route::post('/',   [AttendanceAdminController::class, 'store'])->name('store');
});
