<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckForceActionsApi
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            if (userSettings('auth.force_password_change')) {
                return apiResponse('Please change your password.', apiError('auth.force.password_change'), false, 403);
            }

            if (userSettings('auth.force_mfa')) {
                return apiResponse('Please enable MFA.', apiError('auth.force.mfa'), false, 403);
            }
        }

        return $next($request);
    }
}
