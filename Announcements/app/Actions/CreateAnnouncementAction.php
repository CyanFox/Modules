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

class CreateAnnouncementAction
{
    use AsAction;

    public string $commandSignature = 'announcements:create';

    public string $commandDescription = 'Create a new Announcement';

    public function handle($data)
    {
        return Announcement::create($data);
    }

    public function asJob($data)
    {
        $this->handle($data);
    }

    public function asListener($data)
    {
        $this->handle($data);
    }

    public function asCommand(Command $command)
    {
        $title = text('Title', required: true);
        $description = textarea('Description');
        $icon = text('Icon', default: 'bell', hint: 'All icons can be found at lucide.dev');
        $color = select('Color', [
            'info' => 'Info',
            'success' => 'Success',
            'warning' => 'Warning',
            'danger' => 'Danger',
        ], default: 'info');
        $dismissible = select('Dismissible', [
            'yes' => 'Yes',
            'no' => 'No',
        ], default: 'yes');
        $disabled = select('Disabled', [
            'yes' => 'Yes',
            'no' => 'No',
        ], default: 'no');
        $groupAccess = multiselect('Group Access',
            Role::all()->pluck('name', 'id')->toArray(),
        );
        $permissionAccess = multiselect('Permission Access',
            Permission::all()->pluck('name', 'id')->toArray(),
        );
        $userAccess = multiselect('User Access',
            User::all()->pluck('username', 'id')->toArray(),
        );

        $announcement = $this->handle([
            'title' => $title,
            'description' => $description,
            'icon' => $icon,
            'color' => $color,
            'dismissible' => $dismissible === 'yes',
            'disabled' => $disabled === 'yes',
        ]);

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

        $command->info('Announcement created successfully');
    }
}
