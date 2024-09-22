<?php

namespace Modules\AdminModule\Tests;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

abstract class TestCase extends \Tests\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $permissions = [
            'adminmodule.admin',
            'adminmodule.dashboard.view',
            'adminmodule.users.view',
            'adminmodule.users.create',
            'adminmodule.users.update',
            'adminmodule.users.delete',
            'adminmodule.groups.view',
            'adminmodule.groups.create',
            'adminmodule.groups.update',
            'adminmodule.groups.delete',
            'adminmodule.permissions.view',
            'adminmodule.permissions.create',
            'adminmodule.permissions.update',
            'adminmodule.permissions.delete',
            'adminmodule.settings.view',
            'adminmodule.settings.update',
            'adminmodule.settings.editor',
            'adminmodule.settings.editor.update',
            'adminmodule.modules.view',
            'adminmodule.modules.disable',
            'adminmodule.modules.enable',
            'adminmodule.modules.install',
            'adminmodule.modules.delete',
            'adminmodule.modules.actions.npm',
            'adminmodule.modules.actions.composer',
            'adminmodule.modules.actions.migrate',
        ];

        $existingPermissionsQuery = Permission::query();
        $existingPermissions = $existingPermissionsQuery->whereIn('name', $permissions)->get()->keyBy('name');
        $newPermissions = [];

        foreach ($permissions as $permission) {
            if (!$existingPermissions->has($permission)) {
                $newPermissions[] = ['name' => $permission, 'module' => 'adminmodule'];
            }
        }

        if (!empty($newPermissions)) {
            Permission::insert($newPermissions);
        }

        $role = Role::create(['name' => 'Super Admin'])->first();
        $role->syncPermissions(
            Permission::all()
        );
    }
}
