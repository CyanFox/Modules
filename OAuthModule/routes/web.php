<?php

use Illuminate\Support\Facades\Route;
use Modules\OAuthModule\app\Services\OAuthService;
use Modules\OAuthModule\Http\Controllers\OAuthModuleController;

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

Route::group(['prefix' => 'oauth', 'as' => 'oauth.'], function() {
    Route::get('{provider}/redirect', [OAuthService::class, 'redirectToProvider'])->name('redirect');

    if (setting('oauthmodule.authentik.enable')) {
        Route::get('authentik/callback', [OAuthService::class, 'handleAuthentikCallback'])->name('auth.authentik.callback'); // Not tested yet
    }
    if (setting('oauthmodule.github.enable')) {
        Route::get('github/callback', [OAuthService::class, 'handleGitHubCallback'])->name('github.callback');
    }
    if (setting('oauthmodule.discord.enable')) {
        Route::get('discord/callback', [OAuthService::class, 'handleDiscordCallback'])->name('discord.redirect');
    }
    if (setting('oauthmodule.google.enable')) {
        Route::get('google/callback', [OAuthService::class, 'handleGoogleCallback'])->name('google.callback'); // Not tested yet
    }

});
