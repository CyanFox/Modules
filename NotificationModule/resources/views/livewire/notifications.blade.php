<div>
    @foreach($notifications as $notification)
        @if($notification->location !== $currentLocation)
            @continue
        @endif

        @foreach(json_decode($notification->permissions) as $permission)
            @if(auth()->user()->cannot($permission))
                @continue(2)
            @endif
        @endforeach

        <div class="my-4">
            <x-card class="border-2 rounded-lg {{ $notification->border }}" wire:ignore>
                <div class="md:flex md:justify-between">
                    <div>
                        <p><i class="{{ $notification->icon }}"></i> {{ $notification->title }}</p>
                        <x-badge
                            color="{{ $notification->badge }}">{{ __('notificationmodule::notifications.types.' . $notification->type) }}</x-badge>
                    </div>
                    <div class="flex items-center mb-5 gap-2">
                        <i class="icon-clock text-xl"></i>
                        {{ $notification->created_at->diffForHumans() }}
                        @if($notification->dismissible)
                            <i class="icon-x text-red-600 text-xl cursor-pointer"
                               wire:click="dismissNotification('{{ $notification->id }}')"></i>
                        @endif
                    </div>
                </div>
                @if($notification->message)
                    <x-divider/>
                    {!! \Illuminate\Support\Str::markdown($notification->message) !!}
                @endif
                @if($notification->files)
                    <x-divider/>
                @endif

                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if ($notification->files !== null)
                            @foreach($notification->files as $path)
                                @php
                                    $file = basename($path);
                                    $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                                @endphp
                                <tr class="border-b dark:border-dark-600 border-gray-300">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="icon-file text-2xl mr-2"></i>
                                            <div class="text-sm font-medium">{{ $file }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <x-button type="button"
                                                  wire:click="downloadFile('{{ $notification->id }}', '{{ $file }}')"
                                                  loading>
                                            <i class="icon-download"></i>
                                        </x-button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    @endforeach
</div>
