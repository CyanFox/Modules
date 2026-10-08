<div class="space-y-4">
    @persist('account.tabs.sessions')
    <x-account::profile-tabs selected-tab="sessions"/>
    @endpersist
    <div class="overflow-x-auto space-y-4" wire:transition>
        <x-cf.card :title="__('account::sessions.web_sessions')" hook="account.sessions.web_sessions">
            <x-table>
                <x-table.header>
                    <x-table.header.item>
                        {{ __('account::sessions.ip_address') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('account::sessions.user_agent') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('account::sessions.platform') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('account::sessions.last_activity') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('messages.tables.actions') }}
                    </x-table.header.item>
                </x-table.header>
                <x-table.body>
                    @foreach(auth()->user()->sessions()->get() as $session)
                        @php
                            $agent = new \Jenssegers\Agent\Agent();
                            $agent->setUserAgent($session->user_agent);

                            $userAgent = '<i class="icon-monitor-smartphone text-lg"></i> ' . __('account::sessions.platform_types.unknown');
                            if ($agent->isDesktop()) {
                                $userAgent = '<i class="icon-monitor"></i> ' . __('account::sessions.platform_types.desktop');
                            } else if ($agent->isPhone()) {
                                $userAgent = $agent->isPhone() ? '<i class="icon-smartphone"></i> ' . __('account::sessions.platform_types.phone') :
                                    '<i class="icon-tablet"></i> ' . __('account::sessions.platform_types.tablet');
                            }
                        @endphp
                        <x-table.body.row>
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
                                <span x-data
                                      x-tooltip.raw="{{ formatDateTime($session->last_activity) }}">
                                    {{ carbon()->parse($session->last_activity ?? 0)->diffForHumans() }}
                                </span>
                            </x-table.body.item>
                            <x-table.body.item>
                                @if($session->id != session()->getId())
                                    <x-button.floating wire:click="logoutSession('{{ $session->id }}')"
                                                       :tooltip="__('account::sessions.tooltips.logout')"
                                                       loading="logoutSession" size="sm" color="danger">
                                        <i class="icon-log-out"></i>
                                    </x-button.floating>
                                @else
                                    <x-badge color="success">
                                        {{ __('account::sessions.your_session') }}
                                    </x-badge>
                                @endif
                            </x-table.body.item>
                        </x-table.body.row>
                    @endforeach
                </x-table.body>
            </x-table>
        </x-cf.card>
        <x-cf.card hook="account.sessions.app_sessions">
            <x-slot:title>
                <div class="flex justify-between">
                    <span>{{ __('account::sessions.app_sessions') }}</span>
                    <x-button.floating size="sm" :tooltip="__('account::sessions.tooltips.qrcode')"
                                       wire:click="showLoginQrCode" loading="showLoginQrCode">
                        <i class="icon-qr-code"></i>
                    </x-button.floating>
                </div>
            </x-slot:title>

            <x-table>
                <x-table.header>
                    <x-table.header.item>
                        {{ __('account::sessions.device') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('account::sessions.platform') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('account::sessions.last_activity') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('messages.tables.created_at') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('messages.tables.actions') }}
                    </x-table.header.item>
                </x-table.header>
                <x-table.body>
                    @foreach(auth()->user()->tokens as $token)
                        @php
                            $properties = json_decode($token->name);

                            if (data_get($properties, 'type') == 'api') {
                                continue;
                            }
                        @endphp
                        <x-table.body.row>
                            <x-table.body.item>
                                <span x-data x-tooltip.raw="{{ data_get($properties, 'uuid') }}">
                                    {{ data_get($properties, 'name') }}
                                </span>
                            </x-table.body.item>
                            <x-table.body.item>
                                <span class="flex items-center gap-1">
                                    {!! __('account::sessions.device_types.'.e(data_get($properties, 'platform'))) !!}
                                </span>
                            </x-table.body.item>
                            <x-table.body.item>
                                <x-core::human-date :date="$token->last_used_at"
                                                    :fallback="__('account::sessions.never_used')"/>
                            </x-table.body.item>
                            <x-table.body.item>
                                <x-core::human-date :date="$token->created_at"/>
                            </x-table.body.item>
                            <x-table.body.item>
                                <x-button.floating size="sm" color="danger"
                                                   wire:click="logoutAppSession('{{ $token->id }}')"
                                                   loading="logoutAppSession('{{ $token->id }}')"
                                                   :tooltip="__('account::sessions.tooltips.logout')">
                                    <i class="icon-log-out"></i>
                                </x-button.floating>
                            </x-table.body.item>
                        </x-table.body.row>
                    @endforeach
                </x-table.body>
            </x-table>
        </x-cf.card>
    </div>
</div>
