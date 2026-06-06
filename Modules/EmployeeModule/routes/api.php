<?php

use Illuminate\Support\Facades\Route;
use Modules\EmployeeModule\Http\Controllers\EmployeeModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('employeemodules', EmployeeModuleController::class)->names('employeemodule');
});
