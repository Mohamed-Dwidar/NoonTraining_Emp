<?php

use Illuminate\Support\Facades\Route;
use Modules\BonuseModule\Http\Controllers\BonuseModuleController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('bonusemodules', BonuseModuleController::class)->names('bonusemodule');
});
