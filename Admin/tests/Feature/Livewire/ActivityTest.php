<?php

namespace Modules\Admin\Tests\Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Admin\tests\TestCase;
use Modules\Auth\Models\User;
use PHPUnit\Framework\Attributes\Test;

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
