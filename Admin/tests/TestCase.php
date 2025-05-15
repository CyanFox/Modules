<?php

namespace Modules\Admin\tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

        Artisan::call('module:seed', ['module' => 'Admin']);

        $group = Role::findOrCreate('Super Admin', 'web');
        $group->givePermissionTo(Permission::all());
    }
}
