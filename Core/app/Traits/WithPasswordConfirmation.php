<?php

namespace Modules\Core\Traits;

use Closure;
use Laravel\SerializableClosure\SerializableClosure;
use Livewire\Attributes\On;
use RealZone22\PenguBlade\ModalComponent;

trait WithPasswordConfirmation
{
    private $passwordConfirmationData = [];

    public function checkPasswordConfirmation()
    {
        return $this;
    }

    public function passwordTitle($title)
    {
        $this->passwordConfirmationData['title'] = $title;

        return $this;
    }

    public function passwordDescription($description)
    {
        $this->passwordConfirmationData['description'] = $description;

        return $this;
    }

    public function passwordExpiration($expiration = 300)
    {
        $this->passwordConfirmationData['expiration'] = $expiration;

        return $this;
    }

    public function checkPassword()
    {
        if ($this->hasPasswordConfirmedSession($this->passwordConfirmationData['expiration'] ?? 300)) {
            $this->executePasswordConfirmation($this->createPasswordConfirmationToken());

            return true;
        }

        $this->dispatch('openModal', 'core::components.modals.password-confirmation', [
            'title' => $this->passwordConfirmationData['title'] ?? null,
            'description' => $this->passwordConfirmationData['description'] ?? null,
            'event' => $this->passwordConfirmationData['event'] ?? null,
            'dispatch' => $this->passwordConfirmationData['dispatch'] ?? null,
            'cancelEvent' => $this->passwordConfirmationData['cancelEvent'] ?? null,
            'modal' => $this->passwordConfirmationData['modal'] ?? null,
        ]);

        return false;
    }

    public function hasPasswordConfirmedSession($expiration = 300): bool
    {
        $confirmedAt = session('core.password_confirmed_at');

        return $confirmedAt && $confirmedAt >= (time() - $expiration);
    }

    private function executePasswordConfirmation(string $passwordConfirmationToken): void
    {
        if (isset($this->passwordConfirmationData['event'])) {
            $this->handlePasswordConfirmation($this->passwordConfirmationData['event']);
        }

        if (isset($this->passwordConfirmationData['dispatch'])) {
            $dispatch = $this->passwordConfirmationData['dispatch'];

            $this->dispatch($dispatch['event'], ...$dispatch['args']);
        }

        if (isset($this->passwordConfirmationData['modal'])) {
            $modal = $this->passwordConfirmationData['modal'];
            $arguments = $modal['arguments'];
            $arguments['passwordConfirmationToken'] = $passwordConfirmationToken;

            $this->dispatch(
                'openModal',
                $modal['component'],
                $arguments,
                $modal['attributes'],
            );
        }
    }

    #[On('core.passwordConfirmed')]
    public function handlePasswordConfirmation($event): void
    {
        if (is_array($event) && ($event['type'] ?? null) === 'callback') {
            unserialize($event['payload'])->getClosure()();
        } elseif (is_string($event) && preg_match('/^(\w+)\((.*)\)$/', $event, $matches)) {
            $methodName = $matches[1];
            $arguments = array_map('trim', explode(',', $matches[2]));

            if (method_exists($this, $methodName)) {
                call_user_func_array([$this, $methodName], $arguments);
            }
        }
    }

    private function createPasswordConfirmationToken(): string
    {
        $token = str()->random(64);

        cache()->put($this->passwordConfirmationTokenKey($token), true, now()->addMinutes(5));

        return $token;
    }

    private function passwordConfirmationTokenKey(string $token): string
    {
        return 'core.password_confirmation.' . hash('sha256', $token);
    }

    public function mountWithPasswordConfirmation(?string $passwordConfirmationToken = null): void
    {
        if (!$this instanceof ModalComponent || !$this->requiresPasswordConfirmationToken()) {
            return;
        }

        $hasValidToken = $passwordConfirmationToken
            && cache()->pull($this->passwordConfirmationTokenKey($passwordConfirmationToken));

        if (!$hasValidToken) {
            abort(403);
        }
    }

    protected function requiresPasswordConfirmationToken(): bool
    {
        return true;
    }

    public function passwordFunction(string $callable, ...$args): static
    {
        $this->passwordConfirmationData['event'] = $callable . '(' . implode(', ', $args) . ')';

        return $this;
    }

    public function passwordCallback(Closure $callback): static
    {
        $this->passwordConfirmationData['event'] = $this->passwordSerializeCallback($callback);

        return $this;
    }

    private function passwordSerializeCallback(Closure $callback): array
    {
        return [
            'type' => 'callback',
            'payload' => serialize(new SerializableClosure($callback)),
        ];
    }

    public function passwordCancelCallback(Closure $callback): static
    {
        $this->passwordConfirmationData['cancelEvent'] = $this->passwordSerializeCallback($callback);

        return $this;
    }

    public function passwordDispatch(string $dispatch, ...$args): static
    {
        $this->passwordConfirmationData['dispatch'] = [
            'event' => $dispatch,
            'args' => $args,
        ];

        return $this;
    }

    public function passwordModal(string $modalComponent, array $arguments = [], array $modalAttributes = []): static
    {
        $this->passwordConfirmationData['modal'] = [
            'component' => $modalComponent,
            'arguments' => $arguments,
            'attributes' => $modalAttributes,
        ];

        return $this;
    }
}
