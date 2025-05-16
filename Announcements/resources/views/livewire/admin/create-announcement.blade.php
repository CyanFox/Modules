<div>
    <x-cf.card :title="__('announcements::announcements.create_announcement.title')" view-integration="admin.announcements.create">
        <form wire:submit="createAnnouncement">
            <x-announcements::inputs.announcements :icon="$icon"/>

            <x-cf.buttons.create target="createAnnouncement" :create-text="__('announcements::announcements.create_announcement.buttons.create_announcement')"
                                 :back-url="route('admin.announcements')"/>
        </form>
    </x-cf.card>
</div>
