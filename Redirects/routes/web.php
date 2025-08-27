<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Redirects\Livewire\CreateRedirect;
use Modules\Redirects\Livewire\Redirects;
use Modules\Redirects\Livewire\UpdateRedirect;
use Modules\Redirects\Models\Redirect;

Route::group(['prefix' => 'redirects', 'middleware' => ['auth']], function () {
    Route::get('/', Redirects::class)->name('redirects');
    Route::get('create', CreateRedirect::class)->name('redirects.create');
    Route::get('update/{redirectId}', UpdateRedirect::class)->name('redirects.update');
});

Route::fallback(function (Request $request) {
    $fullUrl = $request->fullUrl();
    $path = '/' . ltrim($request->path(), '/');
    $host = $request->getScheme() . '://' . $request->getHost();
    $urlWithoutQuery = $host . $path;

    $redirect = Redirect::where('active', true)
        ->where(function ($q) use ($fullUrl, $path, $host, $urlWithoutQuery) {
            $q->where('from', $fullUrl)
                ->orWhere('from', $urlWithoutQuery)
                ->orWhere('from', $host)
                ->orWhere('from', $path);
        })
        ->orderByRaw("CASE
                WHEN `from` = ? THEN 1
                WHEN `from` = ? THEN 2
                WHEN `from` = ? THEN 3
                WHEN `from` = ? THEN 4
                ELSE 5 END", [$fullUrl, $urlWithoutQuery, $host, $path])
        ->first();

    if ($redirect) {
        if ($redirect->internal) {
            if (!auth()->check()) {
                abort(404);
            }

            $user = auth()->user();
            $access = $redirect->access()->where('can_update', false);

            $hasRoleAccess = $access->whereNotNull('role_id')
                ->whereIn('role_id', $user->roles->pluck('id'))
                ->exists();

            $hasPermissionAccess = $access->whereNotNull('permission_id')
                ->whereIn('permission_id', $user->getAllPermissions()->pluck('id'))
                ->exists();

            $hasUserAccess = $access->whereNotNull('user_id')
                ->where('user_id', $user->id)
                ->exists();

            if (!($hasRoleAccess || $hasPermissionAccess || $hasUserAccess)) {
                abort(404);
            }
        }

        $targetUrl = $redirect->to;
        if ($redirect->include_query_string && $request->getQueryString()) {
            $separator = parse_url($targetUrl, PHP_URL_QUERY) ? '&' : '?';
            $targetUrl .= $separator . $request->getQueryString();
        }

        $redirect->increment('hits');
        $redirect->update(['last_accessed_at' => now()]);

        return redirect($targetUrl, $redirect->status_code);
    }

    abort(404);
});
