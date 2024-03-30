<a class="flex items-center w-full h-12 px-3.5 mt-2 rounded hover:bg-base-300 {{ request()->routeIs('admin.actions*') ? 'bg-base-300' : '' }}"
   href="{{ route('admin.actions') }}" wire:navigate>
    <i class="icon-terminal"></i>
    <span
        class="ml-2 text-sm font-medium text-hidden">{{ __('actions::navigation.sidebar.actions') }}</span>
</a>
