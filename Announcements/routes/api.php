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
    Route::get('/', [AnnouncementsController::class, 'getUserAnnouncements'])->name('Get User Announcements');
    Route::patch('dismiss/{announcementId}', [AnnouncementsController::class, 'dismissAnnouncement'])->name('Dismiss Announcement');

    Route::prefix('admin')->group(function () {
        Route::get('/', [AnnouncementsController::class, 'getAnnouncements'])->name('Get Announcements');
        Route::post('/', [AnnouncementsController::class, 'createAnnouncement'])->name('Create Announcement');
        Route::get('{announcementId}', [AnnouncementsController::class, 'getAnnouncement'])->name('Get specific Announcement');
        Route::delete('{announcementId}', [AnnouncementsController::class, 'deleteAnnouncement'])->name('Delete Announcement');
        Route::put('{announcementId}', [AnnouncementsController::class, 'updateAnnouncement'])->name('Update Announcement');
    });
});
