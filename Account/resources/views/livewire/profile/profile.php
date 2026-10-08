<?php

use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Url;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Modules\Core\Facades\UserSettings;
use Modules\Core\Models\Session;
use Modules\Core\Traits\WithConfirmation;
use Modules\Core\Traits\WithPasswordConfirmation;

new class extends Component {
    use WithPasswordConfirmation, WithConfirmation;

    #[Url]
    public string $tab = 'profile';

    public string $theme;
    public string $language;

    public string $firstName;
    public string $lastName;
    public string $username;
    public string $email;

    public string $currentPassword;
    public string $newPassword;
    public string $confirmNewPassword;

    public function enableMfa()
    {
        $this->checkPasswordConfirmation()
            ->passwordModal('account::components.modals.enable-mfa')
            ->checkPassword();
    }

    public function regenerateRecoveryCodes()
    {
        $this->checkPasswordConfirmation()
            ->passwordModal('account::components.modals.regenerate-recovery-codes')
            ->checkPassword();
    }

    public function disableMfa($confirmed = false)
    {
        if ($confirmed) {
            if (!$this->hasPasswordConfirmedSession()) {
                return;
            }

            UserSettings::delete(auth()->id(), 'auth.mfa.recovery_codes');
            UserSettings::delete(auth()->id(), 'auth.mfa.secret');
            UserSettings::delete(auth()->id(), 'auth.mfa.enabled');

            Toaster::success(__('account::account.disable_mfa.notifications.disabled'));

            $this->redirect(url()->previous(), true);
            return;
        }

        $this->dialog()
            ->question(__('account::account.disable_mfa.title'),
                __('account::account.disable_mfa.description'))
            ->confirm(__('account::account.disable_mfa.buttons.disable'), 'warning')
            ->icon('icon-triangle-alert')
            ->needsPasswordConfirmation()
            ->method('disableMfa', true)
            ->send();
    }

    public function updateProfile()
    {
        $this->validate([
            'firstName' => 'nullable|string|max:255',
            'lastName' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
            'email' => 'required|email|max:255',
        ]);

        auth()->user()->update([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->username,
            'email' => $this->email,
        ]);

        Toaster::success(__('account::account.notifications.profile_updated'));

        $this->redirect(url()->previous(), true);
    }

    public function changePassword()
    {
        $this->validate([
            'currentPassword' => 'required|string|current_password',
            'newPassword' => ['required', 'string', Password::defaults()],
            'confirmNewPassword' => ['required', 'string', 'same:newPassword'],
        ]);

        auth()->user()->update([
            'password' => $this->newPassword,
        ]);

        Session::where('user_id', auth()->id())->whereNot('id', session()->getId())->delete();

        Toaster::success(__('account::account.notifications.password_changed'));

        $this->redirect(url()->previous(), true);
    }

    public function updateTheme()
    {
        auth()->user()->update(['theme' => $this->theme]);

        Toaster::success(__('account::account.notifications.theme_updated'));

        $this->redirect(url()->previous(), true);
    }

    public function updateLanguage()
    {
        auth()->user()->update(['language' => $this->language]);

        App::setLocale($this->language);

        Toaster::success(__('account::account.notifications.language_updated'));

        $this->redirect(url()->previous(), true);
    }

    public function mount()
    {
        $this->theme = auth()->user()->theme;
        $this->language = auth()->user()->language;

        $this->firstName = auth()->user()->first_name;
        $this->lastName = auth()->user()->last_name;
        $this->username = auth()->user()->username;
        $this->email = auth()->user()->email;
    }

    public function render()
    {
        return $this->view()
            ->layout('dashboard::layouts.app', ['breadcrumbs' => [['label' => __('account::account.account'), 'url' => route('account.profile')], ['label' => __('account::account.profile'), 'last' => true]]])
            ->title(__('account::account.profile'));
    }
};
