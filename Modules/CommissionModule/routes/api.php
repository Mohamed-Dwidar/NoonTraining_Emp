<?php

use Illuminate\Support\Facades\Route;
use Modules\CommissionModule\App\Http\Controllers\CommissionModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('commissionmodules', CommissionModuleController::class)->names('commissionmodule');
});
