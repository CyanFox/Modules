<?php

namespace Modules\Auth\Traits;

use Illuminate\Support\Sleep;
use Livewire\Attributes\On;

trait WithPasswordConfirmation
{
    private $passwordConfirmationData = [];

    public function hasPasswordConfirmedSession($expiration = 300, $sleep = 0.25)
    {
        Sleep::sleep($sleep); // To prevent other modals closing

        if (session()->has('auth.password_confirmed_at') &&
            session('auth.password_confirmed_at') >= time() - $expiration) {
            return true;
        }

        return false;
    }

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
            return true;
        }

        $this->dispatch('openModal', 'auth::components.modals.confirm-password', [
            'title' => $this->passwordConfirmationData['title'] ?? null,
            'description' => $this->passwordConfirmationData['description'] ?? null,
            'event' => $this->passwordConfirmationData['event'],
        ]);

        return false;
    }

    public function passwordMethod(string $callable, ...$args): static
    {
        $this->passwordConfirmationData['event'] = $callable.'('.implode(', ', $args).')';

        return $this;
    }

    #[On('auth.passwordConfirmed')]
    public function handlePasswordConfirmation($event): void
    {
        if (preg_match('/^(\w+)\((.*)\)$/', $event, $matches)) {
            $methodName = $matches[1];
            $arguments = array_map('trim', explode(',', $matches[2]));
            call_user_func_array([$this, $methodName], $arguments);
        }
    }
}
