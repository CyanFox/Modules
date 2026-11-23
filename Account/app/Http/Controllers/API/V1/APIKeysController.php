<?php

namespace Modules\Account\Http\Controllers\API\V1;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
        ],
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getApiKeys(Request $request)
    {
        $apiKeys = QueryBuilder::for($request->user()->tokens())
            ->allowedSorts('created_at', 'last_used_at', 'expires_at')
            ->allowedFilters('name', 'abilities')
            ->get()
            ->filter(function ($session) {
                $name = json_decode($session->name, true);

                return is_array($name) && ($name['type'] ?? null) === 'api';
            })
            ->map(function ($session) {
                $session->name = json_decode($session->name, true);

                return $session;
            })
            ->values();

        $perPage = (int) ($request->query('perPage') ?: 15);
        $perPage = $perPage > 0 ? $perPage : 15;
        $page = max((int) $request->query('page', 1), 1);

        $apiKeys = new LengthAwarePaginator(
            $apiKeys->slice(($page - 1) * $perPage, $perPage)->values(),
            $apiKeys->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ],
        );

        return apiResponse('API Keys retrieved.', $apiKeys);
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
        'plainTextToken' => '',
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function createApiKey(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'permissions' => 'nullable|array',
        ]);

        $token = $request->user()->createToken(json_encode(['name' => $request->name, 'type' => 'api']), $request->permissions ?? []);
        $token->accessToken->name = json_decode($token->accessToken->name, true);

        return apiResponse('API Key created.', [
            'token' => $token,
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

        return apiResponse('API Key not found.', apiError('account.api_keys.not_found'), 404);
    }
}
