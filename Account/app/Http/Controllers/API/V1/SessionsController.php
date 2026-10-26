<?php

namespace Modules\Account\Http\Controllers\API\V1;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('Account Sessions')]
class SessionsController
{
    #[Endpoint(title: 'Get Web Sessions', description: 'Supports pagination, filtering and sorting via laravel-query-builder')]
    #[Response(200, 'Web sessions retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Web sessions retrieved.', 'data' => [
        [
            'id' => 'abcdefg',
            'user_id' => 1,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
            'last_activity' => 1234567890,
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z',
        ]
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getWebSessions(Request $request)
    {
        return apiResponse('Web sessions retrieved.', QueryBuilder::for($request->user()->sessions())
            ->allowedSorts('created_at', 'user_agent', 'last_activity')
            ->allowedFilters('ip_address', 'user_agent')
            ->paginate($request->query('perPage') ?? null));
    }

    #[Endpoint(title: 'Get Native Sessions', description: 'Supports pagination, filtering and sorting via laravel-query-builder')]
    #[Response(200, 'Native sessions retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Native sessions retrieved.', 'data' => [
        [
            'id' => 1,
            'tokenable_type' => 'Some\\Model',
            'tokenable_id' => 1,
            'name' => [
                'name' => '',
                'type' => 'native',
                'platform' => '',
                'uuid' => ''
            ],
            'abilities' => [],
            'last_used_at' => '2000-01-01T00:00:00.000000Z',
            'expires_at' => '2000-01-01T00:00:00.000000Z',
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z',
        ]
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getNativeSessions(Request $request)
    {
        $sessions = QueryBuilder::for($request->user()->tokens())
            ->where('name', 'like', '%"type":"native"%')
            ->allowedSorts('created_at', 'last_used_at', 'expires_at')
            ->allowedFilters('name', 'abilities')
            ->paginate($request->query('perPage') ?? null);

        return apiResponse('Native sessions retrieved.', $sessions);
    }

    #[Endpoint(title: 'Revoke Web Session')]
    #[Response(200, 'Web session revoked.', examples: ['application/json' => ['success' => true, 'message' => 'Web session revoked.', 'data' => []]])]
    #[Response(404, 'Web session not found.', examples: ['application/json' => ['success' => true, 'message' => 'Web session not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function revokeWebSession(Request $request, $sessionId)
    {
        $request->validate([
            'password' => 'required|string|current_password'
        ]);

        $session = $request->user()->sessions()->where('id', $sessionId)->first();
        if ($session?->delete()) {
            return apiResponse('Web session revoked.');
        }

        return apiResponse('Web session not found.', [], false, 404);
    }

    #[Endpoint(title: 'Revoke Native Session')]
    #[Response(200, 'Native session revoked.', examples: ['application/json' => ['success' => true, 'message' => 'Native session revoked.', 'data' => []]])]
    #[Response(404, 'Native session not found.', examples: ['application/json' => ['success' => true, 'message' => 'Native session not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function revokeNativeSession(Request $request, $sessionId)
    {
        $request->validate([
            'password' => 'required|string|current_password'
        ]);

        $session = $request->user()->tokens()->where('id', $sessionId)->first();
        if ($session?->delete()) {
            return apiResponse('Native session revoked.');
        }

        return apiResponse('Native session not found.', [], false, 404);
    }

    #[Endpoint(title: 'Login QR Code')]
    #[Response(200, 'Login QR code retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Login QR code retrieved.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getLoginQrCode(Request $request)
    {
        $request->validate([
            'password' => 'required|string|current_password'
        ]);

        return apiResponse('Login QR code retrieved.', ['qrcode' => url()->temporarySignedRoute('api.auth.login.qrcode', now()->addMinutes(5), ['userId' => $request->user()->id])]);
    }
}
