<div>
    <x-cf.card :title="__('notificationmodule::notifications.update_notification.title')" view-integration="adminmodule.notifications.update">
        <form wire:submit="updateNotification">
            <x-notificationmodule::notification-inputs :notificationIcon="$icon"/>


            <div class="overflow-x-auto mt-4">
                <div class="grid xl:grid-cols-7 lg:grid-cols-4 md:grid-cols-2 gap-4">
                    @foreach($existingFiles as $path)
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
                                <x-button color="red" type="button" wire:click="deleteExistingFile('{{ $file }}')" loading>
                                    <i class="icon-trash"></i>
                                </x-button>
                            </div>
                        </x-card>
                    @endforeach
                </div>
            </div>

            <x-actions.buttons.update target="updateNotification" :back-url="route('admin.notifications')"/>
        </form>
    </x-cf.card>
</div>
