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
        if (auth()->user()->force_change_password === 1) {
            return redirect()->route('account.force-change-password');
        }

        if (auth()->user()->force_activate_two_factor === 1) {
            return redirect()->route('account.force-activate-two-factor');
        }

        return $next($request);
    }
}
