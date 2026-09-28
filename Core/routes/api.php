<?php

declare(strict_types=1);

Route::group(['prefix' => 'v1/core',], function () {
    Route::get('/', fn() => response()->json(['message' => 'Core API']));
});
