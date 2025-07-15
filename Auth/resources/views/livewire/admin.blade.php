<div>
    <div class="mb-4">
        <x-tab wire:model="tab">
            <x-tab.item class="flex-1 flex items-center justify-center" uuid="general"
                        wire:click="$set('tab', 'general')">
                <i class="icon-house"></i>
                <span class="ml-2">{{ __('auth::admin.tabs.general') }}</span>
            </x-tab.item>

            <x-tab.item class="flex-1 flex items-center justify-center" uuid="mail"
                        wire:click="$set('tab', 'mail')">
                <i class="icon-mail"></i>
                <span class="ml-2">{{ __('auth::admin.tabs.mail') }}</span>
            </x-tab.item>

            <x-tab.item class="flex-1 flex items-center justify-center" uuid="oauth"
                        wire:click="$set('tab', 'oauth')">
                <i class="icon-fingerprint"></i>
                <span class="ml-2">{{ __('auth::admin.tabs.oauth') }}</span>
            </x-tab.item>

            <x-tab.item class="flex-1 flex items-center justify-center" uuid="password"
                        wire:click="$set('tab', 'password')">
                <i class="icon-key-round"></i>
                <span class="ml-2">{{ __('auth::admin.tabs.password') }}</span>
            </x-tab.item>

            <x-view-integration name="admin.settings.tabs"/>
        </x-tab>
    </div>

    @if($tab == 'general')
        <x-cf.card view-integration="auth.settings.general">
            <form wire:submit="updateGeneralSettings" class="gap-4 space-y-4">
                <x-input wire:model="defaultAvatarUrl" label="{{ __('auth::admin.default_avatar_url') }}"
                         hint="{{ __('auth::admin.default_avatar_url_hint') }}" required/>

                <div class="grid md:grid-cols-2 gap-4">
                    <x-input wire:model="loginRateLimit" label="{{ __('auth::admin.login_rate_limit') }}" type="number"
                             required/>
                    <x-input wire:model="registerRateLimit" label="{{ __('auth::admin.register_rate_limit') }}"
                             type="number" required/>
                </div>

                <x-divider/>

                <div class="grid md:grid-cols-2 gap-4">
                    <x-input wire:model="unsplashApiKey" label="{{ __('auth::admin.unsplash_api_key') }}"/>
                    <x-input wire:model="unsplashUtm" label="{{ __('auth::admin.unsplash_utm') }}"/>
                    <x-input wire:model="unsplashFallbackCss" label="{{ __('auth::admin.unsplash_fallback_css') }}"/>
                    <x-input wire:model="unsplashQuery" label="{{ __('auth::admin.unsplash_query') }}"/>
                </div>

                <x-divider/>

                <div class="grid md:grid-cols-2 gap-4">
                    <x-checkbox wire:model="enableDeleteAccount" label="{{ __('auth::admin.enable_delete_account') }}"/>
                    <x-checkbox wire:model="enableChangeAvatar" label="{{ __('auth::admin.enable_change_avatar') }}"/>
                    <x-checkbox wire:model="enableRegister" label="{{ __('auth::admin.enable_register') }}"/>
                    <x-checkbox wire:model="enableForgotPassword"
                                label="{{ __('auth::admin.enable_forgot_password') }}"/>
                    <x-checkbox wire:model="enableLogin" label="{{ __('auth::admin.enable_login') }}"/>
                    <x-checkbox wire:model="enableLoginCaptcha" label="{{ __('auth::admin.enable_login_captcha') }}"/>
                    <x-checkbox wire:model="enableRegisterCaptcha"
                                label="{{ __('auth::admin.enable_register_captcha') }}"/>
                    <x-checkbox wire:model="enableForgotPasswordCaptcha"
                                label="{{ __('auth::admin.enable_forgot_password_captcha') }}"/>
                </div>

                @can('admin.settings.update')
                    <x-cf.buttons.update :show-cancel="false" :update-text="__('messages.buttons.save')"
                                         target="updateGeneralSettings" class="mt-0"/>
                @endcan
            </form>
        </x-cf.card>
    @elseif($tab == 'mail')
        <x-cf.card view-integration="auth.settings.mail">
            <form wire:submit="updateMailSettings" class="gap-4 space-y-4">

                <div class="mb-4 space-y-4">
                    <span class="text-lg font-semibold mb-4">
                        {{ __('auth::admin.forgot_password_mail_title') }}
                    </span>
                    <x-divider/>
                    <div class="grid md:grid-cols-2 gap-4">
                        <x-input wire:model="forgotPasswordMailTitle"
                                 label="{{ __('auth::admin.forgot_password_mail.title') }}"
                                 hint="{{ __('auth::admin.forgot_password_mail.hint') }}" required/>
                        <x-input wire:model="forgotPasswordMailSubject"
                                 label="{{ __('auth::admin.forgot_password_mail.subject') }}"
                                 hint="{{ __('auth::admin.forgot_password_mail.hint') }}" required/>
                    </div>

                    <x-textarea wire:model="forgotPasswordMailContent"
                                label="{{ __('auth::admin.forgot_password_mail.content') }}"
                                hint="{{ __('auth::admin.forgot_password_mail.hint') }}" required/>
                </div>

                <div class="mb-4 space-y-4">
                    <span class="text-lg font-semibold mb-4">
                        {{ __('auth::admin.new_session_mail_title') }}
                    </span>
                    <x-divider/>
                    <div class="grid md:grid-cols-2 gap-4">
                        <x-input wire:model="newSessionMailTitle"
                                 label="{{ __('auth::admin.new_session_mail.title') }}"
                                 hint="{{ __('auth::admin.new_session_mail.hint') }}" required/>
                        <x-input wire:model="newSessionMailSubject"
                                 label="{{ __('auth::admin.new_session_mail.subject') }}"
                                 hint="{{ __('auth::admin.new_session_mail.hint') }}" required/>
                    </div>

                    <x-textarea wire:model="newSessionMailContent"
                                label="{{ __('auth::admin.new_session_mail.content') }}"
                                hint="{{ __('auth::admin.new_session_mail.hint') }}" required/>
                </div>

                @can('admin.settings.update')
                    <x-cf.buttons.update :show-cancel="false" :update-text="__('messages.buttons.save')"
                                         target="updateMailSettings" class="mt-0"/>
                @endcan
            </form>
        </x-cf.card>
    @elseif($tab == 'oauth')
        <x-cf.card view-integration="auth.settings.oauth">
            <form wire:submit="updateOAuthSettings" class="gap-4 space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <x-select :label="__('auth::admin.enable_oauth')"
                              wire:model="oauthLoginEnabled" required>
                        <option value="1">{{ __('messages.yes') }}</option>
                        <option value="0">{{ __('messages.no') }}</option>
                    </x-select>
                    <x-input wire:model="oauthWellKnownUrl" label="{{ __('auth::admin.oauth_well_known_url') }}"
                             required/>

                    <x-select :label="__('auth::admin.oauth_login_color')"
                              wire:model="oauthLoginColor" required>
                        <option value="primary">{{ __('auth::admin.oauth_colors.primary') }}</option>
                        <option value="secondary">{{ __('auth::admin.oauth_colors.secondary') }}</option>
                        <option value="inverse">{{ __('auth::admin.oauth_colors.inverse') }}</option>
                        <option value="info">{{ __('auth::admin.oauth_colors.info') }}</option>
                        <option value="warning">{{ __('auth::admin.oauth_colors.warning') }}</option>
                        <option value="danger">{{ __('auth::admin.oauth_colors.danger') }}</option>
                        <option value="success">{{ __('auth::admin.oauth_colors.success') }}</option>
                    </x-select>
                    <x-input wire:model="oauthLoginText" label="{{ __('auth::admin.oauth_login_text') }}"
                             required/>
                </div>

                <x-divider/>

                <div class="grid md:grid-cols-3 gap-4">
                    <x-input wire:model="oauthIdField" label="{{ __('auth::admin.oauth_id_field') }}"
                             required/>
                    <x-input wire:model="oauthUsernameField" label="{{ __('auth::admin.oauth_username_field') }}"
                             required/>
                    <x-input wire:model="oauthEmailField" label="{{ __('auth::admin.oauth_email_field') }}"
                             required/>

                    <x-input wire:model="oauthClientId" label="{{ __('auth::admin.oauth_client_id') }}"
                             required/>
                    <x-input wire:model="oauthClientSecret" label="{{ __('auth::admin.oauth_client_secret') }}"
                             required/>
                    <x-input wire:model="oauthRedirectUri" label="{{ __('auth::admin.oauth_redirect_uri') }}"
                             required/>
                </div>

                @can('admin.settings.update')
                    <x-cf.buttons.update :show-cancel="false" :update-text="__('messages.buttons.save')"
                                         target="updateOAuthSettings" class="mt-0"/>
                @endcan
            </form>
        </x-cf.card>
    @elseif($tab == 'password')
        <x-cf.card view-integration="auth.settings.password">
            <form wire:submit="updatePasswordSettings" class="gap-4 space-y-4">
                <div class="grid lg:grid-cols-2 gap-4">
                    <x-input wire:model="passwordMinLength" label="{{ __('auth::admin.password_min_length') }}"
                             type="number" required/>
                    <x-input wire:model="passwordBlacklist" label="{{ __('auth::admin.password_blacklist') }}"/>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <x-checkbox wire:model="passwordRequireUppercase" label="{{ __('auth::admin.password_require_uppercase') }}"/>
                    <x-checkbox wire:model="passwordRequireLowercase" label="{{ __('auth::admin.password_require_lowercase') }}"/>
                    <x-checkbox wire:model="passwordRequireNumbers" label="{{ __('auth::admin.password_require_numbers') }}"/>
                    <x-checkbox wire:model="passwordRequireSpecialCharacters" label="{{ __('auth::admin.password_require_special_characters') }}"/>
                </div>

                @can('admin.settings.update')
                    <x-cf.buttons.update :show-cancel="false" :update-text="__('messages.buttons.save')"
                                         target="updatePasswordSettings" class="mt-0"/>
                @endcan
            </form>
        </x-cf.card>
    @endif
</div>
