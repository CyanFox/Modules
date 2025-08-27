<?php

namespace Modules\Redirects\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Auth\Models\User;
use Modules\Redirects\Livewire\Components\Tables\RedirectsTable;
use Modules\Redirects\Livewire\CreateRedirect;
use Modules\Redirects\Livewire\UpdateRedirect;
use Modules\Redirects\Models\Redirect;
use Modules\Redirects\tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class RedirectsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully(): void
    {
        $user = User::factory()->create();

        Redirect::create([
            'from' => '/old-url',
            'to' => '/new-url',
            'status_code' => 301,
            'created_by' => $user->id,
            'active' => true,
            'hits' => 10,
        ]);

        $this->actingAs($user)->get(route('redirects'))
            ->assertSuccessful();
    }

    #[Test]
    public function can_create_redirect()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        Livewire::actingAs($user)
            ->test(CreateRedirect::class)
            ->set('from', 'https://example.com/old-url')
            ->set('to', 'https://example.com/new-url')
            ->set('statusCode', 301)
            ->set('active', true)
            ->call('createRedirect');

        $this->assertDatabaseHas('redirects', [
            'from' => 'https://example.com/old-url',
            'to' => 'https://example.com/new-url',
            'status_code' => 301,
            'created_by' => $user->id,
            'active' => true,
            'hits' => 0,
        ]);
    }

    #[Test]
    public function can_update_redirect()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $redirect = Redirect::create([
            'from' => 'https://example.com/old-url',
            'to' => 'https://example.com/new-url',
            'status_code' => 301,
            'created_by' => $user->id,
            'active' => true,
            'hits' => 10,
        ]);

        Livewire::actingAs($user)
            ->test(UpdateRedirect::class, ['redirectId' => $redirect->id])
            ->set('redirectId', $redirect->id)
            ->set('redirect', $redirect)
            ->set('from', 'https://example.com/updated-old-url')
            ->set('to', 'https://example.com/updated-new-url')
            ->set('statusCode', 302)
            ->set('active', false)
            ->call('updateRedirect');

        $this->assertDatabaseHas('redirects', [
            'id' => $redirect->id,
            'from' => 'https://example.com/updated-old-url',
            'to' => 'https://example.com/updated-new-url',
            'status_code' => 302,
            'created_by' => $user->id,
            'active' => false,
            'hits' => 10,
        ]);
    }

    #[Test]
    public function can_delete_redirect()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $redirect = Redirect::create([
            'from' => '/old-url',
            'to' => '/new-url',
            'status_code' => 301,
            'created_by' => $user->id,
            'active' => true,
            'hits' => 10,
        ]);

        Livewire::actingAs($user)
            ->test(RedirectsTable::class)
            ->call('deleteRedirect', $redirect->id);

        $this->assertDatabaseMissing('redirects', [
            'id' => $redirect->id,
        ]);
    }

    #[Test]
    public function redirect_can_redirect(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        Redirect::create([
            'from' => '/old-url',
            'to' => '/new-url',
            'status_code' => 301,
            'created_by' => $user->id,
            'active' => true,
            'hits' => 0,
        ]);

        $this->get('/old-url')
            ->assertRedirect('/new-url');
    }
}
