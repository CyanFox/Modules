<?php

namespace Modules\NotificationModule\app\Livewire\Components\Tables\Admin;

use Livewire\Attributes\On;
use Modules\NotificationModule\app\Models\Notification;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Str;

class NotificationsTable extends DataTableComponent
{
    protected $model = Notification::class;

    #[On('refresh')]
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setConfigurableAreas([
            'toolbar-left-start' => 'notificationmodule::components.tables.admin.create-notification',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.table.id'), 'id')
                ->sortable(),
            Column::make(__('notificationmodule::notifications.notification_list.table.title'), 'title')
                ->sortable()
                ->searchable(),
            Column::make(__('notificationmodule::notifications.notification_list.table.message'), 'message')
                ->sortable()
                ->searchable()
                ->format(fn ($value) => Str::limit($value, 50)),
            Column::make(__('notificationmodule::notifications.notification_list.table.type'), 'type')
                ->sortable()
                ->format(fn ($value) => __('notificationmodule::notifications.types.'.$value)),
            Column::make(__('notificationmodule::notifications.notification_list.table.icon'), 'icon')
                ->sortable()
                ->format(fn ($value) => '<i class="'.$value.' text-lg"></i>')
                ->html(),
            BooleanColumn::make(__('notificationmodule::notifications.table.dismissible'), 'dismissible')
                ->sortable(),
            Column::make(__('notificationmodule::notifications.notification_list.table.location'), 'location')
                ->sortable()
                ->format(fn ($value) => __('notificationmodule::notifications.locations.'.$value)),
            Column::make(__('messages.table.created_at'), 'created_at')
                ->sortable(),
            Column::make(__('messages.table.updated_at'), 'updated_at')
                ->sortable(),
            Column::make(__('messages.table.actions'))
                ->label(function ($row) {
                    return
                        '<a href="'.route('admin.notifications.update', ['notificationId' => $row->id]).'"><i class="icon-pen font-semibold text-lg text-blue-600 px-2"></i></a>'.
                        '<i wire:click="$dispatch(`openModal`, { component: `notificationmodule::components.modals.admin.delete-notification`,
                        arguments: { notificationId: `'.$row->id.'` } })" class="icon-trash font-semibold text-lg text-red-600 cursor-pointer"></i>';
                })
                ->html(),
        ];
    }
}
