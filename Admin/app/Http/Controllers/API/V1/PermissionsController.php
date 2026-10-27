<?php

namespace Modules\Admin\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Modules\Core\Models\Permission;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('Admin Permissions')]
class PermissionsController extends Controller
{
    #[Endpoint(title: 'Get Permissions', description: 'Supports pagination, filtering and sorting via laravel-query-builder')]
    #[Response(200, 'Permissions retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Permissions retrieved.', 'data' => [
        [
            'id' => 1,
            'name' => 'name',
            'guard_name' => 'guard name',
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z',
        ]
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getPermissions(Request $request)
    {
        if ($request->user()->cannot('admin.permissions')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        return apiResponse('Permissions retrieved.', QueryBuilder::for(Permission::class)
            ->allowedSorts('name', 'guard_name')
            ->allowedFilters('name', 'guard_name')
            ->paginate($request->query('perPage') ?? null));
    }

    #[Endpoint(title: 'Get Permission')]
    #[Response(200, 'Permission retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Permission retrieved.', 'data' => [
        'id' => 1,
        'name' => 'name',
        'guard_name' => 'guard name',
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(404, 'Permission not found.', examples: ['application/json' => ['success' => false, 'message' => 'Permission not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getPermission(Request $request, int $permissionsId)
    {
        if ($request->user()->cannot('admin.permissions')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $permission = Permission::find($permissionsId);
        if (!$permission) {
            return apiResponse('Permission not found.', [], false, 404);
        }

        return apiResponse('Permission retrieved.', $permission->toArray());
    }

    #[Endpoint(title: 'Create Permission')]
    #[Response(200, 'Permission created.', examples: ['application/json' => ['success' => true, 'message' => 'Permission created.', 'data' => [
        'id' => 1,
        'name' => 'name',
        'guard_name' => 'guard name',
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function createPermission(Request $request)
    {
        if ($request->user()->cannot('admin.permissions.create')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'guard_name' => 'required|string|max:255',
        ]);

        $permission = Permission::create($request->all());

        return apiResponse('Permission created.', $permission);
    }

    #[Endpoint(title: 'Update Permission')]
    #[Response(200, 'Permission updated.', examples: ['application/json' => ['success' => true, 'message' => 'Permission updated.', 'data' => [
        'id' => 1,
        'name' => 'name',
        'guard_name' => 'guard name',
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(404, 'Permission not found.', examples: ['application/json' => ['success' => false, 'message' => 'Permission not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function updatePermission(Request $request, int $permissionId)
    {
        if ($request->user()->cannot('admin.permissions.update')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $permission = Permission::find($permissionId);
        if (!$permission) {
            return apiResponse('Permission not found.', [], false, 404);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permissionId,
            'guard_name' => 'nullable|string|max:255',
        ]);

        $permission->update($request->all());

        return apiResponse('Permission updated.', $permission);
    }

    #[Endpoint(title: 'Delete Permission')]
    #[Response(200, 'Permission deleted.', examples: ['application/json' => ['success' => true, 'message' => 'Permission deleted.', 'data' => []]])]
    #[Response(404, 'Permission not found.', examples: ['application/json' => ['success' => false, 'message' => 'Permission not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function deletePermission(Request $request, int $permissionId)
    {
        if ($request->user()->cannot('admin.permissions.delete')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $permission = Permission::find($permissionId);
        if (!$permission) {
            return apiResponse('Permission not found.', [], false, 404);
        }

        $permission->delete();

        return apiResponse('Permission deleted.');
    }
}
