<?php

namespace Modules\Auth\Actions\Permissions;

use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Auth\Models\Permission;
use function Laravel\Prompts\confirm;

class DeletePermissionAction
{
    use AsAction;

    public string $commandSignature = 'auth:permissions.delete {name}';
    public string $commandDescription = 'Delete an permission';

    public function handle(Permission $permission)
    {
        return $permission->delete();
    }

    public function asJob(Permission $permission)
    {
        $this->handle($permission);
    }

    public function asListener(Permission $permission)
    {
        $this->handle($permission);
    }

    public function asCommand(Command $command)
    {
        $permission = Permission::where('name', $command->argument('name'))->first();
        if (!$permission) {
            $command->error('Permission not found');

            return;
        }

        $delete = confirm('Are you sure you want to delete this permission?');

        if (!$delete) {
            $command->info('Permission not deleted');

            return;
        }

        $this->handle($permission);

        $command->info('Permission deleted successfully');
    }

}
