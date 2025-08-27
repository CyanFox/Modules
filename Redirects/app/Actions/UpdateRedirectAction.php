<?php

namespace Modules\Redirects\Actions;

use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Redirects\Models\Redirect;

class UpdateRedirectAction
{
    use AsAction;

    public function handle($redirect, $data)
    {
        return $redirect->update($data);
    }

    public function asJob($redirect, $data)
    {
        $this->handle($redirect, $data);
    }

    public function asListener($redirect, $data)
    {
        $this->handle($redirect, $data);
    }

}
