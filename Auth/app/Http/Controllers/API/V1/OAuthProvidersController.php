<?php

namespace Modules\Auth\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Modules\Auth\Models\OAuthProvider;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('Auth OAuth Providers')]
class OAuthProvidersController extends Controller
{
    #[Endpoint(title: 'Get OAuth Providers', description: 'Supports pagination, filtering and sorting via laravel-query-builder')]
    #[Response(200, 'OAuth providers retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'OAuth providers retrieved.', 'data' => [
        [
            'id' => 1,
            'name' => 'name',
            'color' => 'primary',
            'icon' => 'icon-google',
            'discovery_url' => 'https://provider.example.com/.well-known/openid-configuration',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'account_linking' => true,
            'fields' => [
                ['oauth' => 'preferred_username', 'db' => 'username'],
                ['oauth' => 'email', 'db' => 'email'],
            ],
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z',
        ],
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getOAuthProviders(Request $request)
    {
        if ($request->user()->cannot('admin.settings')) {
            return apiResponse('Unauthenticated.', apiError('auth.oauth_providers.unauthenticated'), false, 401);
        }

        return apiResponse('OAuth providers retrieved.', QueryBuilder::for(OAuthProvider::class)
            ->allowedSorts('name', 'discovery_url')
            ->allowedFilters('name', 'discovery_url')
            ->paginate($request->query('perPage') === null ? null : (int)$request->query('perPage')));
    }

    #[Endpoint(title: 'Get OAuth Provider')]
    #[Response(200, 'OAuth provider retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'OAuth provider retrieved.', 'data' => [
        'id' => 1,
        'name' => 'name',
        'color' => 'primary',
        'icon' => 'icon-google',
        'discovery_url' => 'https://provider.example.com/.well-known/openid-configuration',
        'client_id' => 'client-id',
        'client_secret' => 'client-secret',
        'account_linking' => true,
        'fields' => [
            ['oauth' => 'preferred_username', 'db' => 'username'],
            ['oauth' => 'email', 'db' => 'email'],
        ],
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(404, 'OAuth provider not found.', examples: ['application/json' => ['success' => false, 'message' => 'OAuth provider not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getOAuthProvider(Request $request, int $oauthProviderId)
    {
        if ($request->user()->cannot('admin.settings')) {
            return apiResponse('Unauthenticated.', apiError('auth.oauth_providers.unauthenticated'), false, 401);
        }

        $provider = OAuthProvider::find($oauthProviderId);
        if (!$provider) {
            return apiResponse('OAuth provider not found.', apiError('auth.oauth_providers.not_found'), false, 404);
        }

        return apiResponse('OAuth provider retrieved.', $provider->withoutRelations()->toArray());
    }

    #[Endpoint(title: 'Create OAuth Provider')]
    #[Response(200, 'OAuth provider created.', examples: ['application/json' => ['success' => true, 'message' => 'OAuth provider created.', 'data' => [
        'id' => 1,
        'name' => 'name',
        'color' => 'primary',
        'icon' => 'icon-google',
        'discovery_url' => 'https://provider.example.com/.well-known/openid-configuration',
        'client_id' => 'client-id',
        'client_secret' => 'client-secret',
        'account_linking' => true,
        'fields' => [
            ['oauth' => 'preferred_username', 'db' => 'username'],
            ['oauth' => 'email', 'db' => 'email'],
        ],
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function createOAuthProvider(Request $request)
    {
        if ($request->user()->cannot('admin.settings')) {
            return apiResponse('Unauthenticated.', apiError('auth.oauth_providers.unauthenticated'), false, 401);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:oauth_providers,name',
            'color' => 'nullable|in:primary,secondary,success,danger,warning,info',
            'icon' => 'nullable|string|max:255',
            'discovery_url' => 'required|url',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'account_linking' => 'sometimes|boolean',
            'fields' => 'required|array',
        ]);

        $provider = OAuthProvider::create($request->all());

        return apiResponse('OAuth provider created.', $provider);
    }

    #[Endpoint(title: 'Update OAuth Provider')]
    #[Response(200, 'OAuth provider updated.', examples: ['application/json' => ['success' => true, 'message' => 'OAuth provider updated.', 'data' => [
        'id' => 1,
        'name' => 'name',
        'color' => 'primary',
        'icon' => 'icon-google',
        'discovery_url' => 'https://provider.example.com/.well-known/openid-configuration',
        'client_id' => 'client-id',
        'client_secret' => 'client-secret',
        'account_linking' => true,
        'fields' => [
            ['oauth' => 'preferred_username', 'db' => 'username'],
            ['oauth' => 'email', 'db' => 'email'],
        ],
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(404, 'OAuth provider not found.', examples: ['application/json' => ['success' => false, 'message' => 'OAuth provider not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function updateOAuthProvider(Request $request, int $oauthProviderId)
    {
        if ($request->user()->cannot('admin.settings')) {
            return apiResponse('Unauthenticated.', apiError('auth.oauth_providers.unauthenticated'), false, 401);
        }

        $provider = OAuthProvider::find($oauthProviderId);
        if (!$provider) {
            return apiResponse('OAuth provider not found.', apiError('auth.oauth_providers.not_found'), false, 404);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:oauth_providers,name,' . $oauthProviderId,
            'color' => 'nullable|in:primary,secondary,success,danger,warning,info',
            'icon' => 'nullable|string|max:255',
            'discovery_url' => 'required|url',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'account_linking' => 'sometimes|boolean',
            'fields' => 'required|array',
        ]);

        $provider->update($request->all());

        return apiResponse('OAuth provider updated.', $provider);
    }

    #[Endpoint(title: 'Delete OAuth Provider')]
    #[Response(200, 'OAuth provider deleted.', examples: ['application/json' => ['success' => true, 'message' => 'OAuth provider deleted.', 'data' => []]])]
    #[Response(404, 'OAuth provider not found.', examples: ['application/json' => ['success' => false, 'message' => 'OAuth provider not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function deleteOAuthProvider(Request $request, int $oauthProviderId)
    {
        if ($request->user()->cannot('admin.settings')) {
            return apiResponse('Unauthenticated.', apiError('auth.oauth_providers.unauthenticated'), false, 401);
        }

        $provider = OAuthProvider::find($oauthProviderId);
        if (!$provider) {
            return apiResponse('OAuth provider not found.', apiError('auth.oauth_providers.not_found'), false, 404);
        }

        $provider->delete();

        return apiResponse('OAuth provider deleted.');
    }
}
