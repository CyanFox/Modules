<?php

namespace Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Announcements\Livewire\Admin\CreateAnnouncement;
use Modules\Announcements\Livewire\Admin\UpdateAnnouncement;
use Modules\Announcements\Livewire\Components\Tables\AnnouncementsTable;
use Modules\Announcements\Models\Announcement;
use Modules\Auth\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Modules\Announcements\tests\TestCase;

class AdminAnnouncementsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        Announcement::create([
            'title' => 'Test Announcement',
            'description' => 'This is a test announcement.',
            'color' => 'info',
            'dismissible' => true,
            'disabled' => false,
            'icon' => 'bell',
        ]);

        $this->actingAs($user)->get(route('admin.announcements'))
            ->assertSuccessful();
    }

    #[Test]
    public function can_create_announcement()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        Livewire::actingAs($user)
            ->test(CreateAnnouncement::class)
            ->set('title', 'Test Announcement')
            ->set('description', 'This is a test announcement.')
            ->set('color', 'info')
            ->set('dismissible', true)
            ->set('disabled', false)
            ->set('icon', 'bell')
            ->call('createAnnouncement');

        $this->assertDatabaseHas('announcements', [
            'title' => 'Test Announcement',
            'description' => 'This is a test announcement.',
            'color' => 'info',
            'dismissible' => true,
            'disabled' => false,
            'icon' => 'bell',
        ]);

    }

    #[Test]
    public function can_update_announcement()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $announcement = Announcement::create([
            'title' => 'Test Announcement',
            'description' => 'This is a test announcement.',
            'color' => 'info',
            'dismissible' => true,
            'disabled' => false,
            'icon' => 'bell',
        ]);

        Livewire::actingAs($user)
            ->test(UpdateAnnouncement::class, ['announcementId' => $announcement->id])
            ->set('announcementId', $announcement->id)
            ->set('title', 'Test Announcement 1')
            ->set('description', 'This is a test announcement. 1')
            ->set('color', 'info')
            ->set('dismissible', true)
            ->set('disabled', false)
            ->set('icon', 'bell')
            ->call('updateAnnouncement');

        $this->assertDatabaseHas('announcements', [
            'id' => $announcement->id,
            'title' => 'Test Announcement 1',
            'description' => 'This is a test announcement. 1',
            'color' => 'info',
            'dismissible' => true,
            'disabled' => false,
            'icon' => 'bell',
        ]);
    }

    #[Test]
    public function can_delete_announcement()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $announcement = Announcement::create([
            'title' => 'Test Announcement',
            'description' => 'This is a test announcement.',
            'color' => 'info',
            'dismissible' => true,
            'disabled' => false,
            'icon' => 'bell',
        ]);

        Livewire::actingAs($user)
            ->test(AnnouncementsTable::class)
            ->call('deleteAnnouncement', $announcement->id);

        $this->assertDatabaseMissing('announcements', [
            'id' => $announcement->id,
        ]);
    }
}
