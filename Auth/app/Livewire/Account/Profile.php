<?php

namespace Modules\Auth\Livewire\Account;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Modules\Auth\Actions\Users\DeleteUserAction;
use Modules\Auth\Actions\Users\UpdateUserAction;
use Modules\Auth\Models\User;
use Modules\Auth\Rules\Password;
use Modules\Auth\Traits\WithConfirmation;
use Modules\Auth\Traits\WithPasswordConfirmation;
use Spatie\Activitylog\Models\Activity;

class Profile extends CFComponent
{
    use WithConfirmation, WithCustomLivewireException, WithPasswordConfirmation;

    #[Url]
    public $tab;

    #[Url]
    public $passTab = 'password';

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

        UpdateUserAction::run(auth()->user(), [
            'language' => $this->language ?? 'en',
            'theme' => $this->theme ?? 'light',
        ]);

        App::setLocale($this->language ?? 'en');

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

        UpdateUserAction::run(auth()->user(), [
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

            if (! Hash::check($this->currentPassword, auth()->user()->password)) {
                $this->addError('currentPassword', __('validation.current_password'));

                return;
            }
        }

        UpdateUserAction::run(auth()->user(), [
            'password' => $this->newPassword,
        ]);

        Notification::make()
            ->title(__('auth::profile.notifications.password_updated'))
            ->success()
            ->send();

        $this->redirect(route('account.profile'), true);
    }

    public function disableTwoFA($confirmed = false)
    {
        if (! $confirmed) {
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

        UpdateUserAction::run(auth()->user(), [
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
        if (! settings('auth.profile.enable.delete_account')) {
            return;
        }

        if (! $confirmed) {
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

        if (!DeleteUserAction::run(auth()->user())) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();
            return;
        }

        Notification::make()
            ->title(__('auth::profile.modals.delete_account.notifications.account_deleted'))
            ->success()
            ->send();

        $this->redirect(route('auth.logout'));
    }

    public function logoutSession($sessionId)
    {
        if (! $this->checkPasswordConfirmation()->passwordFunction('logoutSession', $sessionId)->checkPassword()) {
            return;
        }

        auth()->user()->deleteSession($sessionId);

        Notification::make()
            ->title(__('auth::profile.sessions.notifications.logged_out'))
            ->success()
            ->send();

        $this->redirect(route('account.profile', ['tab' => 'sessions']), true);
    }

    public function logoutAllSessions($confirmed = false)
    {
        if (! $confirmed) {
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

        $this->redirect(route('account.profile', ['tab' => 'sessions']), true);
    }

    public function getActivitiesProperty()
    {
        return Activity::where([
            'subject_id' => auth()->id(),
            'subject_type' => User::class,
        ])->orderByDesc('id')->paginate(10);
    }

    public function mount()
    {
        if (empty($this->tab)) {
            $this->tab = 'overview';
        }

        if (empty($this->passTab)) {
            $this->passTab = 'password';
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

    #[On('refreshProfile')]
    public function render()
    {
        return $this->renderView('auth::livewire.account.profile', __('auth::profile.tab_title'), settings('auth.profile.layout', config('auth.profile.layout')));
    }
}
