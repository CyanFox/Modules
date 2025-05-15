<div x-data="{
    sidebarIsOpen: false, // State for whether the sidebar is open
    sidebarPinned: $persist(false), // State for whether the sidebar is pinned, persisted across sessions
    sidebarHovered: false, // State for whether the sidebar is hovered
    init() {
        this.updateSidebarState(); // Initialize the sidebar state

        // Update sidebar state on window resize
        window.addEventListener('resize', () => this.updateSidebarState());

        // Watch for changes in sidebarHovered state to adjust sidebar width
        this.$watch('sidebarHovered', value => {
            if (value && !this.sidebarPinned) {
                this.$refs.sidebar.style.width = '16rem';
            } else if (!this.sidebarPinned) {
                this.$refs.sidebar.style.width = '4rem';
            }
        });
    },
    updateSidebarState() {
        if (window.innerWidth < 768) {
            this.sidebarPinned = true; // Pin the sidebar on smaller screens
            this.sidebarIsOpen = false; // Close the sidebar on smaller screens
        } else if (!this.sidebarPinned) {
            this.sidebarIsOpen = false; // Ensure the sidebar is closed if not pinned
        }
    }
}"
     x-init="init()"
     class="relative flex w-full flex-col md:flex-row">
    <a class="sr-only" href="#main-content">skip to the main content</a>

    <div x-cloak x-show="sidebarIsOpen"
         class="fixed inset-0 z-20 bg-neutral-950/10 backdrop-blur-xs md:hidden"
         aria-hidden="true"
         x-on:click="sidebarIsOpen = false"
         x-transition.opacity></div>

    <nav x-cloak
         x-ref="sidebar"
         x-on:mouseenter="sidebarHovered = true"
         x-on:mouseleave="sidebarHovered = false"
         class="fixed left-0 z-30 flex h-svh shrink-0 flex-col border-r border-neutral-300 bg-neutral-50 transition-all duration-300 ease-in-out dark:border-neutral-700 dark:bg-neutral-900 overflow-hidden"
         :class="[
       sidebarIsOpen ? 'translate-x-0' : '-translate-x-60 md:translate-x-0',
       sidebarPinned ? 'md:w-64' : 'md:w-16'
     ]"
         aria-label="sidebar navigation">

        <div class="flex h-16 px-2 justify-center">
            <a href="{{ route('dashboard') }}"
               class="flex items-center text-xl font-bold text-neutral-900 dark:text-white"
               :class="(!sidebarPinned && !sidebarHovered) ? 'justify-center' : ''">
                <span class="sr-only">dashboard</span>
                <img src="{{ settings('internal.app.logo', config('settings.logo_path')) }}" alt="Logo"
                     class="{{ settings('dashboard.logo_size') }}">
                <p class="pl-4 truncate transition-opacity duration-300"
                   :class="(!sidebarPinned && !sidebarHovered) ? 'md:hidden' : 'md:block'">
                    {{ settings('internal.app.name', config('app.name')) }}
                </p>
            </a>
        </div>

        <div class="flex flex-col gap-2 items-center overflow-y-auto py-6 px-2">
            @can('admin.dashboard')
                <x-admin::sidebar-item
                    icon="icon-layout-dashboard"
                    :label="__('admin::navigation.dashboard')"
                    route="admin.dashboard"/>
            @endcan

            @can('admin.users')
                <x-admin::sidebar-item
                    icon="icon-users"
                    :label="__('admin::navigation.users')"
                    route="admin.users"/>
            @endcan


            @can('admin.groups')
                <x-admin::sidebar-item
                    icon="icon-shield"
                    :label="__('admin::navigation.groups')"
                    route="admin.groups"/>
            @endcan

            @can('admin.permissions')
                <x-admin::sidebar-item
                    icon="icon-key-round"
                    :label="__('admin::navigation.permissions')"
                    route="admin.permissions"/>
            @endcan

            @can('admin.settings')
                <x-admin::sidebar-item
                    icon="icon-settings"
                    :label="__('admin::navigation.settings')"
                    route="admin.settings"/>
            @endcan

            @can('admin.modules')
                <x-admin::sidebar-item
                    icon="icon-package"
                    :label="__('admin::navigation.modules')"
                    route="admin.modules"/>
            @endcan

            @foreach(\Modules\Admin\Facades\SidebarManager::getAll() as $sidebarItem)
                <x-admin::sidebar-item
                    :icon="$sidebarItem['icon']"
                    :label="$sidebarItem['label']"
                    :url="$sidebarItem['url']"
                    :route="$sidebarItem['route']"/>

                <x-view-integration name="admin.sidebar.items.{{ $sidebarItem['label'] }}"/>
            @endforeach

            <x-view-integration name="admin.sidebar.items"/>
        </div>

        <button
            x-on:click="sidebarPinned = !sidebarPinned"
            class="hidden cursor-pointer md:flex items-center justify-center h-8 mx-2 my-2 mt-auto rounded-md font-medium text-neutral-600 underline-offset-2 hover:bg-black/5 hover:text-neutral-900 focus-visible:underline focus:outline-hidden dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white overflow-hidden"
        >
            <i :class="[sidebarPinned ? 'transform rotate-90 transition-transform duration-300' :
            'transform rotate-0 transition-transform duration-300']" class="icon-pin dark:text-white"></i>
        </button>
    </nav>

    <div class="h-svh w-full overflow-y-auto bg-white dark:bg-neutral-950 transition-all duration-300 ease-in-out" :class="sidebarPinned ? 'md:ml-64' : 'md:ml-16'">
        <nav
            class="sticky top-0 z-10 flex items-center border-b border-neutral-300 bg-neutral-50 px-4 py-2 dark:border-neutral-700 dark:bg-neutral-900"
            aria-label="top navigation bar">

            <button type="button" class="md:hidden inline-block text-neutral-600 dark:text-neutral-300"
                    x-on:click="sidebarIsOpen = true">
                <i class="icon-panel-right-close text-xl"></i>
                <span class="sr-only">sidebar toggle</span>
            </button>

            <div x-data="{ userDropdownIsOpen: false }" class="relative ml-auto"
                 x-on:keydown.esc.window="userDropdownIsOpen = false">
                <button type="button"
                        class="flex w-full cursor-pointer items-center rounded-md gap-2 p-2 text-left text-neutral-600 hover:bg-black/5 hover:text-neutral-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white dark:focus-visible:outline-white"
                        x-bind:class="userDropdownIsOpen ? 'bg-black/10 dark:bg-white/10' : ''" aria-haspopup="true"
                        x-on:click="userDropdownIsOpen = ! userDropdownIsOpen"
                        x-bind:aria-expanded="userDropdownIsOpen">
                    <img src="{{ auth()->user()->avatar() }}" class="size-8 object-cover rounded-md" alt="avatar"
                         aria-hidden="true"/>
                    <div class="hidden md:flex flex-col">
                        <span
                            class="text-sm font-bold text-neutral-900 dark:text-white">{{ auth()->user()->fullName() }}</span>
                        <span class="text-xs" aria-hidden="true">{{ auth()->user()->username }}</span>
                        <span class="sr-only">profile settings</span>
                    </div>
                </button>

                <div x-cloak x-show="userDropdownIsOpen"
                     class="absolute top-14 right-0 z-20 h-fit w-48 px-1 border divide-y divide-neutral-300 border-neutral-300 bg-white dark:divide-neutral-700 dark:border-neutral-700 dark:bg-neutral-950 rounded-md"
                     role="menu" x-on:click.outside="userDropdownIsOpen = false"
                     x-on:keydown.down.prevent="$focus.wrap().next()" x-on:keydown.up.prevent="$focus.wrap().previous()"
                     x-transition="" x-trap="userDropdownIsOpen">

                    <div class="flex flex-col py-1.5">
                        <x-dashboard::profile-item icon="icon-user" label="{{ __('admin::navigation.profile') }}" route="account.profile"/>
                        <x-dashboard::profile-item icon="icon-house" label="{{ __('admin::navigation.home') }}" url="/" :external="true"/>

                        <x-view-integration name="admin.profile.items"/>
                    </div>

                    <x-view-integration name="admin.profile.items.end"/>

                    <div class="flex flex-col py-1.5">
                        <x-dashboard::profile-item icon="icon-log-out" label="{{ __('admin::navigation.logout') }}" route="auth.logout"
                                                   external/>
                    </div>
                </div>
            </div>
        </nav>

        <div id="main-content" class="p-4">
            <div class="overflow-y-auto">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
