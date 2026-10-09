<?php

namespace Modules\Core\Traits;

use Closure;
use Laravel\SerializableClosure\SerializableClosure;
use Livewire\Attributes\On;

trait WithConfirmation
{
    private $confirmationData = [];

    public function dialog(): static
    {
        $this->confirmationData = [];

        return $this;
    }

    public function question(string $title, string $description): static
    {
        $this->confirmationData['title'] = $title;
        $this->confirmationData['description'] = $description;

        return $this;
    }

    public function cancel(string $text, string $color = 'primary'): static
    {
        $this->confirmationData['cancel'] = $text;
        $this->confirmationData['cancelColor'] = $color;

        return $this;
    }

    public function confirm(string $text, string $color = 'success'): static
    {
        $this->confirmationData['confirm'] = $text;
        $this->confirmationData['confirmColor'] = $color;

        return $this;
    }

    public function icon(string $icon, string $color = 'text-warning'): static
    {
        $this->confirmationData['icon'] = $icon;
        $this->confirmationData['iconColor'] = $color;

        return $this;
    }

    public function needsPasswordConfirmation(): static
    {
        $this->confirmationData['needsPasswordConfirmation'] = true;

        return $this;
    }

    public function method(string $callable, ...$args): static
    {
        $this->confirmationData['event'] = $callable . '(' . implode(', ', $args) . ')';

        return $this;
    }

    public function callback(Closure $callback): static
    {
        $this->confirmationData['event'] = $this->serializeCallback($callback);

        return $this;
    }

    private function serializeCallback(Closure $callback): array
    {
        return [
            'type' => 'callback',
            'payload' => serialize(new SerializableClosure($callback)),
        ];
    }

    public function cancelCallback(Closure $callback): static
    {
        $this->confirmationData['cancelEvent'] = $this->serializeCallback($callback);

        return $this;
    }

    public function dispatchEvent(string $event, ...$args): static
    {
        $this->confirmationData['event'] = [
            'event' => $event,
            'args' => $args,
        ];

        return $this;
    }

    public function send(): void
    {
        $this->dispatch('openModal', 'core::components.modals.confirmation', [
            'title' => $this->confirmationData['title'],
            'description' => $this->confirmationData['description'],
            'cancel' => $this->confirmationData['cancel'] ?? '',
            'cancelColor' => $this->confirmationData['cancelColor'] ?? 'primary',
            'confirm' => $this->confirmationData['confirm'] ?? __('messages.buttons.confirm'),
            'confirmColor' => $this->confirmationData['confirmColor'] ?? 'success',
            'icon' => $this->confirmationData['icon'] ?? 'icon-info',
            'iconColor' => $this->confirmationData['iconColor'],
            'needsPasswordConfirmation' => $this->confirmationData['needsPasswordConfirmation'] ?? false,
            'event' => $this->confirmationData['event'] ?? null,
            'cancelEvent' => $this->confirmationData['cancelEvent'] ?? null,
        ]);
    }

    #[On('core.confirmation.confirmed')]
    public function handleConfirmation(mixed $event): void
    {
        if (is_array($event)) {
            if (($event['type'] ?? null) === 'callback') {
                $callback = unserialize($event['payload'])->getClosure();
                $callback();
            } elseif (isset($event['event'])) {
                $eventName = $event['event'];
                $arguments = $event['args'] ?? [];
                $this->dispatch($eventName, ...$arguments);
            } elseif (isset($event['class'], $event['method'])) {
                $class = $event['class'];
                $method = $event['method'];
                $arguments = $event['args'] ?? [];
                call_user_func_array([app($class), $method], $arguments);
            }
        } elseif (is_string($event)) {
            if (preg_match('/^([a-zA-Z0-9_]+)\((.*)\)$/', $event, $matches)) {
                $method = $matches[1];
                $args = array_map('trim', explode(',', $matches[2]));
                call_user_func_array([$this, $method], $args);
            }
        }
    }
}
