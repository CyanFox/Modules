<?php

namespace Modules\AuthModule\Livewire;

use App\Facades\Utils\SettingsManager;
use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;

class AdminSettings extends LWComponent
{
    #[Url]
    public $tab;

    public $enableCaptcha;

    public $enableRegister;

    public $enableForgotPassword;

    public $forgotPasswordEmailTitle;

    public $forgotPasswordEmailSubject;

    public $forgotPasswordEmailContent;

    public $defaultAvatarUrl;

    public $allowChangeAvatar;

    public $allowDeleteAccount;

    public $passwordMinLength;

    public $passwordBlacklist;

    public $passwordRequireNumber;

    public $passwordRequireSpecialCharacter;

    public $passwordRequireUppercase;

    public $passwordRequireLowercase;

    public function updateAuthSettings()
    {
        if (Auth::user()->cannot('adminmodule.settings.update')) {
            return;
        }

        $this->validate([
            'enableCaptcha' => 'required|boolean',
            'enableRegister' => 'required|boolean',
            'enableForgotPassword' => 'required|boolean',
            'passwordMinLength' => 'required|integer|min:1|max:200',
            'passwordBlacklist' => 'nullable|string',
            'passwordRequireNumber' => 'nullable|boolean',
            'passwordRequireSpecialCharacter' => 'nullable|boolean',
            'passwordRequireUppercase' => 'nullable|boolean',
            'passwordRequireLowercase' => 'nullable|boolean',
        ]);

        $settings = [
            'authmodule.enable.captcha' => $this->enableCaptcha,
            'authmodule.enable.register' => $this->enableRegister,
            'authmodule.enable.forgot_password' => $this->enableForgotPassword,
            'authmodule.password.minimum_length' => $this->passwordMinLength,
            'authmodule.password.blacklist' => $this->passwordBlacklist,
            'authmodule.password.require.numbers' => $this->passwordRequireNumber,
            'authmodule.password.require.special_characters' => $this->passwordRequireSpecialCharacter,
            'authmodule.password.require.uppercase' => $this->passwordRequireUppercase,
            'authmodule.password.require.lowercase' => $this->passwordRequireLowercase,
        ];

        try {
            SettingsManager::updateSettings($settings);
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        Notification::make()
            ->title(__('authmodule::settings.notifications.settings_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.modules.settings.authmodule'));
    }

    public function updateEmailSettings()
    {
        if (Auth::user()->cannot('adminmodule.settings.update')) {
            return;
        }

        $this->validate([
            'forgotPasswordEmailTitle' => 'required|string',
            'forgotPasswordEmailSubject' => 'required|string',
            'forgotPasswordEmailContent' => 'required|string',
        ]);

        $settings = [
            'authmodule.emails.forgot_password.title' => $this->forgotPasswordEmailTitle,
            'authmodule.emails.forgot_password.subject' => $this->forgotPasswordEmailSubject,
            'authmodule.emails.forgot_password.content' => $this->forgotPasswordEmailContent,
        ];

        try {
            SettingsManager::updateSettings($settings);
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        Notification::make()
            ->title(__('authmodule::settings.notifications.settings_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.modules.settings.authmodule', ['tab' => __('authmodule::settings.tabs.emails')]));

    }

    public function updateAccountSettings()
    {
        if (Auth::user()->cannot('adminmodule.settings.update')) {
            return;
        }

        $this->validate([
            'defaultAvatarUrl' => 'required|string',
            'allowChangeAvatar' => 'required|boolean',
            'allowDeleteAccount' => 'required|boolean',
        ]);

        $settings = [
            'authmodule.profile.default_avatar_url' => $this->defaultAvatarUrl,
            'authmodule.enable.change_avatar' => $this->allowChangeAvatar,
            'authmodule.enable.delete_account' => $this->allowDeleteAccount,
        ];

        try {
            SettingsManager::updateSettings($settings);
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        Notification::make()
            ->title(__('authmodule::settings.notifications.settings_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.modules.settings.authmodule', ['tab' => __('authmodule::settings.tabs.account')]));

    }

    public function mount()
    {
        if (!$this->tab) {
            $this->tab = __('authmodule::settings.tabs.auth');
        }

        $this->enableCaptcha = setting('authmodule.enable.captcha');
        $this->enableRegister = setting('authmodule.enable.register');
        $this->enableForgotPassword = setting('authmodule.enable.forgot_password');

        $this->forgotPasswordEmailTitle = setting('authmodule.emails.forgot_password.title');
        $this->forgotPasswordEmailSubject = setting('authmodule.emails.forgot_password.subject');
        $this->forgotPasswordEmailContent = setting('authmodule.emails.forgot_password.content');

        $this->defaultAvatarUrl = setting('authmodule.profile.default_avatar_url');
        $this->allowChangeAvatar = setting('authmodule.enable.change_avatar');
        $this->allowDeleteAccount = setting('authmodule.enable.delete_account');

        $this->passwordMinLength = setting('authmodule.password.minimum_length');
        $this->passwordBlacklist = setting('authmodule.password.blacklist');
        $this->passwordRequireNumber = (bool)setting('authmodule.password.require.numbers');
        $this->passwordRequireSpecialCharacter = (bool)setting('authmodule.password.require.special_characters');
        $this->passwordRequireUppercase = (bool)setting('authmodule.password.require.uppercase');
        $this->passwordRequireLowercase = (bool)setting('authmodule.password.require.lowercase');
    }

    public function render()
    {
        return $this->renderView('authmodule::livewire.admin-settings', __('adminmodule::settings.tab_title'), 'adminmodule::components.layouts.app');
    }
}
