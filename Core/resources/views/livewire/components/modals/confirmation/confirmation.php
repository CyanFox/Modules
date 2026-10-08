<?php

use Livewire\Attributes\Locked;
use Modules\Core\Traits\WithPasswordConfirmation;
use RealZone22\PenguBlade\ModalComponent;

new class extends ModalComponent {
    use WithPasswordConfirmation;

    #[Locked]
    public string $title;

    #[Locked]
    public string $description;

    #[Locked]
    public string $cancel;

    #[Locked]
    public string $cancelColor;

    #[Locked]
    public string $confirm;

    #[Locked]
    public string $confirmColor;

    #[Locked]
    public string $icon;

    #[Locked]
    public string $iconColor;

    #[Locked]
    public string $needsPasswordConfirmation;

    #[Locked]
    public mixed $event = null;

    #[Locked]
    public mixed $cancelEvent = null;

    public function confirmAction()
    {
        if ($this->needsPasswordConfirmation && !$this->hasPasswordConfirmedSession()) {
            return;
        }

        if ($this->event !== null) {
            $this->dispatch('core.confirmation.confirmed', $this->event);
        }

        $this->dispatch('closeModal');
    }

    public function cancelAction()
    {
        if ($this->cancelEvent !== null) {
            $this->dispatch('core.confirmation.confirmed', $this->cancelEvent);
        }

        $this->dispatch('closeModal');
    }

    public function mount()
    {
        if ($this->needsPasswordConfirmation && !$this->checkPasswordConfirmation()->passwordFunction('render')->checkPassword()) {
            return;
        }
    }

    protected function requiresPasswordConfirmationToken(): bool
    {
        return false;
    }
};
