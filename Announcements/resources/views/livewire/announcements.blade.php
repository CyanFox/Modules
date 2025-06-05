@php use Illuminate\Support\Facades\Storage; @endphp
<div>
    <x-link wire:click="toggleShowDismissed" class="text-sm">
        {{ $showDismissed ? __('announcements::announcements.hide_dismissed') : __('announcements::announcements.show_dismissed') }}
    </x-link>
    @foreach($announcements as $announcement)
        @php
            $borderColor = match($announcement->color) {
                 'danger' => 'border-danger-500 dark:border-danger-500',
                 'success' => 'border-success-500 dark:border-success-500',
                 'warning' => 'border-warning-500 dark:border-warning-500',
                 default => 'border-info-500 dark:border-info-500',
             };

            $files = collect(Storage::disk('local')->files('announcements/' . $announcement->id))
            ->map(function ($filePath) {
                return [
                    'name' => basename($filePath),
                    'size' => formatFileSize(Storage::disk('local')->size($filePath)),
                    'path' => $filePath
                ];
            })->toArray();

            $isDismissed = $announcement->dismissed->contains('user_id', auth()->id());

            if ($isDismissed) {
                $borderColor .= ' opacity-50';
            }
        @endphp
        <div class="my-4">
            <x-card class="border-2 rounded-lg {{ $borderColor }}" wire:ignore>
                <div class="md:flex md:justify-between md:items-center">
                    <div class="text-xl flex items-center gap-2">
                        <i class="icon-{{ $announcement->icon }}"></i> {{ $announcement->title }}
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="icon-clock text-xl"></i>
                        {{ $announcement->created_at->diffForHumans() }}
                        @if($announcement->dismissible)
                            @if($isDismissed)
                                <i class="icon-rotate-ccw text-success-600 text-xl cursor-pointer"
                                   wire:click="dismissAnnouncement('{{ $announcement->id }}', false)"></i>
                            @else
                                <i class="icon-x text-danger-600 text-xl cursor-pointer"
                                   wire:click="dismissAnnouncement('{{ $announcement->id }}', true)"></i>
                            @endif
                        @endif
                    </div>
                </div>
                @if($announcement->description)
                    <x-divider/>
                    <div class="prose dark:prose-invert max-w-none">
                        {!! str()->markdown($announcement->description) !!}
                    </div>
                @endif
                @if($files)
                    <x-divider/>

                    <div class="overflow-x-auto mt-4">
                        <x-table>
                            <x-table.body>
                                @foreach($files as $file)
                                    <tr>
                                        <x-table.body.item>
                                            {{ $file['name'] }}
                                        </x-table.body.item>
                                        <x-table.body.item class="text-end">
                                            <x-button.floating wire:click="downloadFile('{{ $announcement->id }}', '{{ $file['name'] }}')"
                                                               loading="downloadFile" size="sm">
                                                <i class="icon-download"></i>
                                            </x-button.floating>
                                        </x-table.body.item>
                                    </tr>
                                @endforeach
                            </x-table.body>
                        </x-table>
                    </div>
                @endif
            </x-card>
        </div>
    @endforeach
</div>
