<?php

use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Facades\UserSettings;
use RealZone22\PenguBlade\ModalComponent;

new class extends ModalComponent {
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

    public function mount()
    {
        $this->regenerateCodes();
    }
};
