<?php

namespace Modules\Redirects\Http\Controllers;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Modules\Redirects\Actions\CreateRedirectAction;
use Modules\Redirects\Actions\UpdateRedirectAction;
use Modules\Redirects\Actions\DeleteRedirectAction;
use Modules\Redirects\Models\Redirect;

#[Group('Redirects')]
class RedirectsController
{
    #[QueryParameter('per_page', description: 'Number of redirects per page', type: 'integer', default: 20, example: 10)]
    #[QueryParameter('active', description: 'Filter by active status', type: 'boolean', example: true)]
    public function getRedirects(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (!$user->can('admin.redirects') || !$request->attributes->get('api_key')->can('admin.redirects')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = Redirect::query()->orderBy('created_at', 'desc');

        if ($request->has('active')) {
            $query->where('active', $request->boolean('active'));
        }

        $redirects = $query->paginate($request->query('per_page', 20));

        return response()->json([
            'message' => 'Redirects retrieved successfully',
            'redirects' => $redirects,
        ]);
    }

    #[PathParameter('redirectId', description: 'ID of the redirect to view', type: 'integer')]
    public function getRedirect(Request $request, $redirectId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (!$user->can('admin.redirects') || !$request->attributes->get('api_key')->can('admin.redirects')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $redirect = Redirect::findOrFail($redirectId);

        return response()->json([
            'message' => 'Redirect retrieved successfully',
            'redirect' => $redirect,
        ]);
    }

    public function createRedirect(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (!$user->can('admin.redirects.create') || !$request->attributes->get('api_key')->can('admin.redirects.create')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'from' => 'required|url|unique:redirects,from',
            'to' => 'required|url',
            'status_code' => 'required|in:301,302,303,307,308',
            'active' => 'nullable|boolean',
            'include_query_string' => 'nullable|boolean',
            'internal' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['created_by'] = $user->id;

        $redirect = CreateRedirectAction::run($data);

        return response()->json([
            'message' => 'Redirect created successfully',
            'redirect' => $redirect,
        ]);
    }

    #[PathParameter('redirectId', description: 'ID of the redirect to update', type: 'integer')]
    public function updateRedirect(Request $request, $redirectId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (!$user->can('admin.redirects.update') || !$request->attributes->get('api_key')->can('admin.redirects.update')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'from' => 'required|url|unique:redirects,from,' . $redirectId,
            'to' => 'required|url',
            'status_code' => 'required|in:301,302,303,307,308',
            'active' => 'nullable|boolean',
            'include_query_string' => 'nullable|boolean',
            'internal' => 'nullable|boolean',
        ]);

        $redirect = Redirect::findOrFail($redirectId);

        UpdateRedirectAction::run($redirect, $request->all());

        return response()->json([
            'message' => 'Redirect updated successfully',
            'redirect' => $redirect->fresh(),
        ]);
    }

    #[PathParameter('redirectId', description: 'ID of the redirect to delete', type: 'integer')]
    public function deleteRedirect(Request $request, $redirectId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (!$user->can('admin.redirects.delete') || !$request->attributes->get('api_key')->can('admin.redirects.delete')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $redirect = Redirect::findOrFail($redirectId);

        DeleteRedirectAction::run($redirect);

        return response()->json([
            'message' => 'Redirect deleted successfully',
        ]);
    }

    #[PathParameter('redirectId', description: 'ID of the redirect to get stats for', type: 'integer')]
    public function getRedirectStats(Request $request, $redirectId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (!$user->can('admin.redirects') || !$request->attributes->get('api_key')->can('admin.redirects')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $redirect = Redirect::findOrFail($redirectId);

        return response()->json([
            'message' => 'Redirect stats retrieved successfully',
            'stats' => [
                'hits' => $redirect->hits,
                'last_accessed_at' => $redirect->last_accessed_at,
            ],
        ]);
    }
}
