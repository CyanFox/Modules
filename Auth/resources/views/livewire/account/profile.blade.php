<div>
    <script src="{{ asset('modules/auth/js/webauthn.js') }}"></script>
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
        <x-tab.item class="flex-1 flex items-center justify-center" uuid="activity"
                    wire:click="$set('tab', 'activity')">
            <i class="icon-eye"></i>
            <span class="ml-2">{{ __('auth::profile.tabs.activity') }}</span>
        </x-tab.item>
        <x-tab.item class="flex-1 flex items-center justify-center" uuid="apiKeys"
                    wire:click="$set('tab', 'apiKeys')">
            <i class="icon-key"></i>
            <span class="ml-2">{{ __('auth::profile.tabs.api_keys') }}</span>
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

                        <x-button type="submit" class="w-fit" loading="updateLanguageAndTheme">
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
                            <x-input wire:model="firstName" label="{{ __('auth::profile.profile.first_name') }}"/>
                            <x-input wire:model="lastName" label="{{ __('auth::profile.profile.last_name') }}"/>

                            <x-input wire:model="username" label="{{ __('auth::profile.profile.username') }}" required/>
                            <x-input wire:model="email" label="{{ __('auth::profile.profile.email') }}" required/>

                            <x-view-integration name="auth.profile.overview.profile"/>
                        </div>

                        <x-divider/>

                        <x-button type="submit" class="w-fit" loading="updateProfile">
                            {{ __('messages.buttons.save') }}
                        </x-button>
                    </form>
                </x-card>

                <div class="col-span-2 space-y-4">
                    <x-card>
                        <x-tab wire:model="passTab">
                            <x-tab.item class="flex-1 flex items-center justify-center" uuid="password"
                                        wire:click="$set('passTab', 'password')">
                                <i class="icon-rectangle-ellipsis"></i>
                                <span class="ml-2">{{ __('auth::profile.tabs.password') }}</span>
                            </x-tab.item>
                            <x-tab.item class="flex-1 flex items-center justify-center" uuid="passkeys"
                                        wire:click="$set('passTab', 'passkeys')">
                                <i class="icon-key-round"></i>
                                <span class="ml-2">{{ __('auth::profile.tabs.passkeys') }}</span>
                            </x-tab.item>

                            <x-view-integration name="auth.profile.tabs"/>
                        </x-tab>

                        @if($passTab == 'password')
                            <div class="mt-4">
                                <form wire:submit="updatePassword" class="space-y-4">
                                    @if(auth()->user()->password)
                                        <x-password wire:model="currentPassword" required
                                                    type="password"
                                                    label="{{ __('auth::profile.password.current_password') }}"/>
                                    @endif
                                    <div class="grid md:grid-cols-2 gap-4 mb-3">
                                        <x-password required
                                                    wire:model="newPassword"
                                                    label="{{ __('auth::profile.password.new_password') }}"/>
                                        <x-password required
                                                    wire:model="confirmPassword"
                                                    label="{{ __('auth::profile.password.confirm_password') }}"/>
                                    </div>

                                    <x-view-integration name="auth.profile.overview.password"/>

                                    <x-divider/>

                                    <x-button type="submit" class="w-fit" loading="updatePassword">
                                        {{ __('messages.buttons.save') }}
                                    </x-button>
                                </form>
                            </div>
                        @elseif($passTab == 'passkeys')
                            @livewire('auth::components.passkeys.passkeys-component')
                        @endif
                    </x-card>
                </div>
            </div>
        </div>
    @elseif($tab === 'sessions')
        <x-card class="mt-4">
            <x-card.title>
                <div class="flex items-center justify-between">
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
                                <span class="flex items-center gap-1">
                                    {!! $userAgent !!}
                                </span>
                            </x-table.body.item>
                            <x-table.body.item>
                                <span
                                    x-tooltip.raw="{{ formatDateTime($session->last_activity) }}">
                                    {{ carbon()->parse($session->last_activity ?? 0)->diffForHumans() }}
                                </span>
                            </x-table.body.item>
                            <x-table.body.item>
                                @if($session->id != session()->getId())
                                    <x-button.floating wire:click="logoutSession('{{ $session->id }}')"
                                                       loading="logoutSession" size="sm" color="danger">
                                        <i class="icon-log-out"></i>
                                    </x-button.floating>
                                @endif
                            </x-table.body.item>
                        </tr>
                    @endforeach
                </x-table.body>
            </x-table>
        </x-card>
    @elseif($tab === 'activity')
        <x-card class="mt-4">
            <x-card.title>
                {{ __('auth::profile.activity.title') }}
            </x-card.title>

            <x-table>
                <x-table.header>
                    <x-table.header.item>
                        {{ __('auth::profile.activity.description') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('auth::profile.activity.caused_by') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('auth::profile.activity.subject') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('auth::profile.activity.performed_at') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('messages.tables.actions') }}
                    </x-table.header.item>
                </x-table.header>
                <x-table.body>
                    @foreach($this->activities as $activity)
                        <tr>
                            <x-table.body.item>
                                {{ $activity->description }}
                            </x-table.body.item>
                            <x-table.body.item>
                                {{ $activity->causer ? $activity->causer->displayName() : __('auth::profile.activity.unknown_causer') }}
                            </x-table.body.item>
                            <x-table.body.item>
                                {{ $activity->subject ? $activity->subject->displayName() : __('auth::profile.activity.unknown_subject') }}
                            </x-table.body.item>
                            <x-table.body.item>
                                <span
                                    x-tooltip.raw="{{ formatDateTime($activity->created_at) }}">
                                    {{ carbon()->parse($activity->created_at ?? 0)->diffForHumans() }}
                                </span>
                            </x-table.body.item>
                            <x-table.body.item>
                                @if(filled($activity->properties))
                                    <x-button.floating
                                        wire:click="$dispatch('openModal', {component: 'auth::components.modals.activity-details', arguments: {activityLogId: {{ $activity->id }} }})"
                                        size="sm" color="info">
                                        <i class="icon-eye"></i>
                                    </x-button.floating>
                                @endif
                            </x-table.body.item>
                        </tr>
                    @endforeach
                </x-table.body>
            </x-table>
            @php
                $currentPage = $this->activities->currentPage();
                $lastPage = $this->activities->lastPage();
                $total = $this->activities->total();
                $perPage = $this->activities->perPage();
                $from = $this->activities->firstItem();
                $to = $this->activities->lastItem();
                $tab = request('tab', 'activity');
                $pageLinks = [];

                $pageLinks[] = 1;
                for ($i = max(2, $currentPage - 1); $i <= min($lastPage - 1, $currentPage + 1); $i++) {
                    $pageLinks[] = $i;
                }
                if ($lastPage > 1) {
                    $pageLinks[] = $lastPage;
                }
                $pageLinks = array_unique($pageLinks);
                sort($pageLinks);
            @endphp

            @if ($lastPage > 1)
                <div class="flex items-center justify-between mt-4">
                    <div class="text-sm text-on-surface dark:text-on-surface-dark">
                        {{ __('auth::profile.activity.pagination_text', ['first' => $from, 'last' => $to, 'total' => $total]) }}
                    </div>
                    <nav aria-label="pagination">
                        <ul class="flex shrink-0 items-center gap-2 text-sm font-medium">
                            <li>
                                <a href="{{ $this->activities->previousPageUrl() ? route('account.profile', ['page' => $currentPage - 1, 'tab' => $tab]) : '#' }}"
                                   class="flex items-center rounded-radius p-1 text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark {{ $currentPage == 1 ? 'opacity-50 pointer-events-none' : '' }}"
                                   aria-label="{{ __('auth::profile.activity.pagination_previous') }}" wire:navigate>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true" class="size-6">
                                        <path fill-rule="evenodd"
                                              d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('auth::profile.activity.pagination_previous') }}
                                </a>
                            </li>
                            @foreach ($pageLinks as $index => $page)
                                @if ($index > 0 && $pageLinks[$index] - $pageLinks[$index - 1] > 1)
                                    <li>
                                        <span
                                            class="flex size-6 items-center justify-center rounded-radius p-1 text-on-surface dark:text-on-surface-dark">...</span>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('account.profile', ['page' => $page, 'tab' => $tab]) }}"
                                       class="flex size-6 items-center justify-center rounded-radius p-1 {{ $currentPage == $page ? 'bg-primary font-bold text-on-primary dark:bg-primary-dark dark:text-on-primary-dark' : 'text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark' }}"
                                       aria-label="page {{ $page }}" wire:navigate
                                       @if($currentPage == $page) aria-current="page" @endif>
                                        {{ $page }}
                                    </a>
                                </li>
                            @endforeach
                            <li>
                                <a href="{{ $this->activities->nextPageUrl() ? route('account.profile', ['page' => $currentPage + 1, 'tab' => $tab]) : '#' }}"
                                   class="flex items-center rounded-radius p-1 text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark {{ $currentPage == $lastPage ? 'opacity-50 pointer-events-none' : '' }}"
                                   aria-label="{{ __('auth::profile.activity.pagination_next') }}" wire:navigate>
                                    {{ __('auth::profile.activity.pagination_next') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true" class="size-6">
                                        <path fill-rule="evenodd"
                                              d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            @endif
        </x-card>
    @elseif($tab === 'apiKeys')
        <x-card class="mt-4">
            <x-card.title>
                <div class="flex items-center justify-between">
                    <p>{{ __('auth::profile.api_keys.title') }}</p>
                    <div>
                        <x-button
                            link="/docs/api" target="_blank">
                            {{ __('auth::profile.api_keys.buttons.api_docs') }}
                        </x-button>
                        <x-button
                            wire:click="$dispatch('openModal', {component: 'auth::components.modals.create-api-key'})">
                            {{ __('auth::profile.api_keys.buttons.create_api_key') }}
                        </x-button>
                    </div>
                </div>
            </x-card.title>

            <x-table>
                <x-table.header>
                    <x-table.header.item>
                        {{ __('auth::profile.api_keys.name') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('auth::profile.api_keys.permissions') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('auth::profile.api_keys.last_used') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('messages.tables.created_at') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('messages.tables.actions') }}
                    </x-table.header.item>
                </x-table.header>
                <x-table.body>
                    @foreach(auth()->user()->apiKeys()->with('permissions.permission')->where('connected_device', false)->get() as $apiKey)
                        <tr>
                            <x-table.body.item>
                                {{ $apiKey->name }}
                            </x-table.body.item>
                            <x-table.body.item>
                                <span x-data
                                      x-tooltip.raw="{{ $apiKey->permissions->pluck('permission.name')->implode(', ') }}">
                                    {{ str()->limit($apiKey->permissions->pluck('permission.name')->implode(', '), 100, preserveWords: true) }}
                                </span>
                            </x-table.body.item>
                            <x-table.body.item>
                                <span
                                    x-tooltip.raw="{{ formatDateTime($apiKey->last_used) }}">
                                    {{ $apiKey->last_used ? carbon()->parse($apiKey->last_used)->diffForHumans() : __('auth::profile.api_keys.never_used') }}
                                </span>
                            </x-table.body.item>
                            <x-table.body.item>
                                {{ formatDateTime($apiKey->created_at) }}
                            </x-table.body.item>
                            <x-table.body.item>
                                <x-button.floating wire:click="deleteApiKey('{{ $apiKey->id }}', false)"
                                                   loading="deleteApiKey" size="sm" color="danger">
                                    <i class="icon-trash"></i>
                                </x-button.floating>
                            </x-table.body.item>
                        </tr>
                    @endforeach
                </x-table.body>
            </x-table>
        </x-card>
    @endif
</div>
