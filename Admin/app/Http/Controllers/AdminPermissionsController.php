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
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.permissions')) {
            return $apiKey->sendNoPermissionResponse();
        }

        return apiResponse('Permissions retrieved successfully',
            Permission::orderBy('created_at')
                ->paginate($request->query('per_page', 20)));
    }

    #[PathParameter('permissionId', description: 'ID of the permission to retrieve', type: 'integer', example: 1)]
    public function getPermission(Request $request, $permissionId)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.permissions')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $permission = Permission::find($permissionId);

        if (!$permission) {
            return apiResponse('Permission not found', null, false, 404);
        }

        return apiResponse([
            'message' => 'Permission retrieved successfully',
            'permission' => $permission,
        ]);
    }

    public function createPermission(Request $request)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.permissions.create')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'guard_name' => 'required|string',
        ]);

        $permission = CreatePermissionAction::run($request->only(['name', 'guard_name']));

        return apiResponse('Permission created successfully', $permission);
    }

    #[PathParameter('permissionId', description: 'ID of the permission to update', type: 'integer', example: 1)]
    public function updatePermission(Request $request, $permissionId)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.permissions.update')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $permission = Permission::find($permissionId);

        if (!$permission) {
            return apiResponse('Permission not found', null, false, 404);
        }

        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permissionId,
            'guard_name' => 'required|string',
        ]);

        UpdatePermissionAction::run($permission, $request->only(['name', 'guard_name']));

        return apiResponse('Permission updated successfully', $permission->fresh());
    }

    #[PathParameter('permissionId', description: 'ID of the permission to delete', type: 'integer', example: 1)]
    public function deletePermission(Request $request, $permissionId)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.permissions.delete')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $permission = Permission::find($permissionId);

        if (!$permission) {
            return apiResponse('Permission not found', null, false, 404);
        }

        DeletePermissionAction::run($permission);

        return apiResponse('Permission deleted successfully');
    }
}
