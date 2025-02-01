<?php

namespace Modules\Auth\Livewire\Components\Modals;

use App\Livewire\CFModalComponent;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class ChangeAvatar extends CFModalComponent
{
    use WithCustomLivewireException, WithFileUploads;

    public $avatarUrl;

    public $avatar;

    public function changeAvatar()
    {
        if (!settings('auth.profile.enable.change_avatar')) {
            return;
        }

        if ($this->avatar) {
            $this->validate([
                'avatar' => 'image|max:10000',
            ]);

            $this->avatar->storeAs('avatars', auth()->id() . '.png', 'public');

            Notification::make()
                ->title(__('auth::profile.modals.change_avatar.notifications.avatar_changed'))
                ->success()
                ->send();

            $this->redirect(route('account.profile'), true);

            return;
        }

        if ($this->avatarUrl) {

            auth()->user()->update([
                'custom_avatar_url' => htmlspecialchars($this->avatarUrl),
            ]);

            Notification::make()
                ->title(__('auth::profile.modals.change_avatar.notifications.avatar_changed'))
                ->success()
                ->send();

            $this->redirect(route('account.profile'), true);
        }

    }

    public function resetAvatar()
    {
        if (!settings('auth.profile.enable.change_avatar')) {
            return;
        }

        Storage::disk('public')->delete('avatars/' . auth()->id() . '.png');

        auth()->user()->update([
            'custom_avatar_url' => null,
        ]);


        Notification::make()
            ->title(__('auth::profile.modals.change_avatar.notifications.avatar_reset'))
            ->success()
            ->send();

        $this->redirect(route('account.profile'), true);
    }

    public function mount()
    {
        if (!settings('auth.profile.enable.change_avatar')) {
            abort(403);
        }

        $this->avatarUrl = auth()->user()->custom_avatar_url;
    }

    public function render()
    {
        return view('auth::livewire.components.modals.change-avatar');
    }
}
