<?php

namespace Modules\Auth\Livewire\Components\Modals;

use App\Livewire\CFModalComponent;
use App\Traits\WithCustomLivewireException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Auth\Traits\WithPasswordConfirmation;

class ConnectDevice extends CFModalComponent
{
    use WithCustomLivewireException, WithPasswordConfirmation;

    public $key;

    public function closeModal(): void
    {
        $this->redirect(route('account.profile', ['tab' => 'connectedDevices']), true);
    }

    public function mount()
    {
        if (!$this->checkPasswordConfirmation()->passwordFunction('render')->checkPassword()) {
            return;
        }

        $key = Str::password(symbols: false);

        $apiKey = auth()->user()->apiKeys()->create([
            'name' => 'Connected Device - ' . formatDateTime(now()),
            'key' => Hash::make($key),
            'connected_device' => true,
        ]);

        $this->key = $apiKey->id . '-' . $key;
    }

    public function render()
    {
        return view('auth::livewire.components.modals.connect-device');
    }
}
