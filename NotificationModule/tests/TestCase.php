<?php

namespace Modules\NotificationModule\tests;

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
            'notificationmodule.notifications.admin.view',
            'notificationmodule.notifications.admin.create',
            'notificationmodule.notifications.admin.update',
            'notificationmodule.notifications.admin.delete',
        ];

        PermissionsManager::createPermissions('adminmodule', $permissions);
        PermissionsManager::createGroups('adminmodule', 'Super Admin', Permission::all(), now()->addHour());
    }
}
