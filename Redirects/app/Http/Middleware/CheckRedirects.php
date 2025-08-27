<?php

namespace Modules\Redirects\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Redirects\Models\Redirect;

class CheckRedirects
{
    public function handle(Request $request, Closure $next)
    {
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

        return $next($request);
    }
}
