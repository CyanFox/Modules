<x-dropdown class="relative inline-flex">
    <x-dropdown.trigger :tooltip="__('dashboard::dashboard.tooltips.notifications')">
        <span class="relative inline-flex text-on-surface dark:text-on-surface-dark cursor-pointer">
            <i class="icon-bell"></i>
            @if($unreadNotificationsCount > 0)
                <span
                    class="pointer-events-none absolute -right-1.5 -top-1.5 inline-flex h-4 min-w-4 items-center justify-center rounded-full bg-primary px-1 text-xs font-semibold leading-none text-on-primary">
                    {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                </span>
            @endif
        </span>
    </x-dropdown.trigger>

    <x-dropdown.items position="bottom-right" class="w-80">
        <div
            class="flex flex-wrap items-center justify-between border-b border-outline px-4 py-2.5 dark:border-outline-dark">
            <span class="text-sm font-bold text-on-surface-strong dark:text-on-surface-dark-strong">
                {{ __('dashboard::dashboard.notifications.title') }}
            </span>
            <div class="flex items-center gap-2">
                @if($unreadNotificationsCount > 0)
                    <x-link type="button" wire:click="markAllAsRead"
                            wire:loading.attr="disabled"
                            class="text-xs">
                        {{ __('dashboard::dashboard.notifications.mark_all_as_read') }}
                    </x-link>
                @endif
            </div>
        </div>

        @forelse($notifications as $notification)
            <div class="flex flex-col divide-y divide-outline dark:divide-outline-dark">
                <x-dashboard::notifications.item :notification="$notification"
                                                 :unread="$notification->unread()"/>
            </div>
        @empty
            <p class="text-on-surface dark:text-on-surface-dark px-4 py-6 text-center text-sm">
                {{ __('dashboard::dashboard.notifications.empty') }}
            </p>
        @endforelse
    </x-dropdown.items>
</x-dropdown>
