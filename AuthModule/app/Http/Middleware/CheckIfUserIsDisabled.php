<?php

namespace Modules\AuthModule\Http\Middleware;

use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfUserIsDisabled
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->disabled === 1) {
            Auth::logout();

            Notification::make()
                ->title(__('authmodule::auth.login.user_disabled'))
                ->danger()
                ->send();

            return redirect()->route('auth.login', ['redirect' => $request->fullUrl()]);
        }

        return $next($request);
    }
}
