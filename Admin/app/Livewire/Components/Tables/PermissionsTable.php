<?php

namespace Modules\Admin\Livewire\Components\Tables;

use App\Traits\WithConfirmation;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use Spatie\Permission\Models\Permission;

final class PermissionsTable extends PowerGridComponent
{
    use WithConfirmation, WithCustomLivewireException;

    public string $tableName = 'admin-permissions-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('permissions')
                ->striped()
                ->type(Exportable::TYPE_CSV, Exportable::TYPE_XLS),
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function header(): array
    {
        if (auth()->user()->cannot('admin.permissions.create')) {
            return [];
        }

        return [
            Button::add('create')
                ->slot(Blade::render('<x-button class="flex" wire:navigate link="'.route('admin.permissions.create').'">'.__('admin::permissions.buttons.create_permission').'</x-button>')),
        ];
    }

    public function datasource(): Builder
    {
        return Permission::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('guard_name')
            ->add('created_at_formatted', fn ($row) => $row->created_at->format('d.m.Y H:i'))
            ->add('updated_at_formatted', fn ($row) => $row->updated_at->format('d.m.Y H:i'));
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.tables.id'), 'id')
                ->searchable()
                ->sortable(),

            Column::make(__('admin::permissions.name'), 'name')
                ->searchable()
                ->sortable(),

            Column::make(__('admin::permissions.guard_name'), 'guard_name')
                ->searchable()
                ->sortable(),

            Column::make(__('messages.tables.created_at'), 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable(),

            Column::make(__('messages.tables.updated_at'), 'updated_at_formatted', 'updated_at')
                ->searchable()
                ->sortable(),

            Column::action(__('messages.tables.actions')),
        ];
    }

    public function deletePermission($permissionId, $confirmed = true)
    {
        if (auth()->user()->cannot('admin.permissions.delete')) {
            return;
        }

        if ($confirmed) {
            $permission = Permission::find($permissionId);

            $permission->update([
                'guard_name' => 'web',
            ]);

            $permission->delete();

            Notification::make()
                ->title(__('admin::permissions.delete_permission.notifications.permission_deleted'))
                ->success()
                ->send();

            $this->redirect(route('admin.permissions'), navigate: true);

            return;
        }

        $this->dialog()
            ->question(__('admin::permissions.delete_permission.title'),
                __('admin::permissions.delete_permission.description'))
            ->icon('icon-triangle-alert')
            ->confirm(__('admin::permissions.delete_permission.buttons.delete_permission'), 'danger')
            ->method('deletePermission', $permissionId)
            ->send();

    }

    public function actions(Permission $row): array
    {
        return [
            Button::add('update')
                ->slot(Blade::render('<x-button class="px-2 py-1 flex" wire:navigate link="'.route('admin.permissions.update', ['permissionId' => $row->id]).'"><i class="icon-pen"></i></x-button>')),

            Button::add('delete')
                ->slot(Blade::render('<x-button color="danger" class="px-2 py-1 flex" wire:click="deletePermission(`'.$row->id.'`, false)"><i class="icon-trash"></i></x-button>')),
        ];
    }

    public function actionRules(): array
    {
        return [
            Rule::button('delete')
                ->when(fn ($row) => auth()->user()->cannot('admin.permissions.delete'))
                ->hide(),

            Rule::button('update')
                ->when(fn ($row) => auth()->user()->cannot('admin.permissions.update'))
                ->hide(),
        ];
    }
}
