<?php

use Livewire\Attributes\Locked;
use Livewire\Component;
use Modules\Auth\Facades\Unsplash;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Facades\UserSettings;
use PragmaRX\Google2FA\Google2FA;

new class extends Component
{
    public $unsplash;

    #[Locked]
    public string $mfaSecret;

    public string $code;

    public array $recoveryCodes;

    public function regenerateCodes()
    {
        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = str()->random();
        }

        UserSettings::set(auth()->id(), 'auth.mfa.recovery_codes', $recoveryCodes, true, [SettingsProperty::INTERNAL => true, SettingsProperty::ENCRYPTED => true]);

        $this->recoveryCodes = $recoveryCodes;
    }

    public function downloadCodes()
    {
        return response()->streamDownload(function () {
            echo implode(PHP_EOL, $this->recoveryCodes);
        }, 'recovery-codes-'.auth()->user()->getDisplayName().'.txt');
    }

    public function enableMfa()
    {
        $this->validate([
            'code' => ['required', 'digits:6', function (string $attribute, mixed $value, Closure $fail) {
                $google2FA = new Google2FA;
                if (! $google2FA->verifyKey($this->mfaSecret, $value)) {
                    $fail(__('auth::force.mfa.invalid_code'));
                }
            }],
        ]);

        UserSettings::set(auth()->id(), 'auth.mfa.enabled', true);
        UserSettings::delete(auth()->id(), 'auth.force_mfa');

        $this->regenerateCodes();

        Toaster::success(__('auth::force.mfa.notifications.enabled'));
    }

    public function finish()
    {
        UserSettings::delete(auth()->id(), 'auth.force_mfa');

        $this->redirectIntended('/', true);
    }

    public function mount()
    {
        $this->unsplash = Unsplash::returnBackground();
        $google2FA = new Google2FA;
        $this->mfaSecret = $google2FA->generateSecretKey();

        UserSettings::set(auth()->id(), 'auth.mfa.secret', $this->mfaSecret, true, [SettingsProperty::INTERNAL => true, SettingsProperty::ENCRYPTED => true]);
    }

    public function render()
    {
        return $this->view()
            ->layout('auth::layouts.guest')
            ->title(__('auth::force.mfa.tab_title'));
    }
};
