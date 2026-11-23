<?php

use Modules\Core\Traits\WithPasswordConfirmation;
use RealZone22\PenguBlade\ModalComponent;

new class extends ModalComponent
{
    use WithPasswordConfirmation;

    public function mount()
    {
        if (!$this->hasPasswordConfirmedSession()) {
            abort(403);
        }
    }
};
