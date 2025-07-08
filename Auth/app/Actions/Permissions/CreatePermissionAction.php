<?php

namespace Modules\Auth\Actions\Permissions;

use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Auth\Models\Permission;
use function Laravel\Prompts\text;

class CreatePermissionAction
{
    use AsAction;

    public string $commandSignature = 'auth:permissions.create';
    public string $commandDescription = 'Create a new permission';

    public function handle($data)
    {
        return Permission::create($data);
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

        $command->info('Permission created successfully');
    }

}
