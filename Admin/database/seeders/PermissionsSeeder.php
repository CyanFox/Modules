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
            'admin.settings',
            'admin.activity',

            'admin.users',
            'admin.users.create',
            'admin.users.update',
            'admin.users.delete',

            'admin.roles',
            'admin.roles.create',
            'admin.roles.update',
            'admin.roles.delete',

            'admin.permissions',
            'admin.permissions.create',
            'admin.permissions.update',
            'admin.permissions.delete',

            'admin.modules',
            'admin.modules.disable',
            'admin.modules.enable',
            'admin.modules.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }
    }
}
