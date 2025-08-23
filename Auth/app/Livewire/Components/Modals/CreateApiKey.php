<?php

namespace Modules\Auth\Livewire\Components\Modals;

use App\Livewire\CFModalComponent;
use App\Traits\WithCustomLivewireException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Masmerise\Toaster\Toaster;
use Modules\Auth\Traits\WithPasswordConfirmation;

class CreateApiKey extends CFModalComponent
{
    use WithCustomLivewireException, WithPasswordConfirmation;

    public $name;

    public $permissions = [];

    public $key;

    public function createApiKey()
    {
        if (! $this->hasPasswordConfirmedSession()) {
            return;
        }

        $this->validate([
            'name' => 'required',
            'permissions' => 'required|array',
        ]);

        $key = Str::password(symbols: false);

        $apiKey = auth()->user()->apiKeys()->create([
            'name' => $this->name,
            'key' => Hash::make($key),
        ]);

        foreach ($this->permissions as $permission) {
            $apiKey->permissions()->create([
                'permission_id' => $permission,
            ]);
        }

        $this->key = $apiKey->id.'-'.$key;

        Toaster::success(__('auth::profile.api_keys.modals.create_api_key.notifications.api_key_created'));
    }

    public function closeModal(): void
    {
        $this->redirect(route('account.profile', ['tab' => 'apiKeys']), true);
    }

    public function mount()
    {
        if (! $this->checkPasswordConfirmation()->passwordFunction('render')->checkPassword()) {
            return;
        }
    }

    public function render()
    {
        return view('auth::livewire.components.modals.create-api-key');
    }
}
