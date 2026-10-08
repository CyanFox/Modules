<?php

use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Facades\UserSettings;
use PragmaRX\Google2FA\Google2FA;
use RealZone22\PenguBlade\ModalComponent;

new class extends ModalComponent {

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

        UserSettings::set(auth()->id(), 'auth.mfa.recovery_codes', $recoveryCodes, true, [SettingsProperty::INTERNAL->value => true, SettingsProperty::ENCRYPTED->value => true]);

        $this->recoveryCodes = $recoveryCodes;
    }

    public function downloadCodes()
    {
        return response()->streamDownload(function () {
            echo implode(PHP_EOL, $this->recoveryCodes);
        }, 'recovery-codes-' . auth()->user()->getDisplayName() . '.txt');
    }

    public function enableMfa()
    {
        $this->validate([
            'code' => ['required', 'digits:6', function (string $attribute, mixed $value, Closure $fail) {
                $google2FA = new Google2FA();
                if (!$google2FA->verifyKey($this->mfaSecret, $value)) {
                    $fail(__('account::modals.enable_mfa.invalid_code'));
                }
            }]
        ]);

        UserSettings::set(auth()->id(), 'auth.mfa.enabled', true);

        $this->regenerateCodes();

        Toaster::success(__('account::modals.enable_mfa.notifications.enabled'));
    }

    #[On('modalClosed')]
    public function closeModal(): void
    {
        $this->redirect(url()->previous(), true);
    }

    public static function dispatchCloseEvent(): bool
    {
        return true;
    }

    public function mount()
    {
        $google2FA = new Google2FA();
        $this->mfaSecret = $google2FA->generateSecretKey();

        UserSettings::set(auth()->id(), 'auth.mfa.secret', $this->mfaSecret, true, [SettingsProperty::INTERNAL->value => true, SettingsProperty::ENCRYPTED->value => true]);
    }
};
