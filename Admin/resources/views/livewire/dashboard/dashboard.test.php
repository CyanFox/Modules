<?php

use Livewire\Livewire;
use Modules\Core\Models\Permission;
use Modules\Core\Models\Role;

beforeEach(fn () => seedCoreSettings());

it('renders successfully', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::dashboard')
        ->assertStatus(200);
});

it('shows the stats', function () {
    makeUser();
    makeUser();
    Role::create(['name' => 'Manager', 'guard_name' => 'web']);
    Permission::findOrCreate('test.permission', 'web');

    $this->actingAs(makeUser([], allAdminPermissions()));

    $component = Livewire::test('admin::dashboard');

    $stats = $component->get('stats');

    expect($stats['users'])->toBe(3);
    expect($stats['roles'])->toBe(1);
    expect($stats['permissions'])->toBe(Permission::count());
    expect($stats['modules'])->toBeInt();
    expect($stats['modules'])->toBeGreaterThan(0);
});
