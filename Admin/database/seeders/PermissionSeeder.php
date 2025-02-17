<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'admin.dashboard',

            'admin.users',
            'admin.users.create',
            'admin.users.update',
            'admin.users.delete',

            'admin.groups',
            'admin.groups.create',
            'admin.groups.update',
            'admin.groups.delete',

            'admin.permissions',
            'admin.permissions.create',
            'admin.permissions.update',
            'admin.permissions.delete',

            'admin.settings',
            'admin.settings.update',
            'admin.settings.modules',
            'admin.settings.editor',

            'admin.modules',
            'admin.modules.install',
            'admin.modules.disable',
            'admin.modules.enable',
            'admin.modules.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }
}
