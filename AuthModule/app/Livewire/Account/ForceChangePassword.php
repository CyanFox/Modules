<?php

namespace Modules\AuthModule\Livewire\Account;

use App\Facades\Utils\UnsplashManager;
use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\AuthModule\Rules\Password;

class ForceChangePassword extends LWComponent
{
    public $unsplash;

    public $currentPassword;

    public $newPassword;

    public $newPasswordConfirmation;

    public $captcha;

    public function updatePassword()
    {
        if (Auth::user()->password !== null) {
            $this->validate([
                'currentPassword' => 'required',
                'newPassword' => ['required', 'different:currentPassword', new Password],
                'newPasswordConfirmation' => 'required|same:newPassword',
            ]);
        } else {
            $this->validate([
                'newPassword' => 'required',
                'newPasswordConfirmation' => 'required|same:newPassword',
            ]);
        }

        if (Hash::check($this->currentPassword, Auth::user()->password) === false) {
            $this->addError('currentPassword', __('validation.current_password'));

            return;
        }

        try {
            Auth::user()->update([
                'password' => Hash::make($this->newPassword),
                'force_change_password' => false,
            ]);
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        Notification::make()
            ->title(__('authmodule::account.force_actions.change_password.notifications.password_changed'))
            ->success()
            ->send();

        Auth::logout();
        $this->redirect(route('auth.login'), navigate: true);
    }

    public function mount(): void
    {
        $unsplash = UnsplashManager::returnBackground();

        $this->unsplash = $unsplash;

        if ($unsplash['error'] != null) {
            $this->log($unsplash['error'], 'error');
        }

    }

    public function render()
    {
        return $this->renderView('authmodule::livewire.account.force-change-password', __('authmodule::account.force_actions.change_password.tab_title'));
    }
}
