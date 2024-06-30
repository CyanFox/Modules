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
                    @auth()
                        <a href="{{ route('dashboard') }}" wire:navigate>
                            <x-dropdown.items>
                                <i class="icon-layout-dashboard text-md"></i>
                                <span class="ml-2 text-md">{{ __('dashboardmodule::dashboard.dashboard') }}</span>
                            </x-dropdown.items>
                        </a>

                        <x-view-integration name="dashboardmodule.mobile.auth.nav"/>
                    @endauth
                    @guest()
                        <x-view-integration name="dashboardmodule.mobile.guest.nav"/>
                    @endguest
                </x-dropdown>
            </div>
            <div>
                <img src="{{ asset(setting('settings.logo_path')) }}" alt="Logo" class="w-16 h-16">
            </div>
            <div>
                @auth()
                    <x-dropdown>
                        <x-slot:action>
                            <img x-on:click="show = !show" src="{{ user()->getUser(auth()->user())->getAvatarURL() }}"
                                 alt="Profile" class="w-9 h-9 rounded-full cursor-pointer">
                        </x-slot:action>
                        <a href="{{ route('account.profile') }}" wire:navigate>
                            <x-dropdown.items>
                                <i class="icon-user text-md"></i>
                                <span class="ml-2 text-md">{{ __('dashboardmodule::dashboard.profile') }}</span>
                            </x-dropdown.items>
                        </a>
                        @if(module()->getModule('AdminModule')->isEnabled())
                            @can('adminmodule.admin')
                                <a href="{{ route('admin.dashboard') }}" wire:navigate>
                                    <x-dropdown.items>
                                        <i class="icon-settings text-md"></i>
                                        <span class="ml-2 text-md">{{ __('dashboardmodule::dashboard.admin') }}</span>
                                    </x-dropdown.items>
                                </a>
                            @endcan
                        @endif
                        <a href="{{ route('auth.logout') }}">
                            <x-dropdown.items separator>
                                <i class="icon-log-out text-md"></i>
                                <span class="ml-2 text-md">{{ __('dashboardmodule::dashboard.logout') }}</span>
                            </x-dropdown.items>
                        </a>
                        <x-view-integration name="dashboardmodule.profile.mobile.dropdown"/>
                    </x-dropdown>
                @endauth
                @guest()
                    <a href="{{ route('auth.login', ['redirect' => request()->fullUrl()]) }}" class="flex items-center">
                        <i class="icon-log-in text-md"></i>
                        <span class="ml-2 text-md">{{ __('dashboardmodule::dashboard.login') }}</span>
                    </a>

                    <x-view-integration name="dashboardmodule.profile.mobile.guest"/>
                @endguest
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav
        class="dark:bg-dark-700 dark:border-none border bg-white hidden md:flex items-center justify-between px-4 py-3">
        <div class="ml-auto flex items-center">
            <div class="relative inline-block">
                @auth()
                    <x-dropdown>
                        <x-slot:action>
                            <img x-on:click="show = !show" src="{{ user()->getUser(auth()->user())->getAvatarURL() }}"
                                 alt="Profile" class="w-9 h-9 rounded-full cursor-pointer">
                        </x-slot:action>
                        <a href="{{ route('account.profile') }}" wire:navigate>
                            <x-dropdown.items>
                                <i class="icon-user text-md"></i>
                                <span class="ml-2 text-md">{{ __('dashboardmodule::dashboard.profile') }}</span>
                            </x-dropdown.items>
                        </a>
                        @if(module()->getModule('AdminModule')->isEnabled())
                            @can('adminmodule.admin')
                                <a href="{{ route('admin.dashboard') }}">
                                    <x-dropdown.items>
                                        <i class="icon-settings text-md"></i>
                                        <span class="ml-2 text-md">{{ __('dashboardmodule::dashboard.admin') }}</span>
                                    </x-dropdown.items>
                                </a>
                            @endcan
                        @endif
                        <a href="{{ route('auth.logout') }}">
                            <x-dropdown.items separator>
                                <i class="icon-log-out text-md"></i>
                                <span class="ml-2 text-md">{{ __('dashboardmodule::dashboard.logout') }}</span>
                            </x-dropdown.items>
                        </a>

                        <x-view-integration name="dashboardmodule.profile.dropdown"/>
                    </x-dropdown>
                @endauth
                @guest()
                    <a href="{{ route('auth.login', ['redirect' => request()->fullUrl()]) }}" class="flex items-center">
                        <i class="icon-log-in text-md"></i>
                        <span class="ml-2 text-md">{{ __('dashboardmodule::dashboard.login') }}</span>
                    </a>

                    <x-view-integration name="dashboardmodule.profile.guest"/>
                @endguest
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
                @auth()
                    <x-dashboardmodule::sidebar-entry :label="__('dashboardmodule::dashboard.dashboard')"
                                                      route="dashboard"
                                                      icon="icon-layout-dashboard"/>
                    <x-view-integration name="dashboardmodule.sidebar.auth.header"/>
                @endauth
                @guest()
                    <x-view-integration name="dashboardmodule.sidebar.guest.header"/>
                @endguest

            </div>
            <hr class="border-t border-gray-700 my-2">
            <div class="flex flex-col items-center w-full my-2">
                @auth()
                    <x-dashboardmodule::sidebar-entry :label="__('dashboardmodule::dashboard.profile')"
                                                      route="account.profile"
                                                      icon="icon-user"/>

                    @if(module()->getModule('AdminModule')->isEnabled())
                        @can('adminmodule.admin')
                            <x-dashboardmodule::sidebar-entry :label="__('dashboardmodule::dashboard.admin')"
                                                              route="admin.dashboard"
                                                              icon="icon-settings"/>
                        @endcan
                    @endif

                    <x-dashboardmodule::sidebar-entry :label="__('dashboardmodule::dashboard.logout')"
                                                      route="auth.logout"
                                                      icon="icon-log-out" :navigate="false"/>

                    <x-view-integration name="dashboardmodule.sidebar.auth.footer"/>
                @endauth
                @guest()
                    <x-view-integration name="dashboardmodule.sidebar.guest.footer"/>
                @endguest
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
