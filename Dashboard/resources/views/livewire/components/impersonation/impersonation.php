<?php

use Livewire\Component;

new class extends Component
{
    public function leaveImpersonation()
    {
        $user = auth()->user();

        auth()->user()->leaveImpersonation();

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log('user.impersonate.end');

        $this->redirect(url()->previous(), true);
    }
};
