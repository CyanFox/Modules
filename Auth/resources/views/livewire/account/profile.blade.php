<div>
    <x-tab wire:model="tab">
        <x-tab.item class="flex-1 flex items-center justify-center" uuid="overview"
                    wire:click="$set('tab', 'overview')">
            <i class="icon-house"></i>
            <span class="ml-2">{{ __('auth::profile.tabs.overview') }}</span>
        </x-tab.item>
        <x-tab.item class="flex-1 flex items-center justify-center" uuid="sessions"
                    wire:click="$set('tab', 'sessions')">
            <i class="icon-monitor-dot"></i>
            <span class="ml-2">{{ __('auth::profile.tabs.sessions') }}</span>
        </x-tab.item>

        <x-view-integration name="auth.profile.tabs"/>
    </x-tab>

    @if($tab === 'overview')
        <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-4 mt-4">
            <div class="col-span-1 space-y-4">
                <x-card>
                    <div class="flex">
                        @if(settings('auth.profile.enable.change_avatar'))
                            <div class="h-16 w-16 relative mr-4 group">
                                <div
                                    class="absolute inset-0 bg-cover bg-center z-0 rounded-3xl group-hover:opacity-70 transition-opacity duration-300"
                                    style="background-image: url('{{ auth()->user()->avatar() }}')"></div>
                                <div
                                    wire:click="$dispatch('openModal', {component: 'auth::components.modals.change-avatar'})"
                                    class="opacity-0 group-hover:opacity-100 hover:cursor-pointer duration-300 absolute inset-0 z-10 flex justify-center items-center text-3xl text-white font-semibold">
                                    <i class="icon-upload"></i></div>
                            </div>

                            <x-view-integration name="auth.profile.overview.change_avatar"/>
                        @else
                            <img src="{{ auth()->user()->avatar() }}"
                                 alt="Avatar" class="h-16 w-16 rounded-3xl mr-4">

                            <x-view-integration name="auth.profile.overview.avatar"/>
                        @endif
                        <div>
                            <p class="font-bold">{{ auth()->user()->fullName() }}</p>
                            <p>{{ auth()->user()->username }}</p>

                            <x-view-integration name="auth.profile.overview.username"/>
                        </div>
                    </div>
                </x-card>
                <x-card>
                    <x-card.title>
                        {{ __('auth::profile.language_and_theme.title') }}
                    </x-card.title>

                    <form wire:submit="updateLanguageAndTheme" class="space-y-4">
                        <x-select wire:model="language" label="{{ __('auth::profile.language_and_theme.language') }}">
                            <option value="en">{{ __('auth::profile.language_and_theme.languages.en') }}</option>
                            <option value="de">{{ __('auth::profile.language_and_theme.languages.de') }}</option>
                            <x-view-integration name="auth.profile.language"/>
                        </x-select>

                        <x-select wire:model="theme" label="{{ __('auth::profile.language_and_theme.theme') }}">
                            <option value="light">{{ __('auth::profile.language_and_theme.themes.light') }}</option>
                            <option value="dark">{{ __('auth::profile.language_and_theme.themes.dark') }}</option>
                            <x-view-integration name="auth.profile.theme"/>
                        </x-select>

                        <x-view-integration name="auth.profile.overview.language_and_theme"/>

                        <x-divider/>

                        <x-button type="submit" class="w-full" loading="updateLanguageAndTheme">
                            {{ __('messages.buttons.save') }}
                        </x-button>
                    </form>
                </x-card>
                <x-card>
                    <x-card.title>
                        {{ __('auth::profile.actions.title') }}
                    </x-card.title>

                    <div class="flex flex-wrap gap-2">
                        @if(auth()->user()->two_factor_enabled)
                            <x-button wire:click="disableTwoFA" loading="disableTwoFA" color="warning" class="flex-1">
                                {{ __('auth::profile.actions.buttons.disable_two_factor') }}
                            </x-button>
                            <x-button
                                wire:click="$dispatch('openModal', {component: 'auth::components.modals.two-factor.regenerate-recovery-codes'})"
                                color="secondary" class="flex-1">
                                {{ __('auth::profile.actions.buttons.regenerate_recovery_codes') }}
                            </x-button>
                        @else
                            <x-button
                                wire:click="$dispatch('openModal', {component: 'auth::components.modals.two-factor.activate-two-f-a'})"
                                color="success" class="flex-1">
                                {{ __('auth::profile.actions.buttons.activate_two_factor') }}
                            </x-button>
                        @endif

                        @if(settings('auth.profile.enable.delete_account'))
                            <x-button wire:click="deleteAccount" loading="deleteAccount" color="danger" class="flex-1">
                                {{ __('auth::profile.actions.buttons.delete_account') }}
                            </x-button>
                        @endif

                        <x-view-integration name="auth.profile.overview.actions"/>
                    </div>
                </x-card>
            </div>


            <div class="col-span-2 space-y-4 lg:mt-0 mt-4">
                <x-card>
                    <x-card.title>
                        {{ __('auth::profile.profile.title') }}
                    </x-card.title>

                    <form wire:submit="updateProfile" class="space-y-3">
                        <div class="grid md:grid-cols-2 gap-4 mb-3">
                            <x-input wire:model="firstName">{{ __('auth::profile.profile.first_name') }}</x-input>
                            <x-input wire:model="lastName">{{ __('auth::profile.profile.last_name') }}</x-input>

                            <x-input wire:model="username" required>{{ __('auth::profile.profile.username') }}</x-input>
                            <x-input wire:model="email" required>{{ __('auth::profile.profile.email') }}</x-input>

                            <x-view-integration name="auth.profile.overview.profile"/>
                        </div>

                        <x-divider/>

                        <x-button type="submit" class="w-full lg:w-32" loading="updateProfile">
                            {{ __('messages.buttons.save') }}
                        </x-button>
                    </form>
                </x-card>

                <div class="col-span-2 space-y-4">
                    <x-card>
                        <x-card.title>
                            {{ __('auth::profile.password.title') }}
                        </x-card.title>

                        <form wire:submit="updatePassword" class="space-y-4">
                            @if(auth()->user()->password)
                                <x-password wire:model="currentPassword" required
                                            type="password">{{ __('auth::profile.password.current_password') }}</x-password>
                            @endif
                            <div class="grid md:grid-cols-2 gap-4 mb-3">
                                <x-password required
                                            wire:model="newPassword">{{ __('auth::profile.password.new_password') }}</x-password>
                                <x-password required
                                            wire:model="confirmPassword">{{ __('auth::profile.password.confirm_password') }}</x-password>
                            </div>

                            <x-view-integration name="auth.profile.overview.password"/>

                            <x-divider/>

                            <x-button type="submit" class="w-full lg:w-32" loading="updatePassword">
                                {{ __('messages.buttons.save') }}
                            </x-button>
                        </form>
                    </x-card>
                </div>
            </div>
        </div>
    @elseif($tab === 'sessions')
        <x-card class="mt-4">
            <x-card.title>
                <div class="flex justify-between">
                    <p>{{ __('auth::profile.sessions.title') }}</p>
                    <x-button wire:click="logoutAllSessions" loading="logoutAllSessions" color="danger">
                        {{ __('auth::profile.sessions.buttons.logout_all') }}
                    </x-button>
                </div>
            </x-card.title>

            <x-table>
                <x-table.header>
                    <x-table.header.item>
                        {{ __('auth::profile.sessions.ip_address') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('auth::profile.sessions.user_agent') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('auth::profile.sessions.platform') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('auth::profile.sessions.last_active') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('messages.tables.actions') }}
                    </x-table.header.item>
                </x-table.header>
                <x-table.body>
                    @foreach(auth()->user()->getAllSessions() as $session)
                        @php
                            $agent = new \Jenssegers\Agent\Agent();
                            $agent->setUserAgent($session->user_agent);

                            $userAgent = '<i class="icon-monitor-smartphone text-lg"></i> ' . __('auth::profile.sessions.device_types.unknown');
                            if ($agent->isDesktop()) {
                                $userAgent = '<i class="icon-monitor"></i> ' . __('auth::profile.sessions.device_types.desktop');
                            } elseif ($agent->isPhone()) {
                                $userAgent = $agent->isPhone() ? '<i class="icon-smartphone"></i> ' . __('auth::profile.sessions.device_types.phone') :
                                    '<i class="icon-tablet"></i> ' . __('auth::profile.sessions.device_types.tablet');
                            }
                        @endphp
                        <tr>
                            <x-table.body.item>
                                {{ $session->ip_address }}
                            </x-table.body.item>
                            <x-table.body.item>
                                {{ $session->user_agent }}
                            </x-table.body.item>
                            <x-table.body.item>
                                {!! $userAgent !!}
                            </x-table.body.item>
                            <x-table.body.item>
                                {{ \Illuminate\Support\Carbon::parse($session->last_active)->diffForHumans() }}
                            </x-table.body.item>
                            <x-table.body.item>
                                @if($session->id != session()->getId())
                                    <x-button wire:click="logoutSession('{{ $session->id }}')"
                                              loading="logoutSession" class="px-2 py-1" color="danger">
                                        <i class="icon-log-out"></i>
                                    </x-button>
                                @endif
                            </x-table.body.item>
                        </tr>
                    @endforeach
                </x-table.body>
            </x-table>
        </x-card>
    @endif
</div>
