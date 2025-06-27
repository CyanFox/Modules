<?php

namespace Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Admin\tests\TestCase;
use Modules\Auth\Models\User;
use PHPUnit\Framework\Attributes\Test;

class ModulesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $this->actingAs($user)->get(route('admin.modules'))
            ->assertStatus(200);
    }
}
