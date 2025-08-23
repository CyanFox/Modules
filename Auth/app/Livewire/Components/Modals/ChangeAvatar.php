<?php

namespace Modules\Auth\Livewire\Components\Modals;

use App\Livewire\CFModalComponent;
use App\Traits\WithCustomLivewireException;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Modules\Auth\Actions\Users\UpdateUserAction;

class ChangeAvatar extends CFModalComponent
{
    use WithCustomLivewireException, WithFileUploads;

    public $avatarUrl;

    public $avatar;

    public function changeAvatar()
    {
        if (! settings('auth.profile.enable.change_avatar')) {
            return;
        }

        if ($this->avatar) {
            $this->validate([
                'avatar' => 'image:allow_svg|max:10000',
            ]);

            $this->avatar->storeAs('avatars', auth()->id().'.png', 'public');

            Toaster::success(__('auth::profile.modals.change_avatar.notifications.avatar_changed'));

            $this->redirect(route('account.profile'), true);

            return;
        }

        if ($this->avatarUrl) {

            UpdateUserAction::run(auth()->user(), [
                'custom_avatar_url' => htmlspecialchars($this->avatarUrl),
            ]);

            Toaster::success(__('auth::profile.modals.change_avatar.notifications.avatar_changed'));

            $this->redirect(route('account.profile'), true);
        }

    }

    public function resetAvatar()
    {
        if (! settings('auth.profile.enable.change_avatar')) {
            return;
        }

        Storage::disk('public')->delete('avatars/'.auth()->id().'.png');

        UpdateUserAction::run(auth()->user(), [
            'custom_avatar_url' => null,
        ]);

        Toaster::success(__('auth::profile.modals.change_avatar.notifications.avatar_reset'));

        $this->redirect(route('account.profile'), true);
    }

    public function mount()
    {
        if (! settings('auth.profile.enable.change_avatar')) {
            abort(403);
        }

        $this->avatarUrl = auth()->user()->custom_avatar_url;
    }

    public function render()
    {
        return view('auth::livewire.components.modals.change-avatar');
    }
}
