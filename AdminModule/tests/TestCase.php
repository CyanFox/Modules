<?php

namespace Modules\AdminModule\Tests;

use App\Facades\Utils\PermissionsManager;
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

        PermissionsManager::createPermissions('adminmodule', $permissions);
        PermissionsManager::createGroups('adminmodule', 'Super Admin', Permission::all(), now()->addHour());
    }
}
