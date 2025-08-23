<?php

namespace Modules\Admin\Http\Controllers;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Modules\Auth\Actions\Permissions\CreatePermissionAction;
use Modules\Auth\Actions\Permissions\DeletePermissionAction;
use Modules\Auth\Actions\Permissions\UpdatePermissionAction;
use Modules\Auth\Models\Permission;

#[Group('Admin Permissions')]
class AdminPermissionsController
{
    #[QueryParameter('per_page', description: 'Number of permissions per page', type: 'integer', default: 20, example: 10)]
    public function getPermissions(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.permissions') || ! $request->attributes->get('api_key')->can('admin.permissions')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Permissions retrieved successfully',
            'permissions' => Permission::orderBy('created_at')
                ->paginate($request->query('per_page', 20)),
        ]);
    }

    #[PathParameter('permissionId', description: 'ID of the permission to retrieve', type: 'integer', example: 1)]
    public function getPermission(Request $request, $permissionId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.permissions') || ! $request->attributes->get('api_key')->can('admin.permissions')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $permission = Permission::find($permissionId);

        if (! $permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        return response()->json([
            'message' => 'Permission retrieved successfully',
            'permission' => $permission,
        ]);
    }

    public function createPermission(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.permissions.create') || ! $request->attributes->get('api_key')->can('admin.permissions.create')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'guard_name' => 'required|string',
        ]);

        $permission = CreatePermissionAction::run($request->only(['name', 'guard_name']));

        return response()->json([
            'message' => 'Permission created successfully',
            'permission' => $permission,
        ]);
    }

    #[PathParameter('permissionId', description: 'ID of the permission to update', type: 'integer', example: 1)]
    public function updatePermission(Request $request, $permissionId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.permissions.update') || ! $request->attributes->get('api_key')->can('admin.permissions.update')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $permission = Permission::find($permissionId);

        if (! $permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|unique:permissions,name,'.$permissionId,
            'guard_name' => 'required|string',
        ]);

        UpdatePermissionAction::run($permission, $request->only(['name', 'guard_name']));

        return response()->json([
            'message' => 'Permission updated successfully',
            'permission' => $permission->fresh(),
        ]);
    }

    #[PathParameter('permissionId', description: 'ID of the permission to delete', type: 'integer', example: 1)]
    public function deletePermission(Request $request, $permissionId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.permissions.delete') || ! $request->attributes->get('api_key')->can('admin.permissions.delete')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $permission = Permission::find($permissionId);

        if (! $permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        DeletePermissionAction::run($permission);

        return response()->json([
            'message' => 'Permission deleted successfully',
        ]);
    }
}
