<?php

use Illuminate\Support\Facades\Route;
use Modules\BranchModule\App\Http\Controllers\BranchModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('branchmodules', BranchModuleController::class)->names('branchmodule');
});
