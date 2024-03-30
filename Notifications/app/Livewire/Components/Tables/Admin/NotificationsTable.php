<?php

namespace Modules\Notifications\app\Livewire\Components\Tables\Admin;

use Livewire\Attributes\On;
use Modules\Notifications\app\Models\Notification;
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
            'toolbar-left-start' => 'notifications::components.tables.admin.create-notification',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.table.id'), 'id')
                ->sortable(),
            Column::make(__('notifications::notifications.notification_list.table.title'), 'title')
                ->sortable()
                ->searchable(),
            Column::make(__('notifications::notifications.notification_list.table.message'), 'message')
                ->sortable()
                ->searchable()
                ->format(fn ($value) => Str::limit($value, 50)),
            Column::make(__('notifications::notifications.notification_list.table.type'), 'type')
                ->sortable()
                ->format(fn ($value) => __('notifications::notifications.types.'.$value)),
            Column::make(__('notifications::notifications.notification_list.table.icon'), 'icon')
                ->sortable()
                ->format(fn ($value) => '<i class="'.$value.' text-lg"></i>')
                ->html(),
            BooleanColumn::make(__('notifications::notifications.table.dismissible'), 'dismissible')
                ->sortable(),
            Column::make(__('notifications::notifications.notification_list.table.location'), 'location')
                ->sortable()
                ->format(fn ($value) => __('notifications::notifications.locations.'.$value)),
            Column::make(__('messages.table.created_at'), 'created_at')
                ->sortable(),
            Column::make(__('messages.table.updated_at'), 'updated_at')
                ->sortable(),
            Column::make(__('messages.table.actions'))
                ->label(function ($row) {
                    return
                        '<a href="'.route('admin.notifications.update', ['notificationId' => $row->id]).'"><i class="icon-pen font-semibold text-lg text-blue-600 px-2"></i></a>'.
                        '<i wire:click="$dispatch(`openModal`, { component: `notifications::components.modals.admin.delete-notification`,
                        arguments: { notificationId: `'.$row->id.'` } })" class="icon-trash font-semibold text-lg text-red-600 cursor-pointer"></i>';
                })
                ->html(),
        ];
    }
}
