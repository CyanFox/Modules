<?php

use RealZone22\PenguBlade\ModalComponent;

new class extends ModalComponent {

    public string $name;
    public array $permissions = [];

    public string $token;

    public function createApiKey()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
        ]);

        $this->token = auth()->user()->createToken(json_encode(['name' => $this->name, 'type' => 'api']), $this->permissions)->plainTextToken;

        Toaster::success(__('account::modals.create_api_key.notifications.created'));
    }

    public function closeModal(): void
    {
        $this->redirect(url()->previous(), true);
    }

    public static function dispatchCloseEvent(): bool
    {
        return true;
    }
};
