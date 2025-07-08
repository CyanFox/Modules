<?php

namespace Modules\Auth\Actions\Users;

use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Auth\Models\User;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class UpdateUserAction
{
    use AsAction;

    public string $commandSignature = 'auth:users.update {username}';
    public string $commandDescription = 'Update an existing user';

    public function handle(User $user, $data)
    {
        return $user->update($data);
    }

    public function asJob(User $user, $data)
    {
        $this->handle($user, $data);
    }

    public function asListener(User $user, $data)
    {
        $this->handle($user, $data);
    }

    public function asCommand(Command $command)
    {
        $user = User::where('username', $command->argument('username'))->first();
        if (! $user) {
            $command->error('User not found');

            return;
        }

        $firstName = text('First Name', default: $user->first_name, required: true);
        $lastName = text('Last Name', default: $user->last_name, required: true);
        $username = text('Username', default: $user->username, required: true, validate: ['unique:users,username,'.$user->id]);
        $email = text('Email', default: $user->email, required: true, validate: ['email', 'unique:users,email,'.$user->id]);
        $password = password('Password', hint: 'Leave empty to keep the same password');
        $groups = multiselect(
            'Groups',
            \Spatie\Permission\Models\Role::all()->pluck('name')->toArray(),
            default: $user->roles->pluck('name')->toArray(),
        );
        $permissions = multiselect(
            'Permissions',
            \Spatie\Permission\Models\Permission::all()->pluck('name')->toArray(),
            default: $user->permissions->pluck('name')->toArray(),
        );

        $this->handle($user, [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'email' => $email,
        ]);

        if ($password) {
            $this->handle($user, ['password' => $user->password]);
        }

        $user->syncRoles($groups);
        $user->syncPermissions($permissions);

        $command->info('User updated successfully');
    }

}
