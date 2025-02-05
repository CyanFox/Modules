<?php

namespace Modules\Auth\Console\Users;

use Illuminate\Console\Command;
use Modules\Auth\Models\User;

use function Laravel\Prompts\confirm;

class DeleteUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'auth:users.delete {username}';

    /**
     * The console command description.
     */
    protected $description = 'Delete a user.';

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
        if (! $user) {
            $this->error('User not found');

            return;
        }

        $delete = confirm('Are you sure you want to delete this user?');

        if (! $delete) {
            $this->info('User not deleted');

            return;
        }

        $user->delete();

        $this->info('User deleted successfully');
    }
}
