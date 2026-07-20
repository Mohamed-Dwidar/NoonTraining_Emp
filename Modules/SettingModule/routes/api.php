<?php

use Illuminate\Support\Facades\Route;
use Modules\SettingModule\app\Http\Controllers\SettingModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('settingmodules', SettingModuleController::class)->names('settingmodule');
});
