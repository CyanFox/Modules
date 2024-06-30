<div>
    <x-tab wire:model.live="tab">
        <x-tab.items :tab="__('authmodule::settings.tabs.auth')">
            <x-slot:left>
                <i class="icon-key-square"></i>
            </x-slot:left>
        </x-tab.items>

        <x-tab.items :tab="__('authmodule::settings.tabs.emails')">
            <x-slot:left>
                <i class="icon-mail"></i>
            </x-slot:left>
        </x-tab.items>

        <x-tab.items :tab="__('authmodule::settings.tabs.account')">
            <x-slot:left>
                <i class="icon-user"></i>
            </x-slot:left>
        </x-tab.items>

        <x-view-integration name="authmodule.settings.tabs"/>
    </x-tab>

    <div class="mt-4">

        <x-view-integration name="authmodule.settings.header"/>

        @if($tab == __('authmodule::settings.tabs.auth'))
            <x-card>
                <form wire:submit="updateAuthSettings">
                    @csrf

                    <x-view-integration name="authmodule.settings.auth.header"/>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-5">
                        <x-select.styled label="{{ __('authmodule::settings.enable.captcha') }} *" :options="[
                            ['label' => __('messages.yes'), 'value' => '1'],
                            ['label' => __('messages.no'), 'value' => '0']]"
                                         select="label:label|value:value" wire:model="enableCaptcha" searchable/>

                        <x-select.styled label="{{ __('authmodule::settings.enable.register') }} *" :options="[
                            ['label' => __('messages.yes'), 'value' => '1'],
                            ['label' => __('messages.no'), 'value' => '0']]"
                                         select="label:label|value:value" wire:model="enableRegister" searchable/>

                        <x-select.styled label="{{ __('authmodule::settings.enable.forgot_password') }} *" :options="[
                            ['label' => __('messages.yes'), 'value' => '1'],
                            ['label' => __('messages.no'), 'value' => '0']]"
                                         select="label:label|value:value" wire:model="enableForgotPassword" searchable/>
                    </div>

                    <x-divider/>

                    <div class="my-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-number label="{{ __('authmodule::settings.security.password.min_length') }} *"
                                      wire:model="passwordMinLength" min="1" max="200" required/>

                            <x-input label="{{ __('authmodule::settings.security.password.blacklist') }}"
                                     :hint="__('authmodule::settings.security.password.blacklist_hint')"
                                     wire:model="passwordBlacklist"/>
                        </div>
                        <x-checkbox label="{{ __('authmodule::settings.security.password.require_number') }}"
                                    wire:model="passwordRequireNumber"/>
                        <x-checkbox
                            label="{{ __('authmodule::settings.security.password.require_special_characters') }}"
                            wire:model="passwordRequireSpecialCharacter"/>
                        <x-checkbox label="{{ __('authmodule::settings.security.password.require_uppercase') }}"
                                    wire:model="passwordRequireUppercase"/>
                        <x-checkbox label="{{ __('authmodule::settings.security.password.require_lowercase') }}"
                                    wire:model="passwordRequireLowercase"/>
                    </div>

                    <x-view-integration name="authmodule.settings.auth.form"/>

                    @can('adminmodule.settings.update')
                        <x-divider/>

                        <div class="space-x-1 mt-3">
                            <x-button type="submit" loading="updateAuthSettings">
                                {{ __('authmodule::settings.buttons.update_settings') }}
                            </x-button>

                            <x-view-integration name="authmodule.settings.auth.buttons"/>
                        </div>
                    @endcan


                    <x-view-integration name="authmodule.settings.auth.footer"/>
                </form>
            </x-card>
        @endif

        @if($tab == __('authmodule::settings.tabs.emails'))
            <x-card>
                <form wire:submit="updateEmailSettings">
                    @csrf

                    <x-view-integration name="authmodule.settings.emails.header"/>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <x-input label="{{ __('authmodule::settings.emails.forgot_password.title') }} *"
                                 :hint="__('authmodule::settings.emails.forgot_password.placeholders')"
                                 wire:model="forgotPasswordEmailTitle"/>

                        <x-input label="{{ __('authmodule::settings.emails.forgot_password.subject') }} *"
                                 :hint="__('authmodule::settings.emails.forgot_password.placeholders')"
                                 wire:model="forgotPasswordEmailSubject"/>
                    </div>

                    <div class="mb-4">
                        <x-textarea label="{{ __('authmodule::settings.emails.forgot_password.content') }} *"
                                    :hint="__('authmodule::settings.emails.forgot_password.placeholders')"
                                    wire:model="forgotPasswordEmailContent" resize-auto/>
                    </div>

                    <x-view-integration name="authmodule.settings.emails.form"/>

                    @can('adminmodule.settings.update')
                        <x-divider/>

                        <div class="space-x-1 mt-3">
                            <x-button type="submit" loading="updateEmailSettings">
                                {{ __('authmodule::settings.buttons.update_settings') }}
                            </x-button>

                            <x-view-integration name="authmodule.settings.emails.buttons"/>
                        </div>
                    @endcan

                    <x-view-integration name="authmodule.settings.emails.footer"/>
                </form>
            </x-card>
        @endif

        @if($tab == __('authmodule::settings.tabs.account'))
            <x-card>
                <form wire:submit="updateAccountSettings">
                    @csrf

                    <x-view-integration name="authmodule.settings.account.header"/>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-5">
                        <x-input label="{{ __('authmodule::settings.account.default_avatar_url') }} *"
                                 :hint="__('authmodule::settings.account.default_avatar_url_placeholder')"
                                 wire:model="defaultAvatarUrl"/>

                        <x-select.styled label="{{ __('authmodule::settings.account.allow.change_avatar') }} *"
                                         :options="[
                                            ['label' => __('messages.yes'), 'value' => '1'],
                                            ['label' => __('messages.no'), 'value' => '0']]"
                                         select="label:label|value:value" wire:model="allowChangeAvatar" searchable/>

                        <x-select.styled label="{{ __('authmodule::settings.account.allow.delete_account') }} *"
                                         :options="[
                                            ['label' => __('messages.yes'), 'value' => '1'],
                                            ['label' => __('messages.no'), 'value' => '0']]"
                                         select="label:label|value:value" wire:model="allowDeleteAccount" searchable/>


                    </div>

                    <x-view-integration name="authmodule.settings.account.form"/>

                    @can('adminmodule.settings.update')
                        <x-divider/>

                        <div class="space-x-1 mt-3">
                            <x-button type="submit" loading="updateAccountSettings">
                                {{ __('authmodule::settings.buttons.update_settings') }}
                            </x-button>

                            <x-view-integration name="authmodule.settings.account.buttons"/>
                        </div>
                    @endcan

                    <x-view-integration name="authmodule.settings.account.footer"/>
                </form>
            </x-card>
        @endif
    </div>
</div>
