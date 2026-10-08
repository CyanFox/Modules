<?php

use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Modules\Core\Traits\WithPasswordConfirmation;

new class extends Component {
    use WithPasswordConfirmation;

    public function logoutSession($sessionId)
    {
        $this->checkPasswordConfirmation()
            ->passwordCallback(function () use ($sessionId) {
                auth()->user()->sessions()->where('id', $sessionId)->first()?->delete();

                Toaster::success(__('account::sessions.notifications.logged_out'));
            })
            ->checkPassword();
    }

    public function logoutAppSession($tokenId)
    {
        $this->checkPasswordConfirmation()
            ->passwordCallback(function () use ($tokenId) {
                auth()->user()->tokens()->where('id', $tokenId)->first()?->delete();

                Toaster::success(__('account::sessions.notifications.logged_out'));
            })
            ->checkPassword();
    }

    public function showLoginQrCode(): void
    {
        $this->checkPasswordConfirmation()
            ->passwordModal('account::components.modals.login-qr-code')
            ->checkPassword();
    }

    public function render()
    {
        return $this->view()
            ->layout('dashboard::layouts.app', ['breadcrumbs' => [['label' => __('account::account.account'), 'url' => route('account.profile')], ['label' => __('account::account.sessions'), 'last' => true]]])
            ->title(__('account::account.sessions'));
    }
};
