<?php

use Illuminate\View\View;
use Livewire\Component;
use Modules\Auth\Models\OAuthProvider;
use Modules\Core\Traits\WithConfirmation;

new class extends Component
{
    use WithConfirmation;

    public function deleteProvider($providerId, $confirmed = false)
    {
        if (auth()->user()->cannot('admin.settings')) {
            return;
        }

        if ($confirmed) {
            $provider = OAuthProvider::find($providerId);

            if (! $provider?->delete()) {
                Toaster::error(__('messages.notifications.something_went_wrong'));

                return;
            }

            Toaster::success(__('auth::admin.oauth_providers.delete.notifications.deleted'));

            $this->redirect(url()->previous(), true);

            return;
        }

        $this->dialog()
            ->question(__('auth::admin.oauth_providers.delete.title'),
                __('auth::admin.oauth_providers.delete.description'))
            ->icon('icon-triangle-alert')
            ->confirm(__('auth::admin.oauth_providers.delete.buttons.delete'), 'danger')
            ->method('deleteProvider', $providerId, true)
            ->send();

    }

    public function render(): View
    {
        return $this->view()
            ->layout('admin::layouts.app', ['breadcrumbs' => [['label' => __('admin::admin.admin'), 'url' => route('admin.dashboard')],
                ['label' => __('admin::settings.tab_title'), 'url' => route('admin.settings')], ['label' => __('admin::settings.module.tab_title'), 'url' => route('admin.settings.module')],
                ['label' => modules()->getModule('Auth')->getName(), 'url' => route('admin.settings.auth')], ['label' => __('auth::admin.oauth_providers.tab_title'), 'last' => true]]])
            ->title(__('auth::admin.oauth_providers.tab_title'));
    }
};
