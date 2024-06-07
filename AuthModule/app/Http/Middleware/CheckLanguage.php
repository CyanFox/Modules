<?php

namespace Modules\AuthModule\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class CheckLanguage
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (isset(Auth::user()->language)) {
            App::setLocale(Auth::user()->language);

            return $next($request);
        }

        $language = $request->cookie('language');

        if ($language) {
            App::setLocale($language);

            return $next($request);
        } else {
            App::setLocale(setting('app_lang'));
            $response = $next($request);

            if ($response instanceof Response) {
                $response->withCookie(cookie()->forever('language', setting('app_lang')));
            }

            return $response;
        }
    }
}
