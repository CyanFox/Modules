<?php

use Livewire\Component;
use Modules\Core\Traits\WithConfirmation;
use Modules\Core\Traits\WithPasswordConfirmation;

new class extends Component {
    use WithPasswordConfirmation, WithConfirmation;

    public function showCreateAPIKey()
    {
        $this->checkPasswordConfirmation()
            ->passwordModal('account::components.modals.create-api-key')
            ->checkPassword();
    }

    public function deleteApiKey(string $tokenId, bool $confirmed = false)
    {
        if ($confirmed) {
            auth()->user()->tokens->where('id', $tokenId)->first()?->delete();

            Toaster::success(__('account::api.delete_api_key.notifications.deleted'));

            $this->redirect(url()->previous(), true);
            return;
        }

        $this->dialog()
            ->question(__('account::api.delete_api_key.title'),
                __('account::api.delete_api_key.description'))
            ->confirm(__('account::api.delete_api_key.buttons.delete'), 'danger')
            ->icon('icon-triangle-alert')
            ->method('deleteApiKey', $tokenId, true)
            ->send();
    }

    public function render()
    {
        return $this->view()
            ->layout('dashboard::layouts.app', ['breadcrumbs' => [['label' => __('account::account.account'), 'url' => route('account.profile')], ['label' => __('account::account.api'), 'last' => true]]])
            ->title(__('account::account.api'));
    }
};
