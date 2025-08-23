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
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.groups') || ! $request->attributes->get('api_key')->can('admin.groups')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Groups retrieved successfully',
            'groups' => Role::orderBy('created_at')->paginate($request->query('per_page', 20)),
        ]);
    }

    #[PathParameter('groupId', description: 'ID of the group to retrieve', type: 'integer', example: 1)]
    public function getGroup(Request $request, $groupId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.groups') || ! $request->attributes->get('api_key')->can('admin.groups')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $group = Role::find($groupId);

        if (! $group) {
            return response()->json(['error' => 'Group not found'], 404);
        }

        return response()->json([
            'message' => 'Group retrieved successfully',
            'group' => $group->load(['permissions']),
        ]);
    }

    public function createGroup(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.groups.create') || ! $request->attributes->get('api_key')->can('admin.groups.create')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'guard_name' => 'required|string',
        ]);

        $group = CreateGroupAction::run($request->only(['name', 'guard_name']));
        $group->syncPermissions($request->input('permissions', []));

        return response()->json([
            'message' => 'Group created successfully',
            'group' => $group->load(['permissions']),
        ]);
    }

    #[PathParameter('groupId', description: 'ID of the group to update', type: 'integer', example: 1)]
    public function updateGroup(Request $request, $groupId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.groups.update') || ! $request->attributes->get('api_key')->can('admin.groups.update')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $group = Role::find($groupId);

        if (! $group) {
            return response()->json(['error' => 'Group not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|unique:roles,name,'.$groupId,
            'guard_name' => 'required|string',
        ]);

        UpdateGroupAction::run($group, $request->only(['name', 'guard_name']));
        $group->syncPermissions($request->input('permissions', []));

        return response()->json([
            'message' => 'Group updated successfully',
            'group' => $group->fresh()->load(['permissions']),
        ]);
    }

    #[PathParameter('groupId', description: 'ID of the group to delete', type: 'integer', example: 1)]
    public function deleteGroup(Request $request, $groupId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.groups.delete') || ! $request->attributes->get('api_key')->can('admin.groups.delete')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $group = Role::find($groupId);

        if (! $group) {
            return response()->json(['error' => 'Group not found'], 404);
        }

        DeleteGroupAction::run($group);

        return response()->json([
            'message' => 'Group deleted successfully',
        ]);
    }
}
