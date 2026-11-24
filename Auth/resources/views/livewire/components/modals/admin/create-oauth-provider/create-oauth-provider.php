<?php

use Masmerise\Toaster\Toaster;
use Modules\Auth\Models\OAuthProvider;
use RealZone22\PenguBlade\ModalComponent;

new class extends ModalComponent
{
    public $name;

    public $color;

    public $icon;

    public $discoveryUrl;

    public $clientId;

    public $clientSecret;

    public $accountLinking = true;

    public $fields = [];

    public function createOAuthProvider()
    {
        $this->validate([
            'name' => 'required|unique:oauth_providers,name',
            'discoveryUrl' => 'required|url',
            'clientId' => 'required',
            'clientSecret' => 'required',
            'color' => 'nullable|in:primary,secondary,success,danger,warning,info',
            'fields' => 'required|array',
        ]);

        OAuthProvider::create([
            'name' => $this->name,
            'color' => $this->color,
            'icon' => $this->normalizeIcon(),
            'discovery_url' => $this->discoveryUrl,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'account_linking' => $this->accountLinking,
            'fields' => $this->fields,
        ]);

        Toaster::success(__('auth::admin.oauth_providers.create.notifications.created'));

        $this->redirect(url()->previous(), true);
    }

    public function addField()
    {
        $this->fields[] = [
            'oauth' => '',
            'db' => '',
        ];
    }

    public function deleteField($index)
    {
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
    }

    private function normalizeIcon(): ?string
    {
        if (! $this->icon) {
            return null;
        }

        if (str_starts_with($this->icon, 'bi') || str_starts_with($this->icon, 'icon-')) {
            return $this->icon;
        }

        return 'icon-'.$this->icon;
    }

    public function mount()
    {
        if (auth()->user()->cannot('admin.settings')) {
            abort(403);
        }

        $this->fields = [
            [
                'oauth' => 'preferred_username',
                'db' => 'username',
            ],
            [
                'oauth' => 'email',
                'db' => 'email',
            ],
        ];
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }
};
