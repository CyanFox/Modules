<?php

namespace Modules\AuthModule\Livewire\Account;

use App\Facades\Utils\UnsplashManager;
use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\AuthModule\Facades\UserManager;

class ForceActivateTwoFactor extends LWComponent
{
    public $unsplash;

    public $password;

    public $twoFactorCode;

    public $captcha;

    public function activateTwoFactor()
    {
        $this->validate([
            'password' => 'required|string',
            'twoFactorCode' => 'required|string',
        ]);

        if (!Hash::check($this->password, Auth::user()->password)) {
            $this->addError('password', __('validation.current_password'));

            return;
        }

        if (!UserManager::getUser(Auth::user())->getTwoFactorManager()->checkTwoFactorCode($this->twoFactorCode, false)) {
            throw ValidationException::withMessages([
                'twoFactorCode' => __('authmodule::account.force_actions.activate_two_factor.two_factor_code_invalid'),
            ]);
        }

        try {
            Auth::user()->update([
                'two_factor_enabled' => true,
                'force_activate_two_factor' => false,
            ]);

            UserManager::getUser(Auth::user())->getTwoFactorManager()->generateRecoveryCodes();
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        UserManager::getUser(Auth::user())->getSessionManager()->revokeOtherSessions();

        Notification::make()
            ->title(__('authmodule::account.force_actions.activate_two_factor.notifications.two_factor_activated'))
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
        return $this->renderView('authmodule::livewire.account.force-activate-two-factor', __('authmodule::account.force_actions.activate_two_factor.tab_title'));
    }
}
