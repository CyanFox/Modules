<div>
    <x-cf.card :title="__('announcements::announcements.update_announcement.title')"
               view-integration="admin.announcements.update">
        <form wire:submit="updateAnnouncement">
            <x-announcements::inputs.announcements :icon="$icon"/>

            <x-cf.buttons.update target="updateAnnouncement"
                                 :update-text="__('announcements::announcements.update_announcement.buttons.update_announcement')"
                                 :back-url="route('admin.announcements')"/>
        </form>
    </x-cf.card>

    <x-card class="mt-4">
        <x-card.title>
            {{ __('announcements::announcements.files') }}
        </x-card.title>

        <x-table>
            <x-table.header>
                <x-table.header.item>
                    {{ __('announcements::announcements.file_name') }}
                </x-table.header.item>
                <x-table.header.item>
                    {{ __('announcements::announcements.size') }}
                </x-table.header.item>
                <x-table.header.item>
                    {{ __('messages.tables.actions') }}
                </x-table.header.item>
            </x-table.header>
            <x-table.body>
                @foreach($uploadedFiles as $file)
                    <tr>
                        <x-table.body.item>
                            {{ $file['name'] }}
                        </x-table.body.item>
                        <x-table.body.item>
                            {{ $file['size'] }}
                        </x-table.body.item>
                        <x-table.body.item>
                            <x-button.floating wire:click="deleteFile('{{ $file['name'] }}', false)"
                                               loading="deleteFile" size="sm" color="danger">
                                <i class="icon-trash"></i>
                            </x-button.floating>
                        </x-table.body.item>
                    </tr>
                @endforeach
            </x-table.body>
        </x-table>
    </x-card>
</div>
