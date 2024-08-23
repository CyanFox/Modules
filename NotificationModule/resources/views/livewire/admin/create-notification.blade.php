<div>
    <x-cf.card :title="__('notificationmodule::notifications.create_notification.title')" view-integration="adminmodule.notifications.create">
        <form wire:submit="createNotification">
            <x-notificationmodule::notification-inputs :notificationIcon="$icon"/>

            <x-actions.buttons.create target="createNotification" :back-url="route('admin.notifications')"/>
        </form>
    </x-cf.card>
</div>
