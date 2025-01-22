<?php

namespace Modules\Auth\Livewire\Account;

use App\Livewire\CFComponent;
use App\Traits\WithConfirmation;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Url;
use Modules\Auth\Rules\Password;
use Modules\Auth\Traits\WithPasswordConfirmation;

class Profile extends CFComponent
{
    use WithCustomLivewireException, WithPasswordConfirmation, WithConfirmation;

    #[Url]
    public $tab;

    public $firstName;
    public $lastName;
    public $username;
    public $email;

    public $language;
    public $theme;

    public $currentPassword;
    public $newPassword;
    public $confirmPassword;

    public function updateLanguageAndTheme()
    {
        $this->validate([
            'language' => 'nullable|string',
            'theme' => 'nullable|string|in:light,dark',
        ]);

        auth()->user()->update([
            'language' => $this->language ?? 'en',
            'theme' => $this->theme ?? 'light',
        ]);


        Notification::make()
            ->title(__('auth::profile.notifications.profile_updated'))
            ->success()
            ->send();

        $this->redirect(route('account.profile'));
    }

    public function updateProfile()
    {
        $this->validate([
            'firstName' => 'nullable|string',
            'lastName' => 'nullable|string',
            'username' => 'required|string',
            'email' => 'required|email',
        ]);

        auth()->user()->update([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->username,
            'email' => $this->email,
        ]);

        Notification::make()
            ->title(__('auth::profile.notifications.profile_updated'))
            ->success()
            ->send();

        $this->redirect(route('account.profile'), true);
    }

    public function updatePassword()
    {
        if (auth()->user()->password === null) {
            $this->validate([
                'newPassword' => ['required', new Password],
                'confirmPassword' => 'required|same:newPassword',
            ]);
        } else {
            $this->validate([
                'currentPassword' => 'required',
                'newPassword' => ['required', new Password],
                'confirmPassword' => 'required|same:newPassword',
            ]);

            if (!Hash::check($this->currentPassword, auth()->user()->password)) {
                $this->addError('currentPassword', __('validation.current_password'));

                return;
            }
        }

        auth()->user()->update([
            'password' => Hash::make($this->newPassword),
        ]);

        Notification::make()
            ->title(__('auth::profile.notifications.password_updated'))
            ->success()
            ->send();

        $this->redirect(route('account.profile'), true);
    }

    public function disableTwoFA($confirmed = false)
    {
        if (!$confirmed) {
            $this->dialog()
                ->question(__('auth::profile.modals.disable_two_fa.title'),
                    __('auth::profile.modals.disable_two_fa.description'))
                ->icon('icon-triangle-alert')
                ->needsPasswordConfirmation()
                ->confirm(__('auth::profile.modals.disable_two_fa.buttons.disable'), 'danger')
                ->method('disableTwoFA', true)
                ->send();

            return;
        }

        auth()->user()->update([
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
        ]);

        auth()->user()->generateTwoFASecret();
        auth()->user()->recoveryCodes()->delete();

        Notification::make()
            ->title(__('auth::profile.modals.disable_two_fa.notifications.two_fa_disabled'))
            ->success()
            ->send();

        $this->redirect(route('account.profile'), true);
    }

    public function deleteAccount($confirmed = false)
    {
        if (!$confirmed) {
            $this->dialog()
                ->question(__('auth::profile.modals.delete_account.title'),
                    __('auth::profile.modals.delete_account.description'))
                ->icon('icon-triangle-alert')
                ->needsPasswordConfirmation()
                ->confirm(__('messages.buttons.delete'), 'danger')
                ->method('deleteAccount', true)
                ->send();

            return;
        }

        auth()->user()->delete();

        Notification::make()
            ->title(__('auth::profile.modals.delete_account.notifications.account_deleted'))
            ->success()
            ->send();

        $this->redirect(route('auth.logout'));
    }

    public function logoutSession($sessionId)
    {
        if (!$this->checkPasswordConfirmation()->passwordMethod('logoutSession', $sessionId)->checkPassword()) {
            return;
        }

        auth()->user()->deleteSession($sessionId);

        Notification::make()
            ->title(__('auth::profile.sessions.notifications.logged_out'))
            ->success()
            ->send();

        $this->redirect(route('account.profile'), true);
    }

    public function logoutAllSessions($confirmed = false)
    {
        if (!$confirmed) {
            $this->dialog()
                ->question(__('auth::profile.sessions.modals.logout_all.title'),
                    __('auth::profile.sessions.modals.logout_all.description'))
                ->icon('icon-triangle-alert')
                ->needsPasswordConfirmation()
                ->confirm(__('messages.buttons.confirm'), 'danger')
                ->method('logoutAllSessions', true)
                ->send();

            return;
        }

        auth()->user()->revokeOtherSessions();

        Notification::make()
            ->title(__('auth::profile.sessions.notifications.logged_out_all'))
            ->success()
            ->send();

        $this->redirect(route('account.profile'), true);
    }

    public function mount()
    {
        if (empty($this->tab)) {
            $this->tab = 'overview';
        }

        $user = auth()->user();

        $this->firstName = $user->first_name;
        $this->lastName = $user->last_name;
        $this->username = $user->username;
        $this->email = $user->email;

        $this->language = $user->language;
        $this->theme = $user->theme;

        $this->currentPassword = '';
        $this->newPassword = '';
        $this->confirmPassword = '';
    }

    public function render()
    {
        return $this->renderView('auth::livewire.account.profile', __('auth::profile.tab_title'), settings('auth.profile.layout', config('auth.profile.layout')));
    }
}
