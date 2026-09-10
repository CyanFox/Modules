<?php

use Illuminate\Support\Facades\Route;
use Modules\Testing\Http\Controllers\TestingController;

Route::resource('testings', TestingController::class)->names('testing');
