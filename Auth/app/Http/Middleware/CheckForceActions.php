<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckForceActions
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            if ($request->routeIs('auth.force-password-change') && ! userSettings('auth.force_password_change')
                || $request->routeIs('auth.force-mfa') && ! userSettings('auth.force_mfa')) {
                return redirect()->to('/');
            }

            if ($request->routeIs('auth.force-password-change') || $request->routeIs('auth.force-mfa')) {
                return $next($request);
            }

            if (userSettings('auth.force_password_change')) {
                return redirect()->route('auth.force-password-change');
            }
            if (userSettings('auth.force_mfa')) {
                return redirect()->route('auth.force-mfa');
            }
        }

        return $next($request);
    }
}
