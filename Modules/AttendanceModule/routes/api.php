<?php

use Illuminate\Support\Facades\Route;
use Modules\AttendanceModule\App\Http\Controllers\AttendanceModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('attendancemodules', AttendanceModuleController::class)->names('attendancemodule');
});
