<?php

use Livewire\Attributes\On;
use Modules\Core\Traits\WithPasswordConfirmation;
use RealZone22\PenguBlade\ModalComponent;

new class extends ModalComponent {
    use WithPasswordConfirmation;

    #[On('modalClosed')]
    public function closeModal(): void
    {
        $this->redirect(url()->previous(), true);
    }

    public static function dispatchCloseEvent(): bool
    {
        return true;
    }
};
