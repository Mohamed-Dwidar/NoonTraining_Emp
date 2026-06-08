<?php

use Illuminate\Support\Facades\Route;
use Modules\PayrollModule\Http\Controllers\PayrollModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('payrollmodules', PayrollModuleController::class)->names('payrollmodule');
});
