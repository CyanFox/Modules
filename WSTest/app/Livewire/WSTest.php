<?php

namespace Modules\WSTest\Livewire;

use App\Livewire\CFComponent;
use Livewire\Attributes\On;

class WSTest extends CFComponent
{
    public $response;

    #[On('echo:wstest,.Modules\\WSTest\\Events\\TestEvent')]
    public function checkWS()
    {
        $this->response .= now().': WebSocket received at '.now().'<br>';
    }

    public function mount()
    {
        $this->response = now().': Waiting for WebSocket messages... Open Browser Console for more details<br>';
    }

    public function render()
    {
        return $this->renderView('wstest::livewire.w-s-test', 'WSTest', 'wstest::components.layouts.app');
    }
}
