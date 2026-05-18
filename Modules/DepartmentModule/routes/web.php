<?php

use Illuminate\Support\Facades\Route;
use Modules\DepartmentModule\App\Http\Controllers\Admin\DepartmentAdminController;

Route::prefix('admin/department')->name('admin.departments.')->middleware(['auth:admin'])->group(function () {
    Route::get('/', [DepartmentAdminController::class, 'index'])->name('index');
    Route::get('create', [DepartmentAdminController::class, 'create'])->name('create');
    Route::post('/', [DepartmentAdminController::class, 'store'])->name('store');
    Route::get('{id}', [DepartmentAdminController::class, 'show'])->name('show');
    Route::get('edit/{id}', [DepartmentAdminController::class, 'edit'])->name('edit');
    Route::post('update', [DepartmentAdminController::class, 'update'])->name('update');
    Route::post('delete/{id}', [DepartmentAdminController::class, 'destroy'])->name('destroy');
});
