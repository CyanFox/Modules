<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Permission;

class PermissionsSeeder extends Seeder
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

            'admin.modules',
            'admin.modules.install',
            'admin.modules.disable',
            'admin.modules.enable',
            'admin.modules.delete',

            'admin.activity',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }
    }
}
