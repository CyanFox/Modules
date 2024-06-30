<?php

namespace Modules\AuthModule\Livewire\Account;

use App\Facades\ModuleManager;
use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Models\UserRecoveryCode;
use Modules\AuthModule\Rules\Password;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Profile extends LWComponent
{
    use WithFileUploads;

    #[Url]
    public $tab;

    public $activateTwoFactorModal;

    public $activateTwoFactorPassword;

    public $activateTwoFactorCode;

    public $showRecoveryCodesModal;

    public $showRecoveryCodes;

    public $showChangeAvatarModal;

    public $avatarFile;

    public $avatarUrl;

    public $language;

    public $theme;

    public $firstName;

    public $lastName;

    public $username;

    public $email;

    public $currentPassword;

    public $newPassword;

    public $newPasswordConfirmation;

    public function updateLanguageAndTheme()
    {
        $this->validate([
            'language' => 'required|in:en,de',
            'theme' => 'required|in:light,dark',
        ]);

        Auth::user()->update([
            'language' => $this->language,
            'theme' => $this->theme,
        ]);

        Notification::make()
            ->title(__('authmodule::account.overview.language_and_theme.notifications.language_and_theme_updated'))
            ->success()
            ->send();

        $this->redirect(route('account.profile'));
    }

    public function updateProfile()
    {
        $this->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'username' => 'required',
            'email' => 'required|email',
        ]);

        try {
            Auth::user()->update([
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'username' => $this->username,
                'email' => $this->email,
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
            ->title(__('authmodule::account.overview.profile.notifications.profile_updated'))
            ->success()
            ->send();

        $this->redirect(route('account.profile'), navigate: true);
    }

    public function updatePassword()
    {
        if (Auth::user()->password === null) {
            $this->validate([
                'newPassword' => ['required', new Password],
                'newPasswordConfirmation' => 'required|same:newPassword',
            ]);
        } else {
            $this->validate([
                'currentPassword' => 'required',
                'newPassword' => ['required', new Password],
                'newPasswordConfirmation' => 'required|same:newPassword',
            ]);

            if (!Hash::check($this->currentPassword, Auth::user()->password)) {
                $this->addError('currentPassword', __('validation.current_password'));

                return;
            }
        }

        try {
            Auth::user()->update([
                'password' => Hash::make($this->newPassword),
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
            ->title(__('authmodule::account.overview.password.notifications.password_updated'))
            ->success()
            ->send();

        $this->redirect(route('account.profile'), navigate: true);

    }

    // Modals & Dialogs
    public function activateTwoFactor()
    {
        $this->validate([
            'activateTwoFactorPassword' => 'required',
            'activateTwoFactorCode' => 'required',
        ]);

        if (!Hash::check($this->activateTwoFactorPassword, Auth::user()->password)) {
            $this->addError('activateTwoFactorPassword', __('validation.current_password'));

            return;
        }

        if (!UserManager::getUser(Auth::user())->getTwoFactorManager()->checkTwoFactorCode($this->activateTwoFactorCode, false)) {
            throw ValidationException::withMessages([
                'activateTwoFactorCode' => __('authmodule::account.overview.actions.modals.activate_two_factor.two_factor_code_invalid'),
            ]);
        }

        try {
            Auth::user()->update([
                'two_factor_enabled' => true,
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
            ->title(__('authmodule::account.overview.actions.modals.activate_two_factor.notifications.two_factor_activated'))
            ->success()
            ->send();

        $this->activateTwoFactorModal = false;
    }

    public function deleteAccount($confirmed = false)
    {
        if (!setting('authmodule.enable.delete_account')) {
            return;
        }
        if ($confirmed) {
            Auth::user()->delete();

            Notification::make()
                ->title(__('authmodule::account.overview.actions.dialogs.delete_account.notifications.account_deleted'))
                ->success()
                ->send();

            $this->redirect(route('auth.login'));

            return;
        }

        $this->dialog()
            ->error(__('authmodule::account.overview.actions.dialogs.delete_account.title'),
                __('authmodule::account.overview.actions.dialogs.delete_account.description'))
            ->confirm(__('authmodule::account.overview.actions.dialogs.delete_account.buttons.delete_account'), 'deleteAccount', [true])
            ->cancel()
            ->send();
    }

    public function disableTwoFactor($confirmed = false)
    {
        if ($confirmed) {
            Auth::user()->update([
                'two_factor_enabled' => false,
            ]);

            UserManager::getUser(Auth::user())->getSessionManager()->revokeOtherSessions();

            Notification::make()
                ->title(__('authmodule::account.overview.actions.dialogs.disable_two_factor.notifications.two_factor_disabled'))
                ->success()
                ->send();

            return;
        }

        $this->dialog()
            ->warning(__('authmodule::account.overview.actions.dialogs.disable_two_factor.title'),
                __('authmodule::account.overview.actions.dialogs.disable_two_factor.description'))
            ->confirm(__('authmodule::account.overview.actions.dialogs.disable_two_factor.buttons.disable_two_factor'), 'disableTwoFactor', [true])
            ->cancel()
            ->send();

    }

    public function regenerateRecoveryCodes(): void
    {
        $this->showRecoveryCodes = [];

        $this->showRecoveryCodes = UserManager::getUser(Auth::user())->getTwoFactorManager()->generateRecoveryCodes();
    }

    public function downloadRecoveryCodes(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $recoveryCodes = implode(PHP_EOL, $this->showRecoveryCodes);

            echo $recoveryCodes;
        }, 'recovery-codes.txt');
    }

    public function changeAvatar()
    {
        if ($this->avatarFile) {
            $this->validate([
                'avatarFile' => 'image|max:10000',
            ]);

            try {
                $this->avatarFile->storeAs('public/avatars', Auth::user()->id . '.png');
            } catch (Exception $e) {
                Notification::make()
                    ->title(__('messages.notifications.something_went_wrong'))
                    ->danger()
                    ->send();

                $this->dispatch('logger', ['type' => 'error', 'message' => $e->getMessage()]);

                return;
            }

            Notification::make()
                ->title(__('authmodule::account.change_avatar.notifications.avatar_changed'))
                ->success()
                ->send();

            $this->redirect(route('account.profile'), navigate: true);

            return;
        }

        if ($this->avatarUrl) {

            try {
                Auth::user()->update([
                    'custom_avatar_url' => $this->avatarUrl,
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
                ->title(__('authmodule::account.change_avatar.notifications.avatar_changed'))
                ->success()
                ->send();

            $this->redirect(route('account.profile'), navigate: true);
        }

    }

    public function resetAvatar(): void
    {
        Storage::disk('public')->delete('avatars/' . Auth::user()->id . '.png');

        try {
            Auth::user()->update([
                'custom_avatar_url' => null,
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
            ->title(__('authmodule::account.change_avatar.notifications.avatar_changed'))
            ->success()
            ->send();

        $this->redirect(route('account.profile'), navigate: true);
    }

    public function mount()
    {
        $this->tab = __('authmodule::account.tabs.overview');
        $this->language = Auth::user()->language;
        $this->theme = Auth::user()->theme;
        $this->firstName = Auth::user()->first_name;
        $this->lastName = Auth::user()->last_name;
        $this->username = Auth::user()->username;
        $this->email = Auth::user()->email;
        $this->avatarUrl = Auth::user()->custom_avatar_url;

        $this->showRecoveryCodes = UserRecoveryCode::where('user_id', Auth::user()->id)->get()->pluck('code')->toArray();
        if (empty($this->recoveryCodes)) {
            $this->showRecoveryCodes = UserManager::getUser(Auth::user())->getTwoFactorManager()->generateRecoveryCodes();
        }
    }

    public function render()
    {
        if (setting('authmodule.layouts.profile') !== null) {
            return $this->renderView('authmodule::livewire.account.profile', __('authmodule::account.tab_title'), setting('authmodule.layouts.profile'));
        }

        if (ModuleManager::getModule('DashboardModule')->isEnabled()) {
            return $this->renderView('authmodule::livewire.account.profile', __('authmodule::account.tab_title'), 'dashboardmodule::components.layouts.app');
        }

        return $this->renderView('authmodule::livewire.account.profile', __('authmodule::account.tab_title'), 'authmodule::components.layouts.app');
    }
}
