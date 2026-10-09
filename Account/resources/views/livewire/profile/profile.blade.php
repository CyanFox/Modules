<div class="space-y-4">
    @persist('account.tabs.profile')
    <x-account::profile-tabs selected-tab="profile"/>
    @endpersist
    <div class="space-y-4" wire:transition>
        <div class="grid md:grid-cols-3 gap-4">
            <div class="space-y-4">
                <x-card>
                    @hook('account.profile.avatar')
                    <div class="flex items-center gap-4">
                        @if(settings('account.enable.change_avatar'))
                            <div class="size-14 relative group">
                                <img src="{{ auth()->user()->getAvatar() }}" alt="Avatar"
                                     class="absolute inset-0 bg-cover bg-center z-0 rounded-full group-hover:opacity-70 transition-opacity duration-300 size-14">
                                <div
                                    wire:click="$dispatch('openModal', {modalComponent: 'account::components.modals.change-avatar'})"
                                    class="opacity-0 group-hover:opacity-100 hover:cursor-pointer duration-300 absolute inset-0 z-10 flex justify-center items-center text-xl text-white font-semibold">
                                    <i class="icon-upload"></i></div>
                            </div>
                        @else
                            <img src="{{ auth()->user()->getAvatar() }}" alt="Avatar"
                                 class="rounded-full size-14">
                        @endif
                        <div class="flex flex-col">
                            <span>{{ auth()->user()->getDisplayName() }}</span>
                            <span>{{ auth()->user()->username }}</span>
                        </div>
                    </div>
                    @endhook
                </x-card>

                <x-card>
                    @hook('account.profile.lang_theme')
                    <div class="space-y-4">
                        <x-select wire:model="language" wire:change="updateLanguage"
                                  :label="__('account::account.language')">
                            <option value="en">{{ __('account::account.languages.en') }}</option>
                            <option value="de">{{ __('account::account.languages.de') }}</option>

                            @shook('s.global.languages')
                        </x-select>

                        <x-select wire:model="theme" wire:change="updateTheme" :label="__('account::account.theme')">
                            <option value="light">{{ __('account::account.themes.light') }}</option>
                            <option value="dark">{{ __('account::account.themes.dark') }}</option>

                            @shook('s.account.profile.lang_theme.themes')
                        </x-select>
                    </div>
                    @endhook
                </x-card>

                <x-card>
                    <div class="flex flex-wrap gap-2">
                        @hook('account.profile.actions')
                        @if(settings('account.enable.delete_account'))
                            <x-button color="danger" class="w-full" wire:click="deleteAccount" loading="deleteAccount">
                                {{ __('account::account.buttons.delete_account') }}
                            </x-button>
                        @endif
                        @if(userSettings('auth.mfa.enabled'))
                            <x-button color="warning" class="w-full" wire:click="disableMfa" loading="disableMfa">
                                {{ __('account::account.buttons.disable_mfa') }}
                            </x-button>
                            <x-button class="w-full" wire:click="regenerateRecoveryCodes"
                                      loading="regenerateRecoveryCodes">
                                {{ __('account::account.buttons.regenerate_recovery_codes') }}
                            </x-button>
                        @else
                            <x-button color="success" class="w-full" wire:click="enableMfa" loading="enableMfa">
                                {{ __('account::account.buttons.enable_mfa') }}
                            </x-button>
                        @endif

                        @shook('s.account.profile.actions')
                        @endhook
                    </div>
                </x-card>
            </div>
            <div class="md:col-span-2 space-y-4">
                <x-card>
                    @hook('account.profile.form')
                    <form wire:submit="updateProfile">
                        <div class="grid md:grid-cols-2 gap-4">
                            <x-input wire:model="firstName" :label="__('account::account.first_name')"/>
                            <x-input wire:model="lastName" :label="__('account::account.last_name')"/>

                            <x-input wire:model="username" :label="__('account::account.username')" required/>
                            <x-input wire:model="email" type="email" :label="__('account::account.email')" required/>
                        </div>

                        <x-divider/>

                        <x-button type="submit" loading="updateProfile" class="md:w-fit">
                            {{ __('account::account.buttons.update_profile') }}
                        </x-button>
                    </form>
                    @endhook
                </x-card>

                <x-card>
                    <x-tab wire:model="tab">
                        @hook('account.password.tabs')
                        <x-tab.item class="flex-1 flex items-center justify-center" uuid="password"
                                    wire:click="$set('tab', 'password')">
                            <i class="icon-square-asterisk"></i>
                            <span class="ml-2">{{ __('account::account.tabs.password') }}</span>
                        </x-tab.item>
                        <x-tab.item class="flex-1 flex items-center justify-center" uuid="passkeys"
                                    wire:click="$set('tab', 'passkeys')">
                            <i class="icon-key-round"></i>
                            <span class="ml-2">{{ __('account::account.tabs.passkeys') }}</span>
                        </x-tab.item>

                        @shook('s.account.password.tabs')
                        @endhook
                    </x-tab>


                    @if($tab == 'password')
                        @hook('account.profile.password')
                        <form wire:submit="changePassword" class="space-y-4 mt-4">
                            <x-password wire:model="currentPassword" :label="__('account::account.current_password')"
                                        required/>

                            <div class="grid md:grid-cols-2 gap-4">
                                <x-password wire:model="newPassword" :label="__('account::account.new_password')"
                                            required/>
                                <x-password wire:model="confirmNewPassword"
                                            :label="__('account::account.confirm_new_password')" required/>
                            </div>

                            <x-divider/>

                            <x-button type="submit" loading="changePassword" class="md:w-fit">
                                {{ __('account::account.buttons.change_password') }}
                            </x-button>
                        </form>
                        @endhook
                    @elseif($tab == 'passkeys')
                        @hook('account.profile.passkeys')
                        @livewire('account::components.passkeys')
                        @endhook
                    @endif
                </x-card>
            </div>
        </div>
    </div>
</div>
