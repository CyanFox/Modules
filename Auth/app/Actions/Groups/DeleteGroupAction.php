<?php

namespace Modules\Auth\Actions\Groups;

use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Auth\Models\Role;

use function Laravel\Prompts\confirm;

class DeleteGroupAction
{
    use AsAction;

    public string $commandSignature = 'auth:groups.delete {name}';

    public string $commandDescription = 'Delete an Group';

    public function handle(Role $role)
    {
        return $role->delete();
    }

    public function asJob(Role $role)
    {
        $this->handle($role);
    }

    public function asListener(Role $role)
    {
        $this->handle($role);
    }

    public function asCommand(Command $command)
    {
        $role = Role::where('name', $command->argument('name'))->first();
        if (! $role) {
            $command->error('Group not found');

            return;
        }

        $delete = confirm('Are you sure you want to delete this group?');

        if (! $delete) {
            $command->info('Group not deleted');

            return;
        }

        $this->handle($role);

        $command->info('Group deleted successfully');
    }
}
