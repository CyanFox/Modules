<?php

namespace Modules\Auth\Actions\Groups;

use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Auth\Models\Permission;
use Modules\Auth\Models\Role;
use function Laravel\Prompts\text;

class CreateGroupAction
{
    use AsAction;

    public string $commandSignature = 'auth:groups.create';
    public string $commandDescription = 'Create a new Group';

    public function handle($data)
    {
        return Role::create($data);
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
        $name = text('Name', required: true, validate: ['unique:permissions,name']);
        $guardName = text('Guard Name', default: 'web', required: true);

        $this->handle([
            'name' => $name,
            'guard_name' => $guardName,
        ]);

        $command->info('Group created successfully');
    }

}
