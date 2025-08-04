<?php

namespace Modules\Auth\Livewire;

use App\Livewire\CFComponent;
use Filament\Notifications\Notification;
use Livewire\Attributes\Url;

class Admin extends CFComponent
{
    #[Url]
    public $tab;

    public $defaultAvatarUrl;
    public $loginRateLimit;
    public $registerRateLimit;
    public $unsplashApiKey;
    public $unsplashUtm;
    public $unsplashFallbackCss;
    public $unsplashQuery;
    public $enableDeleteAccount;
    public $enableChangeAvatar;
    public $enableRegister;
    public $enableLogin;
    public $enableForgotPassword;
    public $enableLoginCaptcha;
    public $enableRegisterCaptcha;
    public $enableForgotPasswordCaptcha;

    public $forgotPasswordMailTitle;
    public $forgotPasswordMailSubject;
    public $forgotPasswordMailContent;
    public $newSessionMailEnabled;
    public $newSessionMailTitle;
    public $newSessionMailSubject;
    public $newSessionMailContent;

    public $oauthLoginEnabled;
    public $oauthWellKnownUrl;
    public $oauthLoginColor;
    public $oauthLoginText;
    public $oauthIdField;
    public $oauthUsernameField;
    public $oauthEmailField;
    public $oauthClientId;
    public $oauthClientSecret;
    public $oauthRedirectUri;

    public $passwordMinLength;
    public $passwordRequireUppercase;
    public $passwordRequireLowercase;
    public $passwordRequireNumbers;
    public $passwordRequireSpecialCharacters;
    public $passwordBlacklist;

