<?php

use Illuminate\Support\Facades\Route;
use Modules\WSTest\Http\Controllers\WSTestController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('wstests', WSTestController::class)->names('wstest');
});
