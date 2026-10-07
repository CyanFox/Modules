<div x-data="{ sidebarIsOpen: false }" class="relative flex w-full flex-col lg:flex-row">
    <a class="sr-only" href="#main-content">skip to the main content</a>

    <div x-cloak x-show="sidebarIsOpen" class="fixed inset-0 z-20 bg-surface-dark/10 backdrop-blur-xs lg:hidden"
         aria-hidden="true" x-on:click="sidebarIsOpen = false" x-transition.opacity></div>

    <nav x-cloak
         class="fixed left-0 z-30 flex h-svh w-60 shrink-0 flex-col border-r border-outline bg-surface-alt p-4 transition-transform duration-300 lg:w-64 lg:translate-x-0 lg:relative dark:border-outline-dark dark:bg-surface-dark-alt"
         x-bind:class="sidebarIsOpen ? 'translate-x-0' : '-translate-x-60'" aria-label="sidebar navigation">
        <a href="{{ route('dashboard') }}" wire:navigate
           class="text-2xl font-bold text-on-surface-strong dark:text-on-surface-dark-strong mb-4 flex justify-center items-center gap-2">
            <span class="sr-only">homepage</span>
            @hook('dashboard.sidebar.logo')
            @if(settings('dashboard.logo.show_logo'))
                <img src="{{ settings('app.logo') }}" alt="Logo"
                     style="{{ settings('dashboard.logo.css') }}">
            @endif

            @if(settings('dashboard.logo.show_app_name'))
                {{ settings('app.name') }}
            @endif
            @endhook
        </a>

        <div class="flex flex-col gap-2 overflow-y-auto pb-6 mt-3">
            @hook('dashboard.sidebar.items')
            <x-dashboard::sidebar.item icon="icon-layout-dashboard" route="dashboard">
                {{ __('dashboard::dashboard.tab_title') }}
            </x-dashboard::sidebar.item>

            @shook('s.dashboard.sidebar.items')
            @endhook
        </div>
    </nav>

    <div class="h-svh w-full overflow-y-auto bg-surface dark:bg-surface-dark">
        <nav
            class="sticky top-0 z-10 flex items-center justify-between border-b border-outline bg-surface-alt px-4 py-2 dark:border-outline-dark dark:bg-surface-dark-alt"
            aria-label="top navibation bar">

            <button type="button"
                    class="lg:hidden cursor-pointer inline-block text-on-surface dark:text-on-surface-dark text-lg"
                    x-on:click="sidebarIsOpen = true">
                <i class="icon-sidebar-open"></i>
                <span class="sr-only">sidebar toggle</span>
            </button>

            <x-breadcrumb class="hidden lg:inline-block">
                @foreach($breadcrumbs as $breadcrumb)
                    @if(data_get($breadcrumb, 'noWireNavigate') || data_get($breadcrumb, 'last'))
                        <x-breadcrumb.item href="{{ data_get($breadcrumb, 'url') }}"
                                           :last="data_get($breadcrumb, 'last')">
                            {{ data_get($breadcrumb, 'label') }}
                        </x-breadcrumb.item>
                    @else
                        <x-breadcrumb.item href="{{ data_get($breadcrumb, 'url') }}"
                                           :last="data_get($breadcrumb, 'last')" wire:navigate>
                            {{ data_get($breadcrumb, 'label') }}
                        </x-breadcrumb.item>
                    @endif
                @endforeach
            </x-breadcrumb>


            <!-- Profile Menu  -->
            <x-dropdown>
                <x-dropdown.trigger>
                    <button type="button"
                            class="flex cursor-pointer w-full items-center rounded-radius gap-2 p-2 text-left text-on-surface hover:bg-primary/5 hover:text-on-surface-strong focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong dark:focus-visible:outline-primary-dark">
                        <img src="{{ auth()->user()->getAvatar() }}"
                             class="size-8 object-cover rounded-radius" alt="avatar" aria-hidden="true"/>
                        <div class="hidden lg:flex flex-col">
                            <span
                                class="text-sm font-bold text-on-surface-strong dark:text-on-surface-dark-strong">{{ auth()->user()->getDisplayName() }}</span>
                            <span class="text-xs" aria-hidden="true">{{ auth()->user()->username }}</span>
                            <span class="sr-only">profile settings</span>
                        </div>
                    </button>
                </x-dropdown.trigger>
                <x-dropdown.items>
                    @hook('dashboard.profile.items')
                    @shook('s.dashboard.profile.items')

                    <x-divider class="my-0"/>
                    <x-dashboard::profile.item icon="icon-log-out" route="auth.logout">
                        {{ __('dashboard::dashboard.logout') }}
                    </x-dashboard::profile.item>
                    @endhook
                </x-dropdown.items>
            </x-dropdown>
        </nav>
        <!-- main content  -->
        <div id="main-content" class="p-4">
            <div class="overflow-y-auto">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
