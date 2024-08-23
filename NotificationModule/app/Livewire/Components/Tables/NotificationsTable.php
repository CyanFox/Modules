<?php

namespace Modules\NotificationModule\Livewire\Components\Tables;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\NotificationModule\Models\Notification;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use TallStackUi\Traits\Interactions;

final class NotificationsTable extends PowerGridComponent
{
    use Interactions, WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Notification::query()->orderBy('created_at', 'desc');
    }

    public function header(): array
    {
        $header = [];

        if (Auth::user()->can('notificationmodule.notifications.admin.create')) {
            $header[] = Button::add('create')
                ->slot('<x-button wire:navigate href="' . route('admin.notifications.create') . '">{{ __("notificationmodule::notifications.buttons.create_notification") }}</x-button>');
        }

        return $header;
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('title')
            ->add('message', fn(Notification $model) => Str::limit($model->message, 50))
            ->add('type', fn(Notification $model) => __('notificationmodule::notifications.types.' . $model->type))
            ->add('icon', fn(Notification $model) => '<i class="' . $model->icon . '"></i>')
            ->add('dismissible', fn(Notification $model) => $model->dismissible ? '<i class="icon-check text-green-600"></i>' : '<i class="icon-x text-red-600"></i>')
            ->add('location', fn(Notification $model) => __('notificationmodule::notifications.locations.' . $model->location))
            ->add('created_at')
            ->add('created_at_formatted', fn(Notification $model) => Carbon::parse($model->created_at)->format('d.m.Y H:i'))
            ->add('updated_at')
            ->add('updated_at_formatted', fn(Notification $model) => Carbon::parse($model->updated_at)->format('d.m.Y H:i'));
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.table.id'), 'id')
                ->searchable()
                ->sortable(),

            Column::make(__('notificationmodule::notifications.title'), 'title')
                ->searchable()
                ->sortable(),

            Column::make(__('notificationmodule::notifications.message'), 'message')
                ->searchable()
                ->sortable(),

            Column::make(__('notificationmodule::notifications.type'), 'type')
                ->searchable()
                ->sortable(),

            Column::make(__('notificationmodule::notifications.icon'), 'icon')
                ->searchable()
                ->sortable(),

            Column::make(__('notificationmodule::notifications.dismissible'), 'dismissible')
                ->searchable()
                ->sortable(),

            Column::make(__('notificationmodule::notifications.location'), 'location')
                ->searchable()
                ->sortable(),

            Column::make(__('messages.table.created_at'), 'created_at')
                ->hidden(),

            Column::make(__('messages.table.created_at'), 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable(),

            Column::make(__('messages.table.updated_at'), 'updated_at')
                ->hidden(),

            Column::make(__('messages.table.updated_at'), 'updated_at_formatted', 'updated_at')
                ->searchable()
                ->sortable(),

            Column::action(__('messages.table.actions')),
        ];
    }

    public function filters(): array
    {
        return [];
    }

    public function bulkDelete(): void
    {
        if (Auth::user()->cannot('adminmodule.permissions.delete')) {
            return;
        }

        if ($this->checkboxValues) {
            Notification::destroy($this->checkboxValues);
            $this->js('window.pgBulkActions.clearAll()');
        }
    }

    public function deleteNotification($notificationId, $confirmed = true)
    {
        if (Auth::user()->cannot('notificationmodule.notifications.admin.delete')) {
            return;
        }

        if ($confirmed) {
            try {
                $notification = Notification::findOrFail($notificationId)->first();

                Storage::deleteDirectory('public/notifications/' . $notification->id);

                $notification->delete();

                \Filament\Notifications\Notification::make()
                    ->title(__('notificationmodule::notifications.delete_notification.notifications.notification_deleted'))
                    ->success()
                    ->send();

                $this->redirect(route('admin.notifications'), navigate: true);
            } catch (Exception $e) {
                \Filament\Notifications\Notification::make()
                    ->title(__('messages.notifications.something_went_wrong'))
                    ->danger()
                    ->send();

                $this->dispatch('logger', ['type' => 'error', 'message' => $e]);
            }

            return;
        }

        $this->dialog()
            ->error(__('notificationmodule::notifications.delete_notification.title'),
                __('notificationmodule::notifications.delete_notification.description'))
            ->confirm(__('messages.buttons.delete'), 'deleteNotification', [$notificationId])
            ->cancel()
            ->send();

    }

    public function actions(Notification $row): array
    {
        return [
            Button::add('update')
                ->slot('<x-button color="blue" wire:navigate href="' . route('admin.notifications.update', ['notificationId' => $row->id]) . '" sm><i class="icon-pen"></i></x-button>')
                ->id(),

            Button::add('delete')
                ->slot('<x-button color="red" wire:click="deleteNotification(`' . $row->id . '`, false)" sm><i class="icon-trash"></i></x-button>')
                ->id(),
        ];
    }

    public function actionRules(Notification $row): array
    {
        return [
            Rule::button('delete')
                ->when(fn($row) => Auth::user()->cannot('notificationmodule.notifications.admin.delete'))
                ->hide(),

            Rule::button('update')
                ->when(fn($row) => Auth::user()->cannot('notificationmodule.notifications.admin.update'))
                ->hide(),
        ];
    }
}
