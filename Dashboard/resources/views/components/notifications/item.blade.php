@props([
    'notification',
    'unread' => false,
])

<?php
$data = is_array($notification->data) ? $notification->data : [];
$view = data_get($data, 'view');
?>

<div class="flex items-start gap-2 px-4 py-2.5 text-sm">
    <div class="min-w-0 flex-1">
        @if($view && view()->exists($view))
            @include($view, ['notification' => $notification, 'data' => $data])
        @elseif(isset($data['title']))
            <span class="text-on-surface-strong dark:text-on-surface-dark-strong block">
                {{ $data['title'] }}
            </span>

            @isset($data['message'])
                <span class="text-on-surface dark:text-on-surface-dark mt-0.5 block">{{ $data['message'] }}</span>
            @endisset
        @else
            <span class="text-on-surface dark:text-on-surface-dark block">
                {{ data_get($data, 'message', __('dashboard::dashboard.notifications.fallback')) }}
            </span>
        @endif

        <span class="text-on-surface dark:text-on-surface-dark mt-1 block text-xs">
            <x-core::human-date :date="$notification->created_at"/>
        </span>
    </div>

    @if($unread)
        <button type="button"
                wire:click="markAsRead('{{ $notification->id }}')"
                wire:loading.attr="disabled"
                x-tooltip.raw="{{ __('dashboard::dashboard.notifications.mark_as_read') }}"
                class="cursor-pointer mt-0.5 shrink-0 text-primary dark:text-primary-dark"
                aria-label="{{ __('dashboard::dashboard.notifications.mark_as_read') }}">
            <i class="icon-check"></i>
        </button>
    @endif
</div>
