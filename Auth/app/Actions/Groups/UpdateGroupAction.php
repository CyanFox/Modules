<?php

namespace Modules\Auth\Actions\Groups;

use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Auth\Models\Role;

use function Laravel\Prompts\text;

class UpdateGroupAction
{
    use AsAction;

    public string $commandSignature = 'auth:groups.update {name}';

    public string $commandDescription = 'Update an existing group';

    public function handle(Role $role, $data)
    {
        return $role->update($data);
    }

    public function asJob(Role $role, $data)
    {
        $this->handle($role, $data);
    }

    public function asListener(Role $role, $data)
    {
        $this->handle($role, $data);
    }

    public function asCommand(Command $command)
    {
        $role = Role::where('name', $command->argument('name'))->first();
        if (! $role) {
            $command->error('Group not found');

            return;
        }

        $name = text('Name', default: $role->name, required: true, validate: ['unique:roles,name,'.$role->id]);
        $guardName = text('Guard Name', default: $role->guard_name, required: true);

        $this->handle($role, [
            'name' => $name,
            'guard_name' => $guardName,
        ]);

        $command->info('Group updated successfully');
    }
}
