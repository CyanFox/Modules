<?php

namespace Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Modules\Admin\Livewire\Groups\CreateGroup;
use Modules\Admin\Livewire\Groups\UpdateGroup;
use Modules\Admin\tests\TestCase;
use Modules\Auth\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;

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
