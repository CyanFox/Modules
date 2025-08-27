<?php

namespace Modules\Redirects\Actions;

use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Redirects\Models\Redirect;

class CreateRedirectAction
{
    use AsAction;

    public function handle($data)
    {
        return Redirect::create($data);
    }

    public function asJob($data)
    {
        $this->handle($data);
    }

    public function asListener($data)
    {
        $this->handle($data);
    }

}
