<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Masmerise\Toaster\Toaster;

class UserIsDisabled
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->disabled) {
            if ($request->expectsJson()) {
                return apiResponse('This account has been disabled', [], false, 401);
            }

            auth()->logout();

            Toaster::error(__('auth::login.user_disabled'));

            return redirect()->route('auth.login');
        }

        return $next($request);
    }
}
