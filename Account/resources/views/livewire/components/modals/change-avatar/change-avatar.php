<?php

use Livewire\WithFileUploads;
use Modules\Core\Facades\UserSettings;
use RealZone22\PenguBlade\ModalComponent;

new class extends ModalComponent {
    use WithFileUploads;

    public $currentAvatar;
    public $customAvatarUrl;
    public $avatar;

    public function updated($propertyName)
    {
        if ($propertyName === 'customAvatarUrl') {
            if (blank($this->customAvatarUrl)) {
                if ($this->avatar) {
                    $this->currentAvatar = $this->avatar->temporaryUrl();
                    return;
                }
                $this->currentAvatar = auth()->user()->getAvatar();
                return;
            }
            $this->currentAvatar = $this->customAvatarUrl;
        }
        if ($propertyName === 'avatar') {
            $this->currentAvatar = $this->avatar->temporaryUrl();
        }
    }

    public function changeAvatar()
    {
        $this->validate([
            'customAvatarUrl' => 'nullable|url',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:6144',
        ]);

        if ($this->customAvatarUrl) {
            auth()->user()->clearMediaCollection('avatar');

            UserSettings::set(auth()->id(), 'core.custom_avatar_url', $this->customAvatarUrl, true);

            Toaster::success(__('account::modals.change_avatar.notifications.changed'));

            $this->redirect(url()->previous(), true);
            return;
        }

        auth()->user()->clearMediaCollection('avatar');
        auth()->user()->addMedia($this->avatar->getRealPath())->toMediaCollection('avatar');

        Toaster::success(__('account::modals.change_avatar.notifications.changed'));

        $this->redirect(url()->previous(), true);
    }

    public function resetAvatar()
    {
        auth()->user()->clearMediaCollection('avatar');
        UserSettings::delete(auth()->id(), 'core.custom_avatar_url');


        Toaster::success(__('account::modals.change_avatar.notifications.reset'));

        $this->redirect(url()->previous(), true);
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function mount()
    {
        if (!settings('account.enable.change_avatar')) {
            abort(403);
        }

        $this->currentAvatar = auth()->user()->getAvatar();
        $this->customAvatarUrl = userSettings('core.custom_avatar_url');
    }
};
