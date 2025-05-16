<?php

use Illuminate\Support\Facades\Route;
use Modules\Announcements\Http\Controllers\AnnouncementsController;
use Modules\Announcements\Livewire\Admin\Announcements;
use Modules\Announcements\Livewire\Admin\CreateAnnouncement;
use Modules\Announcements\Livewire\Admin\UpdateAnnouncement;

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

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'announcements'], function () {
        Route::get('/', Announcements::class)->name('announcements')->can('admin.announcements');
        Route::get('create', CreateAnnouncement::class)->name('announcements.create')->can('admin.announcements.create');
        Route::get('update/{announcementId}', UpdateAnnouncement::class)->name('announcements.update')->can('admin.announcements.update');
    });
});