    public function updateGeneralSettings()
    {
        $this->validate([
            'defaultAvatarUrl' => 'required',
            'loginRateLimit' => 'required|integer|min:0',
            'registerRateLimit' => 'required|integer|min:0',
            'enableDeleteAccount' => 'nullable|boolean',
            'enableChangeAvatar' => 'nullable|boolean',
            'enableRegister' => 'nullable|boolean',
            'enableLogin' => 'nullable|boolean',
            'enableForgotPassword' => 'nullable|boolean',
            'enableLoginCaptcha' => 'nullable|boolean',
            'enableRegisterCaptcha' => 'nullable|boolean',
            'enableForgotPasswordCaptcha' => 'nullable|boolean',
        ]);

        settings()->updateSettings([
            'auth.default_avatar_url' => $this->defaultAvatarUrl,
            'auth.login.rate_limit' => $this->loginRateLimit,
            'auth.register.rate_limit' => $this->registerRateLimit,
            'auth.unsplash.api_key' => $this->unsplashApiKey,
            'auth.unsplash.utm' => $this->unsplashUtm,
            'auth.unsplash.fallback_css' => $this->unsplashFallbackCss,
            'auth.unsplash.query' => $this->unsplashQuery,
            'auth.profile.enable.delete_account' => $this->enableDeleteAccount,
            'auth.profile.enable.change_avatar' => $this->enableChangeAvatar,
            'auth.register.enable' => $this->enableRegister,
            'auth.login.enable' => $this->enableLogin,
            'auth.forgot_password.enable' => $this->enableForgotPassword,
            'auth.login.enable.captcha' => $this->enableLoginCaptcha,
            'auth.register.enable.captcha' => $this->enableRegisterCaptcha,
            'auth.forgot_password.enable.captcha' => $this->enableForgotPasswordCaptcha,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->log('auth.settings.general.updated');

        Notification::make()
            ->title(__('auth::admin.notifications.settings_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.settings.auth', ['tab' => 'general']), true);
    }

    public function updateMailSettings()
    {
        $this->validate([
            'forgotPasswordMailTitle' => 'required',
            'forgotPasswordMailSubject' => 'required',
            'forgotPasswordMailContent' => 'required',
            'newSessionMailEnabled' => 'nullable|boolean',
            'newSessionMailTitle' => 'required',
            'newSessionMailSubject' => 'required',
            'newSessionMailContent' => 'required',
        ]);

        settings()->updateSettings([
            'auth.emails.forgot_password.title' => $this->forgotPasswordMailTitle,
            'auth.emails.forgot_password.subject' => $this->forgotPasswordMailSubject,
            'auth.emails.forgot_password.content' => $this->forgotPasswordMailContent,
            'auth.emails.new_session.enabled' => $this->newSessionMailEnabled,
            'auth.emails.new_session.title' => $this->newSessionMailTitle,
            'auth.emails.new_session.subject' => $this->newSessionMailSubject,
            'auth.emails.new_session.content' => $this->newSessionMailContent,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->log('auth.settings.mail.updated');

        Notification::make()
            ->title(__('auth::admin.notifications.settings_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.settings.auth', ['tab' => 'mail']), true);

    }

    public function updateOAuthSettings()
    {
        $this->validate([
            'oauthLoginEnabled' => 'nullable|boolean',
            'oauthWellKnownUrl' => 'required_if:oauthLoginEnabled,true|url',
            'oauthLoginColor' => 'required_if:oauthLoginEnabled,true|string',
            'oauthLoginText' => 'required_if:oauthLoginEnabled,true|string',
            'oauthIdField' => 'required_if:oauthLoginEnabled,true|string',
            'oauthUsernameField' => 'required_if:oauthLoginEnabled,true|string',
            'oauthEmailField' => 'required_if:oauthLoginEnabled,true|string',
            'oauthClientId' => 'required_if:oauthLoginEnabled,true|string',
            'oauthClientSecret' => 'required_if:oauthLoginEnabled,true|string',
            'oauthRedirectUri' => 'required_if:oauthLoginEnabled,true|url',
        ]);

        settings()->updateSettings([
            'auth.oauth.enable' => $this->oauthLoginEnabled,
            'auth.oauth.well_known_url' => $this->oauthWellKnownUrl,
            'auth.oauth.login_color' => $this->oauthLoginColor,
            'auth.oauth.login_text' => $this->oauthLoginText,
            'auth.oauth.id_field' => $this->oauthIdField,
            'auth.oauth.username_field' => $this->oauthUsernameField,
            'auth.oauth.email_field' => $this->oauthEmailField,
            'auth.oauth.client_id' => $this->oauthClientId,
            'auth.oauth.client_secret' => $this->oauthClientSecret,
            'auth.oauth.redirect' => $this->oauthRedirectUri,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->log('auth.settings.oauth.updated');

        Notification::make()
            ->title(__('auth::admin.notifications.settings_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.settings.auth', ['tab' => 'oauth']), true);
    }

    public function updatePasswordSettings()
    {
        $this->validate([
            'passwordMinLength' => 'required|integer|min:1',
            'passwordRequireUppercase' => 'nullable|boolean',
            'passwordRequireLowercase' => 'nullable|boolean',
            'passwordRequireNumbers' => 'nullable|boolean',
            'passwordRequireSpecialCharacters' => 'nullable|boolean',
            'passwordBlacklist' => 'nullable|string',
        ]);

        settings()->updateSettings([
            'auth.password.minimum_length' => $this->passwordMinLength,
            'auth.password.require.uppercase' => $this->passwordRequireUppercase,
            'auth.password.require.lowercase' => $this->passwordRequireLowercase,
            'auth.password.require.numbers' => $this->passwordRequireNumbers,
            'auth.password.require.special_characters' => $this->passwordRequireSpecialCharacters,
            'auth.password.blacklist' => $this->passwordBlacklist,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->log('auth.settings.password.updated');

        Notification::make()
            ->title(__('auth::admin.notifications.settings_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.settings.auth', ['tab' => 'password']), true);

    }

    public function mount()
    {
        if (!in_array($this->tab, ['general', 'mail', 'oauth', 'password'])) {
            $this->tab = 'general';
        }

        // General
        $this->defaultAvatarUrl = settings('auth.default_avatar_url');
        $this->loginRateLimit = settings('auth.login.rate_limit');
        $this->registerRateLimit = settings('auth.register.rate_limit');
        $this->unsplashApiKey = settings('auth.unsplash.api_key');
        $this->unsplashUtm = settings('auth.unsplash.utm');
        $this->unsplashFallbackCss = settings('auth.unsplash.fallback_css');
        $this->unsplashQuery = settings('auth.unsplash.query');

        $this->enableDeleteAccount = (bool)settings('auth.profile.enable.delete_account');
        $this->enableChangeAvatar = (bool)settings('auth.profile.enable.change_avatar');
        $this->enableRegister = (bool)settings('auth.register.enable');
        $this->enableLogin = (bool)settings('auth.login.enable');
        $this->enableForgotPassword = (bool)settings('auth.forgot_password.enable');
        $this->enableLoginCaptcha = (bool)settings('auth.login.enable.captcha');
        $this->enableRegisterCaptcha = (bool)settings('auth.register.enable.captcha');
        $this->enableForgotPasswordCaptcha = (bool)settings('auth.forgot_password.enable.captcha');

        // Mails
        $this->forgotPasswordMailTitle = settings('auth.emails.forgot_password.title');
        $this->forgotPasswordMailSubject = settings('auth.emails.forgot_password.subject');
        $this->forgotPasswordMailContent = settings('auth.emails.forgot_password.content');
        $this->newSessionMailEnabled = (bool)settings('auth.emails.new_session.enabled');
        $this->newSessionMailTitle = settings('auth.emails.new_session.title');
        $this->newSessionMailSubject = settings('auth.emails.new_session.subject');
        $this->newSessionMailContent = settings('auth.emails.new_session.content');

        // OAuth
        $this->oauthLoginEnabled = settings('auth.oauth.enable');
        $this->oauthWellKnownUrl = settings('auth.oauth.well_known_url');
        $this->oauthLoginColor = settings('auth.oauth.login_color');
        $this->oauthLoginText = settings('auth.oauth.login_text');
        $this->oauthIdField = settings('auth.oauth.id_field');
        $this->oauthUsernameField = settings('auth.oauth.username_field');
        $this->oauthEmailField = settings('auth.oauth.email_field');
        $this->oauthClientId = settings('auth.oauth.client_id');
        $this->oauthClientSecret = settings('auth.oauth.client_secret');
        $this->oauthRedirectUri = settings('auth.oauth.redirect');

        // Password
        $this->passwordMinLength = settings('auth.password.minimum_length');
        $this->passwordRequireUppercase = (bool)settings('auth.password.require.uppercase');
        $this->passwordRequireLowercase = (bool)settings('auth.password.require.lowercase');
        $this->passwordRequireNumbers = (bool)settings('auth.password.require.numbers');
        $this->passwordRequireSpecialCharacters = (bool)settings('auth.password.require.special_characters');
        $this->passwordBlacklist = settings('auth.password.blacklist');
    }

    public function render()
    {
        return $this->renderView('auth::livewire.admin', __('auth::admin.tab_title'), 'admin::components.layouts.app');
    }
}
