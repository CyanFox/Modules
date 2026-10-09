<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Modules\Core\Traits\WithConfirmation;
use Modules\Core\Traits\WithPasswordConfirmation;
use Spatie\LaravelPasskeys\Actions\GeneratePasskeyRegisterOptionsAction;
use Spatie\LaravelPasskeys\Actions\StorePasskeyAction;
use Spatie\LaravelPasskeys\Models\Concerns\HasPasskeys;
use Spatie\LaravelPasskeys\Support\Config;

new class extends Component {
    use WithPasswordConfirmation, WithConfirmation;

    #[Validate('required|string|max:255')]
    public string $name = '';

    public function render(): View
    {
        return view('account::livewire.components.passkeys.passkeys', data: [
            'passkeys' => $this->currentUser()->passkeys, // @phpstan-ignore-line
        ]);
    }

    public function validatePasskeyProperties(): void
    {
        $this->validate();

        $this->dispatch('passkeyPropertiesValidated', [
            'passkeyOptions' => json_decode($this->generatePasskeyOptions()),
        ]);
    }

    #[On('account.passkeys.store')]
    public function storePasskey(string $passkey): void
    {
        if (!$this->hasPasswordConfirmedSession()) {
            $this->checkPasswordConfirmation()
                ->passwordDispatch('account.passkeys.store', $passkey)
                ->checkPassword();

            return;
        }

        $this->persistPasskey($passkey);
    }

    protected function persistPasskey(string $passkey): void
    {
        $passkeyOptions = session()->pull('passkey-registration-options');

        if (!is_string($passkeyOptions)) {
            throw ValidationException::withMessages([
                'name' => __('passkeys::passkeys.error_something_went_wrong_generating_the_passkey'),
            ])->errorBag('passkeyForm');
        }

        $storePasskeyAction = Config::getAction('store_passkey', StorePasskeyAction::class);

        try {
            $storePasskeyAction->execute(
                $this->currentUser(),
                $passkey,
                $passkeyOptions,
                request()->getHost(),
                ['name' => $this->name]
            );
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'name' => __('passkeys::passkeys.error_something_went_wrong_generating_the_passkey'),
            ])->errorBag('passkeyForm');
        }

        $this->clearForm();

        Toaster::success(__('account::account.notifications.passkey_created'));

        $this->redirect(url()->previous(), true);
    }

    #[On('account.passkeys.delete')]
    public function deletePasskey(int $passkeyId, $confirmed = false): void
    {
        if ($confirmed) {
            if (!$this->hasPasswordConfirmedSession()) {
                return;
            }

            $passkey = $this->currentUser()->passkeys()->where('id', $passkeyId);

            if ($passkey->delete()) {
                Toaster::success(__('account::account.delete_passkey.notifications.deleted'));
                $this->redirect(url()->previous(), true);
            }

            return;
        }

        $this->dialog()
            ->question(__('account::account.delete_passkey.title'),
                __('account::account.delete_passkey.description'))
            ->confirm(__('account::account.delete_passkey.buttons.delete'), 'danger')
            ->icon('icon-triangle-alert')
            ->needsPasswordConfirmation()
            ->dispatchEvent('account.passkeys.delete', $passkeyId, true)
            ->send();
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

};
