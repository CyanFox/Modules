<?php

use Illuminate\Support\Facades\Route;
use Modules\Actions\app\Livewire\Admin\Actions\Actions;

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

Route::group(['prefix' => 'admin', 'middleware' => ['role:Super Admin', 'auth', 'disabled']], function () {
    Route::get('actions', Actions::class)->name('admin.actions');
});
