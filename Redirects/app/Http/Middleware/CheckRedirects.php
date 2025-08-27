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

        if ($redirect && $this->hasAccess($redirect, $request)) {
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

    private function hasAccess(Redirect $redirect, Request $request): bool
    {
        if (!$redirect->internal) {
            return true;
        }

        if (!auth()->check()) {
            return false;
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

        return $hasRoleAccess || $hasPermissionAccess || $hasUserAccess;
    }
}
