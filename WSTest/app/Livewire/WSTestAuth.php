<?php

namespace Modules\WSTest\Livewire;

use App\Livewire\CFComponent;
use Livewire\Attributes\On;

class WSTestAuth extends CFComponent
{
    public $response;

    #[On('echo-private:wstest-auth,.Modules\\WSTest\\Events\\AuthTestEvent')]
    public function checkWS()
    {
        $this->response .= now().': Auth WebSocket received at '.now().'<br>';
    }

    public function mount()
    {
        $this->response = now().': Waiting for Auth WebSocket messages... Open Browser Console for more details<br>';
    }

    public function render()
    {
        return $this->renderView('wstest::livewire.w-s-test-auth', 'WSTest Auth', 'wstest::components.layouts.app');
    }
}
