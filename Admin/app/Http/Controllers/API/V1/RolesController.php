<?php

namespace Modules\Admin\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Modules\Core\Models\Role;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('Admin Roles')]
class RolesController extends Controller
{
    #[Endpoint(title: 'Get Roles', description: 'Supports pagination, filtering and sorting via laravel-query-builder')]
    #[Response(200, 'Roles retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Roles retrieved.', 'data' => [
        [
            'id' => 1,
            'name' => 'name',
            'guard_name' => 'guard name',
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z',
        ]
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getRoles(Request $request)
    {
        if ($request->user()->cannot('admin.roles')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        return apiResponse('Roles retrieved.', QueryBuilder::for(Role::class)
            ->allowedSorts('name', 'guard_name')
            ->allowedFilters('name', 'guard_name')
            ->paginate($request->query('perPage') ?? null));
    }

    #[Endpoint(title: 'Get Role')]
    #[Response(200, 'Role retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Role retrieved.', 'data' => [
        'id' => 1,
        'name' => 'name',
        'guard_name' => 'guard name',
        'permissions' => [],
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(404, 'Role not found.', examples: ['application/json' => ['success' => false, 'message' => 'Role not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getRole(Request $request, int $roleId)
    {
        if ($request->user()->cannot('admin.roles')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $role = Role::find($roleId);
        if (!$role) {
            return apiResponse('Role not found.', [], false, 404);
        }

        return apiResponse('Role retrieved.', array_merge($role->withoutRelations()->toArray(), [
            'permissions' => $role->permissions->map(fn($permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
            ]),
        ]));
    }

    #[Endpoint(title: 'Create Role')]
    #[Response(200, 'Role created.', examples: ['application/json' => ['success' => true, 'message' => 'Role created.', 'data' => [
        'id' => 1,
        'name' => 'name',
        'guard_name' => 'guard name',
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function createRole(Request $request)
    {
        if ($request->user()->cannot('admin.roles.create')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create($request->all());

        $role->syncPermissions($request->permissions);

        return apiResponse('Role created.', $role);
    }

    #[Endpoint(title: 'Update Role')]
    #[Response(200, 'Role updated.', examples: ['application/json' => ['success' => true, 'message' => 'Role updated.', 'data' => [
        'id' => 1,
        'name' => 'name',
        'guard_name' => 'guard name',
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(404, 'Role not found.', examples: ['application/json' => ['success' => false, 'message' => 'Role not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function updateRole(Request $request, int $roleId)
    {
        if ($request->user()->cannot('admin.roles.update')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $role = Role::find($roleId);
        if (!$role) {
            return apiResponse('Role not found.', [], false, 404);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $roleId,
            'guard_name' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
        ]);

        $role->update($request->all());
        $role->syncPermissions($request->permissions);

        return apiResponse('Role updated.', $role);
    }

    #[Endpoint(title: 'Delete Role')]
    #[Response(200, 'Role deleted.', examples: ['application/json' => ['success' => true, 'message' => 'Role deleted.', 'data' => []]])]
    #[Response(404, 'Role not found.', examples: ['application/json' => ['success' => false, 'message' => 'Role not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function deleteRole(Request $request, int $roleId)
    {
        if ($request->user()->cannot('admin.roles.delete')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $role = Role::find($roleId);
        if (!$role) {
            return apiResponse('Role not found.', [], false, 404);
        }

        $role->delete();

        return apiResponse('Role deleted.');
    }
}
