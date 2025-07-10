<?php

namespace Modules\Announcements\app\Actions;

use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Announcements\Models\Announcement;
use Modules\Auth\Models\Permission;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\User;

use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\textarea;

class UpdateAnnouncementAction
{
    use AsAction;

    public string $commandSignature = 'announcements:update {id}';

    public string $commandDescription = 'Update an existing Announcement';

    public function handle(Announcement $announcement, $data)
    {
        return $announcement->update($data);
    }

    public function asJob(Announcement $announcement, $data)
    {
        $this->handle($announcement, $data);
    }

    public function asListener(Announcement $announcement, $data)
    {
        $this->handle($announcement, $data);
    }

    public function asCommand(Command $command)
    {
        $announcement = Announcement::find($command->argument('id'));
        if (! $announcement) {
            $command->error('Announcement not found');

            return;
        }

        $title = text('Title', default: $announcement->title, required: true);
        $description = textarea('Description', default: $announcement->description);
        $icon = text('Icon', default: $announcement->icon, hint: 'All icons can be found at lucide.dev');
        $color = select('Color', [
            'info' => 'Info',
            'success' => 'Success',
            'warning' => 'Warning',
            'danger' => 'Danger',
        ], default: $announcement->color);
        $dismissible = select('Dismissible', [
            'yes' => 'Yes',
            'no' => 'No',
        ], default: $announcement->dismissible ? 'yes' : 'no');
        $disabled = select('Disabled', [
            'yes' => 'Yes',
            'no' => 'No',
        ], default: $announcement->disabled ? 'yes' : 'no');
        $groupAccess = multiselect('Group Access',
            Role::all()->pluck('name', 'id')->toArray(),
            default: $announcement->access()->whereNotNull('group_id')->pluck('group_id')->toArray()
        );
        $permissionAccess = multiselect('Permission Access',
            Permission::all()->pluck('name', 'id')->toArray(),
            default: $announcement->access()->whereNotNull('permission_id')->pluck('permission_id')->toArray()
        );
        $userAccess = multiselect('User Access',
            User::all()->pluck('username', 'id')->toArray(),
            default: $announcement->access()->whereNotNull('user_id')->pluck('user_id')->toArray()
        );

        $this->handle($announcement, [
            'title' => $title,
            'description' => $description,
            'icon' => $icon,
            'color' => $color,
            'dismissible' => $dismissible === 'yes',
            'disabled' => $disabled === 'yes',
        ]);

        $announcement->access()->delete();

        foreach ($groupAccess as $group) {
            $announcement->access()->create([
                'group_id' => $group,
                'announcement_id' => $announcement->id,
            ]);
        }

        foreach ($permissionAccess as $permission) {
            $announcement->access()->create([
                'permission_id' => $permission,
                'announcement_id' => $announcement->id,
            ]);
        }

        foreach ($userAccess as $user) {
            $announcement->access()->create([
                'user_id' => $user,
                'announcement_id' => $announcement->id,
            ]);
        }

        $command->info('Announcement updated successfully');
    }
}
