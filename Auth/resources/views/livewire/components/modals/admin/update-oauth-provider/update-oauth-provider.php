<?php

use Masmerise\Toaster\Toaster;
use Modules\Auth\Models\OAuthProvider;
use RealZone22\PenguBlade\ModalComponent;

new class extends ModalComponent
{
    public $providerId;

    public $provider;

    public $name;

    public $color;

    public $icon;

    public $discoveryUrl;

    public $clientId;

    public $clientSecret;

    public $accountLinking = true;

    public $fields = [];

    public function updateOAuthProvider()
    {
        $this->validate([
            'name' => 'required|unique:oauth_providers,name,'.$this->providerId,
            'discoveryUrl' => 'required|url',
            'clientId' => 'required',
            'clientSecret' => 'required',
            'color' => 'nullable|in:primary,secondary,success,danger,warning,info',
            'fields' => 'required|array',
        ]);

        $this->provider->update([
            'name' => $this->name,
            'color' => $this->color,
            'icon' => $this->normalizeIcon(),
            'discovery_url' => $this->discoveryUrl,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'account_linking' => $this->accountLinking,
            'fields' => $this->fields,
        ]);

        Toaster::success(__('auth::admin.oauth_providers.update.notifications.updated'));

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

        $this->provider = OAuthProvider::find($this->providerId);
        if (! $this->provider) {
            abort(404);
        }

        $this->name = $this->provider->name;
        $this->color = $this->provider->color;
        $this->icon = str_replace('icon-', '', $this->provider->icon);
        $this->discoveryUrl = $this->provider->discovery_url;
        $this->clientId = $this->provider->client_id;
        $this->clientSecret = $this->provider->client_secret;
        $this->accountLinking = $this->provider->account_linking;
        $this->fields = $this->provider->fields;
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }
};
