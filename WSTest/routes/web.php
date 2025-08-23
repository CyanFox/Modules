<?php

use Illuminate\Support\Facades\Route;
use Modules\WSTest\Events\AuthTestEvent;
use Modules\WSTest\Events\TestEvent;
use Modules\WSTest\Livewire\WSTest;
use Modules\WSTest\Livewire\WSTestAuth;

Route::middleware('auth')->group(function () {
    Route::get('/wstest/auth', WSTestAuth::class)->name('wstest.auth');

    Route::get('/wstest/send/auth', function () {
        AuthTestEvent::dispatch();

        return response()->json(['status' => 'Auth Event dispatched successfully']);
    });
});

Route::get('/wstest', WSTest::class)->name('wstest');

Route::get('/wstest/send', function () {
    TestEvent::dispatch();

    return response()->json(['status' => 'Event dispatched successfully']);
});

Broadcast::channel('Modules.Auth.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('wstest-auth', function ($user) {
    return auth()->check();
});
