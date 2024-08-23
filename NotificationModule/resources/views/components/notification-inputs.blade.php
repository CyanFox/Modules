<div class="grid md:grid-cols-2 gap-4 my-4">
    <x-input label="{{ __('notificationmodule::notifications.title') }} *" wire:model="title" required/>

    <x-select.styled label="{{ __('notificationmodule::notifications.type') }} *" :options="[
                            ['label' => __('notificationmodule::notifications.types.info'), 'value' => 'info'],
                            ['label' => __('notificationmodule::notifications.types.update'), 'value' => 'update'],
                            ['label' => __('notificationmodule::notifications.types.success'), 'value' => 'success'],
                            ['label' => __('notificationmodule::notifications.types.warning'), 'value' => 'warning'],
                            ['label' => __('notificationmodule::notifications.types.danger'), 'value' => 'danger']]"
                     select="label:label|value:value" wire:model="type" searchable/>
</div>

<div class="grid md:grid-cols-3 gap-4 my-4">
    <x-select.styled label="{{ __('notificationmodule::notifications.dismissible') }} *" :options="[
                            ['label' => __('messages.yes'), 'value' => '1'],
                            ['label' => __('messages.no'), 'value' => '0']]"
                     select="label:label|value:value" wire:model="dismissible" searchable/>

    <x-select.styled label="{{ __('notificationmodule::notifications.location') }} *" :options="[
                            ['label' => __('notificationmodule::notifications.locations.dashboard'), 'value' => 'dashboard'],
                            ['label' => __('notificationmodule::notifications.locations.notifications'), 'value' => 'notifications']]"
                     select="label:label|value:value" wire:model="location" searchable/>

    <x-input label="{{ __('notificationmodule::notifications.icon') }} *" wire:model.live="icon"
             hint="{!! __('notificationmodule::notifications.icon_hint') !!}" required>
        <x-slot:prefix>
            <i class="icon-{{ $notificationIcon }}"></i>
        </x-slot:prefix>
    </x-input>
</div>

<div class="mb-4">
    <x-textarea label="{{ __('notificationmodule::notifications.message') }}" wire:model="message" :hint="__('notificationmodule::notifications.message_hint')" resize-auto/>
</div>

<div class="mb-4">
    <x-upload label="{{ __('notificationmodule::notifications.files') }}" wire:model="files" delete-method="deleteFile" delete multiple />
</div>
