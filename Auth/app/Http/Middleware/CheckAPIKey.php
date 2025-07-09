<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Models\ApiKey;

class CheckAPIKey
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('Authorization') || !$request->header('Authorization')) {
            return response()->json(['error' => 'API key is missing'], 401);
        }

        list($apiKeyId, $apiKeyValue) = explode('-', $request->header('Authorization'), 2);

        $apiKey = ApiKey::find($apiKeyId);

        if (!$apiKey) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        if (!Hash::check($apiKeyValue, $apiKey->key)) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        $apiKey->update(['last_used' => now()]);
        $request->attributes->set('api_key', $apiKey);

        return $next($request);
    }
}
