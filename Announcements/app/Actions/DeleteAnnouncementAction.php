<?php

namespace Modules\Announcements\app\Actions;

use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Announcements\Models\Announcement;

use function Laravel\Prompts\confirm;

class DeleteAnnouncementAction
{
    use AsAction;

    public string $commandSignature = 'announcements:delete {id}';

    public string $commandDescription = 'Delete an Announcement';

    public function handle(Announcement $announcement)
    {
        return $announcement->delete();
    }

    public function asJob(Announcement $announcement)
    {
        $this->handle($announcement);
    }

    public function asListener(Announcement $announcement)
    {
        $this->handle($announcement);
    }

    public function asCommand(Command $command)
    {
        $announcement = Announcement::find($command->argument('id'));
        if (! $announcement) {
            $command->error('Announcement not found');

            return;
        }

        $delete = confirm('Are you sure you want to delete this announcement?');

        if (! $delete) {
            $command->info('Announcement not deleted');

            return;
        }

        $this->handle($announcement);

        $command->info('Announcement deleted successfully');
    }
}
