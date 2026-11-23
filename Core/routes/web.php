<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::passkeys();

Broadcast::channel('Modules.Core.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
