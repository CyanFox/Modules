<?php

namespace Modules\AuthModule\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Modules\AuthModule\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class UpdateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'authmodule:update-user {username}';

    /**
     * The console command description.
     */
    protected $description = 'Update a user';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('username', $this->argument('username'))->first();
        if (!$user) {
            $this->error('User not found');
            return;
        }

        $firstName = text('First Name', required: true, default: $user->first_name);
        $lastName = text('Last Name', required: true, default: $user->last_name);
        $username = text('Username', required: true, validate: ['unique:users,username,' . $user->id], default: $user->username);
        $email = text('Email', required: true, validate: ['email', 'unique:users,email,' . $user->id], default: $user->email);
        $password = password('Password', hint: 'Leave empty to keep the same password');
        $groups = multiselect(
            'Groups',
            Role::all()->pluck('name')->toArray(),
            default: $user->roles->pluck('name')->toArray(),
        );
        $permissions = multiselect(
            'Permissions',
            Permission::all()->pluck('name')->toArray(),
            default: $user->permissions->pluck('name')->toArray(),
        );

        $user->update([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'email' => $email,
        ]);

        if ($password) {
            $user->password = Hash::make($password);
        }

        $user->syncRoles($groups);
        $user->syncPermissions($permissions);

        $this->info('User updated successfully');
    }
}
