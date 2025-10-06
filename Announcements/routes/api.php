<?php

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

use Illuminate\Support\Facades\Route;
use Modules\Announcements\app\Http\Controllers\AnnouncementsController;

Route::group(['middleware' => ['api_key'], 'prefix' => 'v1/announcements'], function () {
    Route::get('/', [AnnouncementsController::class, 'getUserAnnouncements'])->name('announcements.user.view');
    Route::patch('dismiss/{announcementId}', [AnnouncementsController::class, 'dismissAnnouncement'])->name('announcements.dismiss');

    Route::prefix('admin')->group(function () {
        Route::get('/', [AnnouncementsController::class, 'getAnnouncements'])->name('announcements');
        Route::post('/', [AnnouncementsController::class, 'createAnnouncement'])->name('announcements.create');
        Route::get('{announcementId}', [AnnouncementsController::class, 'getAnnouncement'])->name('announcements.view');
        Route::delete('{announcementId}', [AnnouncementsController::class, 'deleteAnnouncement'])->name('announcements.delete');
        Route::put('{announcementId}', [AnnouncementsController::class, 'updateAnnouncement'])->name('announcements.update');
    });
});
