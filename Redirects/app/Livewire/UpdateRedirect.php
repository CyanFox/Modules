<?php

namespace Modules\Redirects\Livewire;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Masmerise\Toaster\Toaster;
use Modules\Redirects\Actions\CreateRedirectAction;
use Modules\Redirects\Actions\UpdateRedirectAction;
use Modules\Redirects\Models\Redirect;

class UpdateRedirect extends CFComponent
{
    use WithCustomLivewireException;

    public $redirectId;
    public $redirect;

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

    public function updateRedirect()
    {
        $this->validate([
            'from' => 'required|url|unique:redirects,from,' . $this->redirect->id,
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

        UpdateRedirectAction::run($this->redirect, [
            'from' => $this->from,
            'to' => $this->to,
            'status_code' => $this->statusCode,
            'active' => $this->active,
            'include_query_string' => $this->includeQueryString,
            'internal' => $this->internal,
            'created_by' => auth()->id(),
        ]);

        $this->redirect->access()->delete();

        if ($this->internal) {
            $this->redirect->access()->createMany(array_map(fn($id) => [
                'role_id' => $id,
                'can_update' => false
            ], $this->accessGroups));

            $this->redirect->access()->createMany(array_map(fn($id) => [
                'permission_id' => $id,
                'can_update' => false
            ], $this->accessPermissions));

            $this->redirect->access()->createMany(array_map(fn($id) => [
                'user_id' => $id,
                'can_update' => false
            ], $this->accessUsers));
        }

        $this->redirect->access()->createMany(array_map(fn($id) => [
            'role_id' => $id,
            'can_update' => true
        ], $this->editAccessGroups));

        $this->redirect->access()->createMany(array_map(fn($id) => [
            'permission_id' => $id,
            'can_update' => true
        ], $this->editAccessPermissions));

        $this->redirect->access()->createMany(array_map(fn($id) => [
            'user_id' => $id,
            'can_update' => true
        ], $this->editAccessUsers));

        Toaster::success(__('redirects::redirects.update_redirect.notifications.redirect_updated'));

        $this->redirect(route('redirects.update', ['redirectId' => $this->redirectId]), true);
    }

    public function mount()
    {
        $this->redirect = Redirect::find($this->redirectId);
        if (!$this->redirect) {
            abort(404);
        }

        $this->from = $this->redirect->from;
        $this->to = $this->redirect->to;
        $this->statusCode = $this->redirect->status_code;
        $this->active = $this->redirect->active;
        $this->includeQueryString = $this->redirect->include_query_string;
        $this->internal = $this->redirect->internal;
        $this->accessGroups = $this->redirect->access()->whereNot('can_update', true)->whereNotNull('role_id')->pluck('role_id')->toArray();
        $this->accessPermissions = $this->redirect->access()->whereNot('can_update', true)->whereNotNull('permission_id')->pluck('permission_id')->toArray();
        $this->accessUsers = $this->redirect->access()->whereNot('can_update', true)->whereNotNull('user_id')->pluck('user_id')->toArray();
        $this->editAccessGroups = $this->redirect->access()->where('can_update', true)->whereNotNull('role_id')->pluck('role_id')->toArray();
        $this->editAccessPermissions = $this->redirect->access()->where('can_update', true)->whereNotNull('permission_id')->pluck('permission_id')->toArray();
        $this->editAccessUsers = $this->redirect->access()->where('can_update', true)->whereNotNull('user_id')->pluck('user_id')->toArray();
    }

    public function render()
    {
        return $this->renderView('redirects::livewire.update-redirect', __('redirects::redirects.update_redirect.tab_title'), 'dashboard::components.layouts.app');
    }
}
