<?php

namespace Modules\Account\Http\Controllers\API\V1;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('Account API Keys')]
class APIKeysController
{
    #[Endpoint(title: 'Get API Keys', description: 'Supports pagination, filtering and sorting via laravel-query-builder')]
    #[Response(200, 'API Keys retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'API Keys retrieved.', 'data' => [
        [
            'id' => 1,
            'tokenable_type' => 'Some\\Model',
            'tokenable_id' => 1,
            'name' => [
                'name' => '',
                'type' => 'api',
            ],
            'abilities' => [],
            'last_used_at' => '2000-01-01T00:00:00.000000Z',
            'expires_at' => '2000-01-01T00:00:00.000000Z',
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z',
        ]
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getApiKeys(Request $request)
    {
        $sessions = QueryBuilder::for($request->user()->tokens())
            ->where('name', 'like', '%"type":"api"%')
            ->allowedSorts('created_at', 'last_used_at', 'expires_at')
            ->allowedFilters('name', 'abilities')
            ->paginate($request->query('perPage') ?? null);

        return apiResponse('API Keys retrieved.', $sessions);
    }

    #[Endpoint(title: 'Create API Key')]
    #[Response(200, 'API Key created.', examples: ['application/json' => ['success' => true, 'message' => 'API Key created.', 'data' => [
        'token' => [
            'accessToken' => [
                'id' => 1,
                'tokenable_type' => 'Some\\Model',
                'tokenable_id' => 1,
                'name' => [
                    'name' => '',
                    'type' => 'api',
                ],
                'abilities' => [],
                'last_used_at' => '2000-01-01T00:00:00.000000Z',
                'expires_at' => '2000-01-01T00:00:00.000000Z',
                'created_at' => '2000-01-01T00:00:00.000000Z',
                'updated_at' => '2000-01-01T00:00:00.000000Z',
            ],
        ],
        'plainTextToken' => ''
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function createApiKey(Request $request)
    {
        $request->validate([
            'password' => 'required|string|current_password',
            'name' => 'required|string',
            'permissions' => 'nullable|array',
        ]);

        $token = $request->user()->createToken(json_encode(['name' => $request->name, 'type' => 'api']), $request->permissions ?? []);
        $token->accessToken->name = json_decode($token->accessToken->name, true);

        return apiResponse('API Key created.', [
            'token' => $token
        ]);
    }

    #[Endpoint(title: 'Delete API Key')]
    #[Response(200, 'API Key deleted.', examples: ['application/json' => ['success' => true, 'message' => 'API Key deleted.', 'data' => []]])]
    #[Response(404, 'API Key not found.', examples: ['application/json' => ['success' => false, 'message' => 'API Key not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function deleteApiKey(Request $request, $apiKeyId)
    {
        $apiKey = $request->user()->tokens()->where('id', $apiKeyId)->first();

        if ($apiKey?->delete()) {
            return apiResponse('API Key deleted.');
        }

        return apiResponse('API Key not found.', null, 404);
    }
}
