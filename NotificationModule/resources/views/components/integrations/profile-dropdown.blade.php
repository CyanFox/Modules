@can('notificationsmodule.notifications.view')
    <a href="{{ route('notifications') }}">
        <x-dropdown.items>
            <i class="icon-bell text-md"></i>
            <span class="ml-2 text-md">{{ __('notificationmodule::navigation.notifications') }}</span>
        </x-dropdown.items>
    </a>
@endcan
