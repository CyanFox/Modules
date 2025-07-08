<?php

namespace Modules\Auth\Actions\Users;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Auth\Models\Permission;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\User;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateUserAction
{
    use AsAction;

    public string $commandSignature = 'auth:users.create';
    public string $commandDescription = 'Create a new user';

    public function handle($data)
    {
        $user = User::create($data);
        $user->generateTwoFASecret();

        return $user;
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
        $firstName = text('First Name', required: true);
        $lastName = text('Last Name', required: true);
        $username = text('Username', required: true, validate: ['unique:users,username']);
        $email = text('Email', required: true, validate: ['email', 'unique:users,email']);
        $password = password('Password', required: true);
        $groups = multiselect(
            'Groups',
            Role::all()->pluck('name')->toArray(),
        );
        $permissions = multiselect(
            'Permissions',
            Permission::all()->pluck('name')->toArray(),
        );

        $user = $this->handle([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole($groups);
        $user->givePermissionTo($permissions);
        $user->generateTwoFASecret();

        $command->info('User created successfully');
    }

}
