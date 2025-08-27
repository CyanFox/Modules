<?php

namespace Modules\Redirects\Livewire;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Masmerise\Toaster\Toaster;
use Modules\Redirects\Actions\CreateRedirectAction;

class CreateRedirect extends CFComponent
{
    use WithCustomLivewireException;

    public $from;
    public $to;
    public $statusCode = 301;
    public $active = true;
    public $includeQueryString = false;
    public $internal = false;
    public $accessGroups = [];
    public $accessPermissions = [];
    public $accessUsers = [];
    public $editAccessGroups = [];
    public $editAccessPermissions = [];
    public $editAccessUsers = [];

    public function createRedirect()
    {
        $this->validate([
            'from' => 'required|url|unique:redirects,from',
            'to' => 'required|url',
            'statusCode' => 'required|in:301,302,303,307,308',
            'active' => 'nullable|boolean',
            'includeQueryString' => 'nullable|boolean',
            'internal' => 'nullable|boolean',
            'accessGroups' => 'array|exists:roles,id',
            'accessPermissions' => 'array|exists:permissions,id',
            'accessUsers' => 'array|exists:users,id',
            'editAccessGroups' => 'array|exists:roles,id',
            'editAccessPermissions' => 'array|exists:permissions,id',
            'editAccessUsers' => 'array|exists:users,id',
        ]);

        $redirect = CreateRedirectAction::run([
            'from' => $this->from,
            'to' => $this->to,
            'status_code' => $this->statusCode,
            'active' => $this->active,
            'include_query_string' => $this->includeQueryString,
            'internal' => $this->internal,
            'created_by' => auth()->id(),
        ]);

        if ($this->internal) {
            $redirect->access()->createMany(array_map(fn($id) => [
                'role_id' => $id,
                'can_update' => false
            ], $this->accessGroups));

            $redirect->access()->createMany(array_map(fn($id) => [
                'permission_id' => $id,
                'can_update' => false
            ], $this->accessPermissions));

            $redirect->access()->createMany(array_map(fn($id) => [
                'user_id' => $id,
                'can_update' => false
            ], $this->accessUsers));
        }

        $redirect->access()->createMany(array_map(fn($id) => [
            'role_id' => $id,
            'can_update' => true
        ], $this->editAccessGroups));

        $redirect->access()->createMany(array_map(fn($id) => [
            'permission_id' => $id,
            'can_update' => true
        ], $this->editAccessPermissions));

        $redirect->access()->createMany(array_map(fn($id) => [
            'user_id' => $id,
            'can_update' => true
        ], $this->editAccessUsers));

        Toaster::success(__('redirects::redirects.create_redirect.notifications.redirect_created'));

        $this->redirect(route('redirects.update', ['redirectId' => $redirect->id]), true);
    }

    public function render()
    {
        return $this->renderView('redirects::livewire.create-redirect', __('redirects::redirects.create_redirect.tab_title'), 'dashboard::components.layouts.app');
    }
}
