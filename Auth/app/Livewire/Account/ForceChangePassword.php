<?php

namespace Modules\Auth\Livewire\Account;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Facades\UnsplashManager;
use Modules\Auth\Rules\Password;

class ForceChangePassword extends CFComponent
{
    use WithCustomLivewireException;

    public $unsplash;

    public $currentPassword;

    public $newPassword;

    public $confirmPassword;

    public function changePassword()
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => ['required', 'same:confirmPassword', 'not_in:'.$this->currentPassword, new Password],
            'confirmPassword' => 'required|same:newPassword',
        ], [
            'password.same' => __('auth::force.change_password.password_same'),
            'confirmPassword.same' => __('auth::force.change_password.password_same'),
            'newPassword.not_in' => __('auth::force.change_password.old_password_used'),
        ]);

        if (! Hash::check($this->currentPassword, auth()->user()->password)) {
            $this->addError('currentPassword', __('auth.password'));

            return;
        }

        auth()->user()->update([
            'password' => Hash::make($this->newPassword),
            'force_change_password' => false,
        ]);

        auth()->user()->revokeOtherSessions();

        Notification::make()
            ->title(__('auth::force.change_password.notifications.password_changed'))
            ->success()
            ->send();

        auth()->logout();

        return redirect()->route('auth.login');
    }

    public function mount()
    {
        $this->unsplash = UnsplashManager::returnBackground();

        if ($this->unsplash['error'] !== null) {
            $this->log($this->unsplash['error'], 'error');
        }
    }

    public function render()
    {
        return $this->renderView('auth::livewire.account.force-change-password', __('auth::force.change_password.tab_title'), 'auth::components.layouts.auth');
    }
}
