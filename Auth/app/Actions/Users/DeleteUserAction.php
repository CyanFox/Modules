<?php

namespace Modules\Auth\Actions\Users;

use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Auth\Models\User;
use function Laravel\Prompts\confirm;

class DeleteUserAction
{
    use AsAction;

    public string $commandSignature = 'auth:users.delete {username}';
    public string $commandDescription = 'Delete a user';

    public function handle(User $user)
    {
        return $user->delete();
    }

    public function asJob(User $user)
    {
        $this->handle($user);
    }

    public function asListener(User $user)
    {
        $this->handle($user);
    }

    public function asCommand(Command $command)
    {
        $user = User::where('username', $command->argument('username'))->first();
        if (!$user) {
            $command->error('User not found');

            return;
        }

        $delete = confirm('Are you sure you want to delete this user?');

        if (!$delete) {
            $command->info('User not deleted');

            return;
        }

        $this->handle($user);

        $command->info('User deleted successfully');
    }

}
