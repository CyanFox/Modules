<div>
    <x-cf.card :title="__('notificationmodule::notifications.update_notification.title')" view-integration="adminmodule.notifications.update">
        <form wire:submit="updateNotification">
            <x-notificationmodule::notification-inputs :notificationIcon="$icon"/>


            <div class="overflow-x-auto mt-4">
                <table class="min-w-full">
                    <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($existingFiles as $path)
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
                                    <x-button color="red" type="button" wire:click="deleteExistingFile('{{ $file }}')" loading>
                                        <i class="icon-trash"></i>
                                    </x-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-actions.buttons.update target="updateNotification" :back-url="route('admin.notifications')"/>
        </form>
    </x-cf.card>
</div>
