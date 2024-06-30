<div>
    <x-tab wire:model.live="tab">
        <x-tab.items :tab="__('authmodule::account.tabs.overview')">
            <x-slot:left>
                <i class="icon-home"></i>
            </x-slot:left>
        </x-tab.items>
        <x-tab.items :tab="__('authmodule::account.tabs.sessions')">
            <x-slot:left>
                <i class="icon-monitor-dot"></i>
            </x-slot:left>
        </x-tab.items>

        <x-view-integration name="authmodule.profile.tabs"/>
    </x-tab>

    @if($tab == __('authmodule::account.tabs.overview'))
        <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-4 mt-4">
            <div class="col-span-1 space-y-4">
                <x-card>
                    <div class="flex">
                        @if(setting('authmodule.enable.change_avatar'))
                            <div class="h-16 w-16 relative mr-4 group">
                                <div
                                    class="absolute inset-0 bg-cover bg-center z-0 rounded-3xl group-hover:opacity-70 transition-opacity duration-300"
                                    style="background-image: url('{{ user()->getUser(auth()->user())->getAvatarURL() }}')"></div>
                                <div
                                    wire:click="$toggle('showChangeAvatarModal')"
                                    class="opacity-0 group-hover:opacity-100 hover:cursor-pointer duration-300 absolute inset-0 z-10 flex justify-center items-center text-3xl text-white font-semibold">
                                    <i class="icon-upload"></i></div>
                            </div>
                            <x-authmodule::modals.change-avatar :avatarUrl="$avatarUrl" :avatarFile="$avatarFile"/>

                            <x-view-integration name="authmodule.profile.overview.change_avatar"/>
                        @else
                            <img src="{{ user()->getUser(auth()->user())->getAvatarURL() }}"
                                 alt="Avatar" class="h-16 w-16 rounded-3xl mr-4">

                            <x-view-integration name="authmodule.profile.overview.avatar"/>
                        @endif
                        <div>
                            <p class="font-bold">{{ auth()->user()->username }}</p>
                            <p>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>

                            <x-view-integration name="authmodule.profile.overview.username"/>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <span
                            class="font-bold text-xl">{{ __('authmodule::account.overview.language_and_theme.title') }}</span>

                        <x-view-integration name="authmodule.profile.overview.language_and_theme.title"/>
                    </x-slot:header>

                    <x-select.styled :options="[
                            ['label' => __('authmodule::account.overview.language_and_theme.languages.en'), 'value' => 'en'],
                            ['label' => __('authmodule::account.overview.language_and_theme.languages.de'), 'value' => 'de'],
                        ]" select="label:label|value:value" wire:model="language" searchable/>

                    <div class="my-4">
                        <x-select.styled :options="[
                                ['label' => __('authmodule::account.overview.language_and_theme.themes.dark'), 'value' => 'dark'],
                                ['label' => __('authmodule::account.overview.language_and_theme.themes.light'), 'value' => 'light'],
                            ]" select="label:label|value:value" wire:model="theme" searchable/>
                    </div>

                    <x-view-integration name="authmodule.profile.overview.language_and_theme.form"/>

                    <x-divider/>


                    <div class="space-x-1 mt-3">
                        <x-button class="mt-3" wire:click="updateLanguageAndTheme" loading="updateLanguageAndTheme">
                            {{ __('authmodule::account.overview.language_and_theme.buttons.update_language_and_theme') }}
                        </x-button>

                        <x-view-integration name="authmodule.profile.overview.language_and_theme.buttons"/>
                    </div>

                    <x-view-integration name="authmodule.profile.overview.language_and_theme.footer"/>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <span
                            class="font-bold text-xl">{{ __('authmodule::account.overview.actions.title') }}</span>

                        <x-view-integration name="authmodule.profile.overview.actions.title"/>
                    </x-slot:header>

                    <x-authmodule::modals.activate-two-factor/>
                    <x-authmodule::modals.show-recovery-codes/>

                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-2">
                        @if(auth()->user()->two_factor_enabled)
                            <x-button wire:click="$toggle('showRecoveryCodesModal')">
                                {{ __('authmodule::account.overview.actions.buttons.show_recovery_codes') }}
                            </x-button>
                            <x-button color="red" wire:click="disableTwoFactor">
                                {{ __('authmodule::account.overview.actions.buttons.disable_two_factor') }}
                            </x-button>

                            <x-view-integration name="authmodule.profile.overview.actions.two_factor_enabled"/>
                        @elseif(auth()->user()->password !== null)
                            <x-button wire:click="$toggle('activateTwoFactorModal')" color="green">
                                {{ __('authmodule::account.overview.actions.buttons.activate_two_factor') }}
                            </x-button>

                            <x-view-integration name="authmodule.profile.overview.actions.two_factor_disabled"/>
                        @endif

                        @if(setting('authmodule.enable.delete_account'))
                            <x-button color="red" wire:click="deleteAccount">
                                {{ __('authmodule::account.overview.actions.buttons.delete_account') }}
                            </x-button>

                            <x-view-integration name="authmodule.profile.overview.actions.delete_account"/>
                        @endif

                        <x-view-integration name="authmodule.profile.overview.actions"/>

                        <x-view-integration name="authmodule.profile.overview.footer"/>
                    </div>
                </x-card>
            </div>

            <div class="col-span-2 space-y-4 lg:mt-0 mt-4">
                <x-card>
                    <x-slot:header>
                        <span
                            class="font-bold text-xl">{{ __('authmodule::account.overview.profile.title') }}</span>

                        <x-view-integration name="authmodule.profile.overview.account.title"/>
                    </x-slot:header>

                    <form wire:submit="updateProfile">
                        @csrf
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-4 mb-3">
                            <x-input label="{{ __('authmodule::messages.first_name') }} *"
                                     wire:model="firstName"/>
                            <x-input label="{{ __('authmodule::messages.last_name') }} *"
                                     wire:model="lastName"/>

                            <x-input label="{{ __('authmodule::messages.username') }} *"
                                     wire:model="username"/>
                            <x-input label="{{ __('authmodule::messages.email') }} *"
                                     wire:model="email"/>
                        </div>

                        <x-view-integration name="authmodule.profile.overview.account.form"/>

                        <x-divider/>


                        <div class="space-x-1 mt-3">
                            <x-button class="mt-3" loading="updateProfile" type="submit">
                                {{ __('authmodule::account.overview.profile.buttons.update_profile') }}
                            </x-button>

                            <x-view-integration name="authmodule.profile.overview.account.buttons"/>
                        </div>

                        <x-view-integration name="authmodule.profile.overview.account.footer"/>
                    </form>
                </x-card>

                <div class="col-span-2 space-y-4">
                    <x-card>
                        <x-slot:header>
                        <span
                            class="font-bold text-xl">{{ __('authmodule::account.overview.password.title') }}</span>

                            <x-view-integration name="authmodule.profile.overview.password.title"/>
                        </x-slot:header>

                        <form wire:submit="updatePassword">
                            @csrf

                            <div class="mb-3">
                                @if(auth()->user()->password !== null)
                                    <x-password
                                        label="{{ __('authmodule::account.overview.password.current_password') }} *"
                                        wire:model="currentPassword"/>
                                @endif
                                <div
                                    class="grid md:grid-cols-2 grid-cols-1 gap-4 @if(auth()->user()->password !== null) mt-4 @endif">
                                    <x-password label="{{ __('authmodule::messages.new_password') }} *"
                                                wire:model="newPassword"/>
                                    <x-password
                                        label="{{ __('authmodule::account.overview.password.new_password_confirmation') }} *"
                                        wire:model="newPasswordConfirmation"/>
                                </div>

                                <x-view-integration name="authmodule.profile.overview.password.form"/>
                            </div>

                            <x-divider/>


                            <div class="space-x-1 mt-3">
                                <x-button class="mt-3" loading="updatePassword" type="submit">
                                    {{ __('authmodule::account.overview.password.buttons.update_password') }}
                                </x-button>

                                <x-view-integration name="authmodule.profile.overview.password.buttons"/>
                            </div>


                            <x-view-integration name="authmodule.profile.overview.password.footer"/>
                        </form>
                    </x-card>
                </div>
            </div>
        </div>
    @endif

    @if($tab === __('authmodule::account.tabs.sessions'))
        <div class="mt-4">
            <x-card>
                <x-slot:header>
                        <span
                            class="font-bold text-xl">{{ __('authmodule::account.sessions.title') }}</span>

                    <x-view-integration name="authmodule.profile.sessions.title"/>
                </x-slot:header>

                <x-view-integration name="authmodule.profile.sessions.header"/>

                @livewire('authmodule::components.tables.sessions-table')

                <x-view-integration name="authmodule.profile.sessions.footer"/>
            </x-card>
        </div>
    @endif
</div>
