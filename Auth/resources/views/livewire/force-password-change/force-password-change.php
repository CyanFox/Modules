<?php

use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Modules\Auth\Facades\Unsplash;
use Modules\Core\Facades\UserSettings;
use Modules\Core\Models\Session;

new class extends Component
{
    public $unsplash;

    public $currentPassword;

    public $newPassword;

    public $confirmNewPassword;

    public function changePassword()
    {
        if (filled(auth()->user()->password)) {
            $this->validate([
                'currentPassword' => 'required|string|current_password',
            ]);
        }

        $this->validate([
            'newPassword' => ['required', 'string', Password::defaults(), 'different:currentPassword'],
            'confirmNewPassword' => ['required', 'string', 'same:newPassword'],
        ]);

        auth()->user()->update([
            'password' => $this->newPassword,
        ]);

        UserSettings::delete(auth()->id(), 'auth.force_password_change');
        Session::where('user_id', auth()->id())->whereNot('id', session()->getId())->delete();

        Toaster::success(__('auth::force.change_password.notifications.changed'));

        $this->redirectIntended('/', true);
    }

    public function mount()
    {
        $this->unsplash = Unsplash::returnBackground();
    }

    public function render()
    {
        return $this->view()
            ->layout('auth::layouts.guest')
            ->title(__('auth::force.change_password.tab_title'));
    }
};
