<?php

namespace Modules\Redirects\Database\Seeders;

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
            'redirects.view',
            'redirects.view.all',
            'redirects.create',
            'redirects.update',
            'redirects.update.admin',
            'redirects.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }
    }
}
