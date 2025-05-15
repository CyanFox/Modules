<?php

namespace Modules\Auth\Livewire\Components\Passkeys;

use Filament\Notifications\Notification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Modules\Auth\Traits\WithPasswordConfirmation;
use Spatie\LaravelPasskeys\Actions\GeneratePasskeyRegisterOptionsAction;
use Spatie\LaravelPasskeys\Actions\StorePasskeyAction;
use Spatie\LaravelPasskeys\Models\Concerns\HasPasskeys;
use Spatie\LaravelPasskeys\Support\Config;
use Throwable;

class PasskeysComponent extends Component
{
    use WithPasswordConfirmation;

    #[Validate('required|string|max:255')]
    public string $name = '';

    public function render(): View
    {
        return view('auth::livewire.components.passkeys.passkeys', data: [
            'passkeys' => $this->currentUser()->passkeys,
        ]);
    }

    public function validatePasskeyProperties(): void
    {
        $this->validate();

        $this->dispatch('passkeyPropertiesValidated', [
            'passkeyOptions' => json_decode($this->generatePasskeyOptions()),
        ]);
    }

    #[On('auth.profile.passkeys.store')]
    public function storePasskey(string $passkey): void
    {
        if (!$this->checkPasswordConfirmation()->passwordDispatch('auth.profile.passkeys.store', $passkey)->checkPassword()) {
            return;
        }

        $storePasskeyAction = Config::getAction('store_passkey', StorePasskeyAction::class);

        try {
            $storePasskeyAction->execute(
                $this->currentUser(),
                $passkey, $this->previouslyGeneratedPasskeyOptions(),
                request()->getHost(),
                ['name' => $this->name]
            );
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'name' => __('passkeys::passkeys.error_something_went_wrong_generating_the_passkey'),
            ])->errorBag('passkeyForm');
        }

        $this->clearForm();

        Notification::make()
            ->title(__('auth::profile.passkeys.notifications.passkey_created'))
            ->success()
            ->send();

        $this->redirect(route('account.profile', ['passTab' => 'passkeys']));
    }

    #[On('auth.profile.passkeys.delete')]
    public function deletePasskey(int $passkeyId): void
    {
        if (!$this->checkPasswordConfirmation()->passwordDispatch('auth.profile.passkeys.delete', $passkeyId)->checkPassword()) {
            return;
        }

        $this->currentUser()->passkeys()->where('id', $passkeyId)->delete();

        Notification::make()
            ->title(__('auth::profile.passkeys.notifications.passkey_deleted'))
            ->success()
            ->send();

        $this->redirect(route('account.profile', ['passTab' => 'passkeys']));
    }

    public function currentUser(): Authenticatable&HasPasskeys
    {
        /** @var Authenticatable&HasPasskeys $user */
        $user = auth()->user();

        return $user;
    }

    protected function clearForm(): void
    {
        $this->name = '';
    }

    protected function generatePasskeyOptions(): string
    {
        $generatePassKeyOptionsAction = Config::getAction('generate_passkey_register_options', GeneratePasskeyRegisterOptionsAction::class);

        $options = $generatePassKeyOptionsAction->execute($this->currentUser());

        session()->put('passkey-registration-options', $options);

        return $options;
    }

    protected function previouslyGeneratedPasskeyOptions(): ?string
    {
        return session()->pull('passkey-registration-options');
    }
}
