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
        if ($this->avatar) {
            $this->validate([
                'avatar' => 'image|max:10000',
            ]);

            $this->avatar->storeAs('avatars', auth()->id() . '.png', 'public');

            Notification::make()
                ->title(__('auth::profile.modals.change_avatar.notifications.avatar_changed'))
                ->success()
                ->send();

            $this->redirect(url()->previous(), true);

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

            $this->redirect(url()->previous(), true);
        }

    }

    public function resetAvatar()
    {
        Storage::disk('public')->delete('avatars/' . auth()->id() . '.png');

        auth()->user()->update([
            'custom_avatar_url' => null,
        ]);


        Notification::make()
            ->title(__('auth::profile.modals.change_avatar.notifications.avatar_reset'))
            ->success()
            ->send();

        $this->redirect(url()->previous(), true);
    }

    public function mount()
    {
        $this->avatarUrl = auth()->user()->custom_avatar_url;
    }

    public function render()
    {
        return view('auth::livewire.components.modals.change-avatar');
    }
}
