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
            if ($request->routeIs('account.force.change-password') && ! auth()->user()->force_change_password
                || $request->routeIs('account.force.activate-two-factor') && ! auth()->user()->force_activate_two_factor) {
                return redirect()->route('dashboard');
            }

            if ($request->routeIs('account.force.change-password') || $request->routeIs('account.force.activate-two-factor')) {
                return $next($request);
            }

            if (auth()->user()->force_change_password) {
                return redirect()->route('account.force.change-password');
            }
            if (auth()->user()->force_activate_two_factor) {
                return redirect()->route('account.force.activate-two-factor');
            }
        }

        return $next($request);
    }
}
