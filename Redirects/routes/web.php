<?php

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

    $redirect = Redirect::where('active', true)
        ->where(function ($q) use ($fullUrl, $path, $host) {
            $q->where('from', $fullUrl)
                ->orWhere('from', $host)
                ->orWhere('from', $path);
        })
        ->orderByRaw("CASE
            WHEN `from` = ? THEN 1
            WHEN `from` = ? THEN 2
            WHEN `from` = ? THEN 3
            ELSE 4 END", [$fullUrl, $host, $path])
        ->first();

    if ($redirect) {
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
