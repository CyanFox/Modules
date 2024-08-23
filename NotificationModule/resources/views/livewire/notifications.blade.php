<div>
    @foreach($notifications as $notification)
        @if($notification->location !== $currentLocation)
            @continue
        @endif
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
                    <div class="grid xl:grid-cols-7 lg:grid-cols-4 md:grid-cols-2 gap-4">
                        @if ($notification->files !== null)
                            @foreach($notification->files as $path)
                                @php
                                    $file = basename($path);
                                    $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                                @endphp
                                <x-card class="rounded-lg border border-1 border-gray-400">
                                    <div class="flex justify-center">
                                        <i class="icon-file text-7xl"></i>
                                    </div>

                                    <div class="flex flex-col items-center justify-center">
                                        <p class="text-center">{{ $file }}</p>
                                    </div>
                                    <div class="flex justify-end mt-4 mr-4">
                                        <x-button type="button"
                                                  wire:click="downloadFile('{{ $notification->id }}', '{{ $file }}')"
                                                  loading>
                                            <i class="icon-download"></i>
                                        </x-button>
                                    </div>
                                </x-card>
                            @endforeach
                        @endif
                    </div>
                </div>
            </x-card>
        </div>
    @endforeach
</div>
