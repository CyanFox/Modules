<?php

namespace Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Admin\Livewire\Components\Tables\PermissionsTable;
use Modules\Admin\Livewire\Permissions\CreatePermission;
use Modules\Admin\Livewire\Permissions\UpdatePermission;
use Modules\Admin\tests\TestCase;
use Modules\Auth\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->log('Test activity log');

        $this->actingAs($user)->get(route('admin.activity'))
            ->assertStatus(200);
    }
}
