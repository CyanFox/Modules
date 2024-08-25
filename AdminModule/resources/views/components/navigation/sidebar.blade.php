<div x-data="{ sidebarOpen: false, pinned: $persist(false) }" class="relative">
    <style>
        .sidebar {
            width: 60px;
            transition: width 0.3s ease;
            z-index: 9;
        }

        .sidebar.expanded {
            width: 200px;
        }

        .sidebar.expanded .text-hidden {
            display: block;
        }

        .text-hidden {
            display: none;
        }
    </style>

    <!-- Mobile Nav & sidebar -->
    <div class="dark:bg-dark-700 dark:border-none border bg-white md:hidden">
        <div class="flex items-center justify-between px-4 py-2">
            <div>
                <x-dropdown>
                    <x-slot:action>
                        <i x-on:click="show = !show" class="icon-menu text-xl dark:text-white cursor-pointer"></i>
                    </x-slot:action>
                    @can('adminmodule.dashboard.view')
                        <a href="{{ route('admin.dashboard') }}" wire:navigate>
                            <x-dropdown.items>
                                <i class="icon-layout-dashboard text-md"></i>
                                <span class="ml-2 text-md">{{ __('adminmodule::navigation.dashboard') }}</span>
                            </x-dropdown.items>
                        </a>
                    @endcan

                    @can('adminmodule.users.view')
                        <a href="{{ route('admin.users') }}" wire:navigate>
                            <x-dropdown.items>
                                <i class="icon-users text-md"></i>
                                <span class="ml-2 text-md">{{ __('adminmodule::navigation.users') }}</span>
                            </x-dropdown.items>
                        </a>
                    @endcan

                    @can('adminmodule.groups.view')
                        <a href="{{ route('admin.groups') }}" wire:navigate>
                            <x-dropdown.items>
                                <i class="icon-shield text-md"></i>
                                <span class="ml-2 text-md">{{ __('adminmodule::navigation.groups') }}</span>
                            </x-dropdown.items>
                        </a>
                    @endcan

                    @can('adminmodule.permissions.view')
                        <a href="{{ route('admin.permissions') }}" wire:navigate>
                            <x-dropdown.items>
                                <i class="icon-key-round text-md"></i>
                                <span class="ml-2 text-md">{{ __('adminmodule::navigation.permissions') }}</span>
                            </x-dropdown.items>
                        </a>
                    @endcan

                    @can('adminmodule.settings.view')
                        <a href="{{ route('admin.settings') }}" wire:navigate>
                            <x-dropdown.items>
                                <i class="icon-settings-2 text-md"></i>
                                <span class="ml-2 text-md">{{ __('adminmodule::navigation.settings') }}</span>
                            </x-dropdown.items>
                        </a>
                    @endcan

                    @can('adminmodule.modules.view')
                        <a href="{{ route('admin.modules') }}" wire:navigate>
                            <x-dropdown.items>
                                <i class="icon-boxes text-md"></i>
                                <span class="ml-2 text-md">{{ __('adminmodule::navigation.modules') }}</span>
                            </x-dropdown.items>
                        </a>
                    @endcan

                    <x-view-integration name="adminmodule.mobile.nav"/>
                </x-dropdown>
            </div>
            <div>
                <img src="{{ asset(setting('settings.logo_path')) }}" alt="Logo" class="w-16 h-16">
            </div>
            <div>
                <x-dropdown>
                    <x-slot:action>
                        <img x-on:click="show = !show" src="{{ user()->getUser(auth()->user())->getAvatarURL() }}"
                             alt="Profile" class="w-9 h-9 rounded-full cursor-pointer">
                    </x-slot:action>
                    <a href="{{ route('account.profile') }}" wire:navigate>
                        <x-dropdown.items>
                            <i class="icon-user text-md"></i>
                            <span class="ml-2 text-md">{{ __('adminmodule::navigation.profile') }}</span>
                        </x-dropdown.items>
                    </a>
                    @if(module()->getModule('DashboardModule')->isEnabled())
                        @if(setting('dashboardmodule.routes.home'))
                            <a href="{{ route('home') }}" wire:navigate>
                                <x-dropdown.items>
                                    <i class="icon-house text-md"></i>
                                    <span class="ml-2 text-md">{{ __('adminmodule::navigation.home') }}</span>
                                </x-dropdown.items>
                            </a>
                        @elseif(setting('dashboardmodule.routes.dashboard'))
                            <a href="{{ route('dashboard') }}" wire:navigate>
                                <x-dropdown.items>
                                    <i class="icon-house text-md"></i>
                                    <span class="ml-2 text-md">{{ __('adminmodule::navigation.dashboard') }}</span>
                                </x-dropdown.items>
                            </a>
                        @endif
                    @endif
                    <a href="{{ route('auth.logout') }}">
                        <x-dropdown.items separator>
                            <i class="icon-log-out text-md"></i>
                            <span class="ml-2 text-md">{{ __('adminmodule::navigation.logout') }}</span>
                        </x-dropdown.items>
                    </a>
                    <x-view-integration name="adminmodule.profile"/>
                </x-dropdown>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav
        class="dark:bg-dark-700 dark:border-none border bg-white hidden md:flex items-center justify-between px-4 py-3">
        <div class="ml-auto flex items-center">
            <div class="relative inline-block">
                <x-dropdown>
                    <x-slot:action>
                        <img x-on:click="show = !show" src="{{ user()->getUser(auth()->user())->getAvatarURL() }}"
                             alt="Profile" class="w-9 h-9 rounded-full cursor-pointer">
                    </x-slot:action>
                    <a href="{{ route('account.profile') }}" wire:navigate>
                        <x-dropdown.items>
                            <i class="icon-user text-md"></i>
                            <span class="ml-2 text-md">{{ __('adminmodule::navigation.profile') }}</span>
                        </x-dropdown.items>
                    </a>
                    @if(module()->getModule('DashboardModule')->isEnabled())
                        @if(setting('dashboardmodule.routes.home'))
                            <a href="{{ route('home') }}" wire:navigate>
                                <x-dropdown.items>
                                    <i class="icon-house text-md"></i>
                                    <span class="ml-2 text-md">{{ __('adminmodule::navigation.home') }}</span>
                                </x-dropdown.items>
                            </a>
                        @elseif(setting('dashboardmodule.routes.dashboard'))
                            <a href="{{ route('dashboard') }}" wire:navigate>
                                <x-dropdown.items>
                                    <i class="icon-house text-md"></i>
                                    <span class="ml-2 text-md">{{ __('adminmodule::navigation.dashboard') }}</span>
                                </x-dropdown.items>
                            </a>
                        @endif
                    @endif
                    <a href="{{ route('auth.logout') }}">
                        <x-dropdown.items separator>
                            <i class="icon-log-out text-md"></i>
                            <span class="ml-2 text-md">{{ __('adminmodule::navigation.logout') }}</span>
                        </x-dropdown.items>
                    </a>

                    <x-view-integration name="adminmodule.profile"/>
                </x-dropdown>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div x-bind:class="{'expanded': sidebarOpen || pinned, 'pinned': pinned}"
         @mouseover="sidebarOpen = true"
         @mouseleave="sidebarOpen = false" @touchstart="sidebarOpen = !sidebarOpen; pinned = false"
         class="flex flex-col items-center w-40 fixed top-0 left-0 h-full sidebar overflow-x-hidden overflow-y-auto dark:bg-dark-700 dark:border-none border bg-white transform transition-transform md:flex hidden">

        <div class="flex items-center justify-center w-full h-16 mt-1">
            <img src="{{ asset(setting('settings.logo_path')) }}" alt="Logo" class="w-16 h-16">
            <span class="ml-1 font-bold dark:text-white hidden"
                  x-bind:class="{'hidden': !sidebarOpen && !pinned}">{{ setting('settings.name') }}</span>
        </div>
        <div class="w-full px-2 mt-4">
            <div class="flex flex-col items-center w-full mt-3 mb-3">
                @can('adminmodule.dashboard.view')
                    <x-adminmodule::sidebar-entry :label="__('adminmodule::navigation.dashboard')"
                                                  route="admin.dashboard"
                                                  icon="icon-layout-dashboard"/>
                @endcan

                @can('adminmodule.users.view')
                    <x-adminmodule::sidebar-entry :label="__('adminmodule::navigation.users')"
                                                  route="admin.users"
                                                  icon="icon-users"/>
                @endcan

                @can('adminmodule.groups.view')
                    <x-adminmodule::sidebar-entry :label="__('adminmodule::navigation.groups')"
                                                  route="admin.groups"
                                                  icon="icon-shield"/>
                @endcan

                @can('adminmodule.permissions.view')
                    <x-adminmodule::sidebar-entry :label="__('adminmodule::navigation.permissions')"
                                                  route="admin.permissions"
                                                  icon="icon-key-round"/>
                @endcan

                @can('adminmodule.settings.view')
                    <x-adminmodule::sidebar-entry :label="__('adminmodule::navigation.settings')"
                                                  route="admin.settings"
                                                  icon="icon-settings-2"/>
                @endcan

                @can('adminmodule.modules.view')
                    <x-adminmodule::sidebar-entry :label="__('adminmodule::navigation.modules')"
                                                  route="admin.modules"
                                                  icon="icon-boxes"/>
                @endcan

                <x-view-integration name="adminmodule.sidebar.top"/>
            </div>
            <hr class="border-t border-gray-700 my-2">
            <div class="flex flex-col items-center w-full my-2">
                <x-adminmodule::sidebar-entry :label="__('adminmodule::navigation.profile')"
                                              route="account.profile"
                                              icon="icon-user"/>

                @if(module()->getModule('DashboardModule')->isEnabled())
                    @if(setting('dashboardmodule.routes.home'))
                        <x-adminmodule::sidebar-entry :label="__('adminmodule::navigation.home')"
                                                      route="home"
                                                      icon="icon-house"/>
                    @elseif(setting('dashboardmodule.routes.dashboard'))
                        <x-adminmodule::sidebar-entry :label="__('adminmodule::navigation.dashboard')"
                                                      route="dashboard"
                                                      icon="icon-house"/>
                    @endif
                @endif

                <x-adminmodule::sidebar-entry :label="__('adminmodule::navigation.logout')"
                                              route="auth.logout"
                                              icon="icon-log-out" :navigate="false"/>

                <x-view-integration name="adminmodule.sidebar.bottom"/>
            </div>
        </div>
        <div class="px-2 py-2 w-full mt-auto">
            <a @click="pinned = !pinned" role="button"
               class="flex items-center justify-center w-full h-12 rounded dark:hover:bg-dark-600 hover:bg-gray-200 sm:inline-flex hidden"
               :class="[pinned ? 'dark:bg-dark-600 bg-gray-200' : '']">
                <i :class="[pinned ? 'transform rotate-90 transition-transform duration-300' :
            'transform rotate-0 transition-transform duration-300']" class="icon-pin dark:text-white"></i>
            </a>
        </div>


    </div>

    <!-- Content -->
    <div :class="{'md:ml-20': !pinned, 'md:ml-56': pinned}"
         class="transition-all duration-300 ease-in-out pt-7 px-2 md:px-5 pb-4">
        {{ $slot }}
    </div>
</div>
