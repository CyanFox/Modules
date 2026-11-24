<?php

declare(strict_types=1);

/**
 * All admin permissions, matching the Admin module seeder.
 */
function allAdminPermissions(): array
{
    return [
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
}
