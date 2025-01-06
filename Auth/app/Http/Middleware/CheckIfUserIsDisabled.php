<?php

namespace Modules\Auth\Http\Middleware;

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
        if (auth()->check() && auth()->user()->disabled) {
            auth()->logout();

            Notification::make()
                ->title(__('authmodule::auth.login.user_disabled'))
                ->danger()
                ->send();

            return redirect()->route('auth.login');
        }

        return $next($request);
    }
}
