<?php

use Illuminate\Support\Facades\Route;
use Modules\TelemetryModule\Http\Controllers\TelemetryController;
use Modules\TelemetryModule\Livewire\Telemetry;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['domain' => setting('telemetrymodule.domains.dashboard')], function () {
    if (setting('telemetrymodule.use_auth')) {
        Route::middleware(['web', 'auth'])->group(function () {
            Route::get('telemetry', Telemetry::class)->name('telemetry');
        });
    } else {
        Route::middleware(['web'])->group(function () {
            Route::get('telemetry', Telemetry::class)->name('telemetry');
        });
    }
});
