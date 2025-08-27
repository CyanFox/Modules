<?php

namespace Modules\Redirects\Actions;

use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Redirects\Models\Redirect;

class DeleteRedirectAction
{
    use AsAction;

    public function handle($redirect)
    {
        return $redirect->delete();
    }

    public function asJob($redirect)
    {
        $this->handle($redirect);
    }

    public function asListener($redirect)
    {
        $this->handle($redirect);
    }

}
