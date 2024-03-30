<?php

namespace Modules\Actions\app\Livewire\Admin\Actions;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Symfony\Component\Process\Process;

class Actions extends Component
{
    public $output = '';

    public function clearCache(): void
    {
        Artisan::call('cache:clear');
        $this->output = Artisan::output();
    }

    public function clearViewCache(): void
    {
        Artisan::call('view:clear');
        $this->output = Artisan::output();
    }

    public function clearActivityLog(): void
    {
        Artisan::call('c:admin:activity.prune --days=0 --keep=0');
        $this->output = Artisan::output();
    }

    public function npmInstall(): void
    {
        $process = Process::fromShellCommandline('npm install');
        $process->run();

        $this->output = $process->getOutput();
    }

    public function npmBuild(): void
    {
        $process = Process::fromShellCommandline('npm run build');
        $process->run();

        $this->output = $process->getOutput();
    }

    public function render()
    {
        return view('actions::livewire.admin.actions.actions')
            ->layout('components.layouts.admin', ['title' => __('actions::actions.tab_title')]);
    }
}
