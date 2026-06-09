<?php

use Illuminate\Support\Facades\Route;
use Modules\SettingModule\app\Http\Controllers\Admin\SettingAdminController;

Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('settings',        [SettingAdminController::class, 'index'])->name('admin.settings.index');
    // Route::get('settings/create', [SettingAdminController::class, 'create'])->name('admin.settings.create');
    // Route::post('settings',       [SettingAdminController::class, 'store'])->name('admin.settings.store');
    Route::post('settings/update',[SettingAdminController::class, 'update'])->name('admin.settings.update');
});
