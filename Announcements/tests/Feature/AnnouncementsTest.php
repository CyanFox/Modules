<?php

namespace Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Announcements\Models\Announcement;
use Modules\Announcements\tests\TestCase;
use Modules\Auth\Models\User;
use PHPUnit\Framework\Attributes\Test;

class AnnouncementsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully(): void
    {
        $user = User::factory()->create();

        Announcement::create([
            'title' => 'Test Announcement',
            'description' => 'This is a test announcement.',
            'color' => 'info',
            'dismissible' => true,
            'disabled' => false,
            'icon' => 'bell',
        ]);

        $this->actingAs($user)->get(route('dashboard'))
            ->assertSuccessful();
    }
}
