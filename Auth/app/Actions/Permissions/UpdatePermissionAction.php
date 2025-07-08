<?php

namespace Modules\Auth\Actions\Permissions;

use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Auth\Models\Permission;

use function Laravel\Prompts\text;

class UpdatePermissionAction
{
    use AsAction;

    public string $commandSignature = 'auth:permissions.update {name}';

    public string $commandDescription = 'Update an existing permission';

    public function handle(Permission $permission, $data)
    {
        return $permission->update($data);
    }

    public function asJob(Permission $permission, $data)
    {
        $this->handle($permission, $data);
    }

    public function asListener(Permission $permission, $data)
    {
        $this->handle($permission, $data);
    }

    public function asCommand(Command $command)
    {
        $permission = Permission::where('name', $command->argument('name'))->first();
        if (! $permission) {
            $command->error('Permission not found');

            return;
        }

        $name = text('Name', default: $permission->name, required: true, validate: ['unique:permissions,name,'.$permission->id]);
        $guardName = text('Guard Name', default: $permission->guard_name, required: true);

        $this->handle($permission, [
            'name' => $name,
            'guard_name' => $guardName,
        ]);

        $command->info('Permission updated successfully');
    }
}
