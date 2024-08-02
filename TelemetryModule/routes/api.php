<?php

use Illuminate\Support\Facades\Route;
use Modules\TelemetryModule\Http\Controllers\TelemetryController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::group(['prefix' => 'v1/telemetry', 'middleware' => ['throttle:1,60'], 'domain' => setting('telemetrymodule.domains.api')], function () {
    Route::post('/', [TelemetryController::class, 'store']);
    Route::get('{instanceId}/delete', [TelemetryController::class, 'delete']);
});
