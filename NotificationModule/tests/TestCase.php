<?php

namespace Modules\NotificationModule\tests;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

abstract class TestCase extends \Tests\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $permissions = [
            'adminmodule.admin',
            'notificationmodule.notifications.admin.view',
            'notificationmodule.notifications.admin.create',
            'notificationmodule.notifications.admin.update',
            'notificationmodule.notifications.admin.delete',
        ];

        $existingPermissionsQuery = Permission::query();
        $existingPermissions = $existingPermissionsQuery->whereIn('name', $permissions)->get()->keyBy('name');
        $newPermissions = [];

        foreach ($permissions as $permission) {
            if (!$existingPermissions->has($permission)) {
                $newPermissions[] = ['name' => $permission, 'module' => 'notificationmodule'];
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
