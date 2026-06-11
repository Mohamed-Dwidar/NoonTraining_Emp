<?php

use Illuminate\Support\Facades\Route;
use Modules\StudentModule\App\Http\Controllers\Admin\StudentAdminController;
use Modules\StudentModule\App\Http\Controllers\Employee\StudentEmployeeController;

Route::prefix('admin/student')->name('admin.students.')->middleware(['auth:admin'])->group(function () {
    Route::get('/',          [StudentAdminController::class, 'index'])->name('index');
    Route::get('/create',    [StudentAdminController::class, 'create'])->name('create');
    Route::post('/',         [StudentAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [StudentAdminController::class, 'edit'])->name('edit');
    Route::post('/{id}',     [StudentAdminController::class, 'update'])->name('update');
    Route::delete('/{id}',   [StudentAdminController::class, 'destroy'])->name('destroy');
    Route::post('/students/import', [StudentAdminController::class, 'importStudents'])->name('import');
});

Route::prefix('employee/student')->name('employee.students.')->middleware(['auth:employee'])->group(function () {
    Route::get('/',          [StudentEmployeeController::class, 'index'])->name('index');
    Route::get('/{id}/edit', [StudentEmployeeController::class, 'edit'])->name('edit');
    Route::post('/{id}',     [StudentEmployeeController::class, 'update'])->name('update');
    Route::delete('/{id}',   [StudentEmployeeController::class, 'destroy'])->name('destroy');
    Route::post('/students/import', [StudentEmployeeController::class, 'importStudents'])->name('import');
});
