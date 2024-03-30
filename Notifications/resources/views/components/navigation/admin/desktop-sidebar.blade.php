<a class="flex items-center w-full h-12 px-3.5 mt-2 rounded hover:bg-base-300 {{ request()->routeIs('admin.notifications*') ? 'bg-base-300' : '' }}"
   href="{{ route('admin.notifications') }}" wire:navigate>
    <i class="icon-bell"></i>
    <span
        class="ml-2 text-sm font-medium text-hidden">{{ __('notifications::navigation.sidebar.notifications') }}</span>
</a>
