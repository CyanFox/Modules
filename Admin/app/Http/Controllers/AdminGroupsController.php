<?php

namespace Modules\Admin\Http\Controllers;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Modules\Auth\Actions\Groups\CreateGroupAction;
use Modules\Auth\Actions\Groups\DeleteGroupAction;
use Modules\Auth\Actions\Groups\UpdateGroupAction;
use Modules\Auth\Models\Role;

#[Group('Admin Groups')]
class AdminGroupsController
{
    #[QueryParameter('per_page', description: 'Number of groups per page', type: 'integer', default: 20, example: 10)]
    public function getGroups(Request $request)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.groups')) {
            return $apiKey->sendNoPermissionResponse();
        }

        return apiResponse('Groups retrieved successfully',
            Role::orderBy('created_at')->paginate($request->query('per_page', 20)));
    }

    #[PathParameter('groupId', description: 'ID of the group to retrieve', type: 'integer', example: 1)]
    public function getGroup(Request $request, $groupId)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.groups')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $group = Role::find($groupId);

        if (! $group) {
            return apiResponse('Group not found', null, false, 404);
        }

        return apiResponse('Group retrieved successfully',
            $group->load(['permissions']));
    }

    public function createGroup(Request $request)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.groups.create')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'guard_name' => 'required|string',
        ]);

        $group = CreateGroupAction::run($request->only(['name', 'guard_name']));
        $group->syncPermissions($request->input('permissions', []));

        return apiResponse('Group created successfully',
            $group->load(['permissions']));
    }

    #[PathParameter('groupId', description: 'ID of the group to update', type: 'integer', example: 1)]
    public function updateGroup(Request $request, $groupId)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.groups.update')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $group = Role::find($groupId);

        if (! $group) {
            return apiResponse('Group not found', null, false, 404);
        }

        $request->validate([
            'name' => 'required|string|unique:roles,name,'.$groupId,
            'guard_name' => 'required|string',
        ]);

        UpdateGroupAction::run($group, $request->only(['name', 'guard_name']));
        $group->syncPermissions($request->input('permissions', []));

        return apiResponse('Group updated successfully',
            $group->fresh()->load(['permissions']));
    }

    #[PathParameter('groupId', description: 'ID of the group to delete', type: 'integer', example: 1)]
    public function deleteGroup(Request $request, $groupId)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.groups.delete')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $group = Role::find($groupId);

        if (! $group) {
            return apiResponse('Group not found', null, false, 404);
        }

        DeleteGroupAction::run($group);

        return apiResponse('Group deleted successfully');
    }
}
