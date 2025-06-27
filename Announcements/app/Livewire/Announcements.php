<?php

namespace Modules\Announcements\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Session;
use Livewire\Component;
use Modules\Announcements\Models\Announcement;

class Announcements extends Component
{
    public $announcements = [];

    #[Session]
    public $showDismissed = false;

    public function downloadFile($announcementId, $file)
    {
        $announcement = Announcement::find($announcementId);

        if ($announcement) {
            if (preg_match('/[\/:*?"<>|]/', $file)) {
                return null;
            }

            return Storage::disk('local')->download('announcements/'.$announcementId.'/'.$file);
        }

        return null;
    }

    public function dismissAnnouncement($announcementId, $dismissed = true)
    {
        $announcement = Announcement::find($announcementId);

        if ($announcement) {
            if (! $dismissed) {
                $announcement->dismissed()->where('user_id', auth()->id())->delete();
            } else {
                $announcement->dismissed()->create([
                    'user_id' => auth()->id(),
                ]);
            }

            $this->redirect(route('dashboard'), true);
        }
    }

    public function toggleShowDismissed()
    {
        $this->showDismissed = ! $this->showDismissed;

        $this->mount();
    }

    public function mount()
    {
        if ($this->showDismissed) {
            $this->announcements = Announcement::query()
                ->where('disabled', false)
                ->where(function ($query) {
                    $query->whereHas('access', function ($q) {
                        $q->where(function ($subquery) {
                            $subquery->where('user_id', auth()->id())
                                ->orWhereIn('group_id', auth()->user()->roles->pluck('id'))
                                ->orWhereIn('permission_id', auth()->user()->permissions->pluck('id'));
                        });
                    })
                        ->orWhereDoesntHave('access');
                })
                ->with(['access'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $this->announcements = Announcement::query()
                ->where('disabled', false)
                ->whereDoesntHave('dismissed', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->where(function ($query) {
                    $query->whereHas('access', function ($q) {
                        $q->where(function ($subquery) {
                            $subquery->where('user_id', auth()->id())
                                ->orWhereIn('group_id', auth()->user()->roles->pluck('id'))
                                ->orWhereIn('permission_id', auth()->user()->permissions->pluck('id'));
                        });
                    })
                        ->orWhereDoesntHave('access');
                })
                ->with(['access'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }

    public function render()
    {
        return view('announcements::livewire.announcements');
    }
}
