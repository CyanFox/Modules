@props(['title' => __('Confirm Password'), 'content' => __('For your security, please confirm your password to continue.'), 'button' => __('Confirm')])

@php
    $confirmableId = md5($attributes->wire('then'));
@endphp

<span
    {{ $attributes->wire('then') }}
    x-data
    x-ref="span"
    x-on:click="$wire.startConfirmingPassword('{{ $confirmableId }}')"
    x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '{{ $confirmableId }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);"
>
    {{ $slot }}
</span>

@once
    <x-modal wire:model="confirmingPassword">
        <x-modal.content>
            <x-modal.header>
                {{ $title }}
            </x-modal.header>

            {{ $content }}

            <div class="mt-4" x-data="{}"
                 x-on:confirming-password.window="setTimeout(() => $refs.confirmable_password.focus(), 250)">
                <x-password autocomplete="current-password"
                         x-ref="confirmable_password"
                         wire:model="confirmablePassword"
                         wire:keydown.enter="confirmPassword"/>

            </div>

            <x-modal.footer>
                <x-button class="ms-3" dusk="confirm-password-button" wire:click="confirmPassword"
                          wire:loading.attr="disabled">
                    {{ $button }}
                </x-button>
            </x-modal.footer>
        </x-modal.content>
    </x-modal>
@endonce
