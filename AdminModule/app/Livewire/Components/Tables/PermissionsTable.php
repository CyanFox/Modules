<?php

namespace Modules\AdminModule\Livewire\Components\Tables;

use Exception;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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
use Spatie\Permission\Models\Permission;
use TallStackUi\Traits\Interactions;

final class PermissionsTable extends PowerGridComponent
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
        return Permission::query();
    }

    public function header(): array
    {
        return [
            Button::add('create')
                ->slot('<x-button wire:click="$dispatch(`clearForm`)" x-on:click="$slideOpen(`create-permission-slide`)">{{ __("adminmodule::permissions.buttons.create_permission") }}</x-button>'),

            Button::add('bulkDelete')
                ->slot('<x-button wire:click="bulkDelete" color="red" loading>{!! __("messages.table.bulk_delete", ["table" => $this->tableName]) !!}</x-button>'),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('guard_name')
            ->add('module')
            ->add('created_at')
            ->add('created_at_formatted', fn (Permission $model) => Carbon::parse($model->created_at)->format('d.m.Y H:i'))
            ->add('updated_at')
            ->add('updated_at_formatted', fn (Permission $model) => Carbon::parse($model->updated_at)->format('d.m.Y H:i'));
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.table.id'), 'id')
                ->searchable()
                ->sortable(),

            Column::make(__('adminmodule::permissions.name'), 'name')
                ->searchable()
                ->sortable(),

            Column::make(__('adminmodule::permissions.guard_name'), 'guard_name')
                ->searchable()
                ->sortable(),

            Column::make(__('adminmodule::permissions.module'), 'module')
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
            Permission::destroy($this->checkboxValues);
            $this->js('window.pgBulkActions.clearAll()');
        }
    }

    public function deletePermission($permissionId, $confirmed = true)
    {
        if (Auth::user()->cannot('adminmodule.groups.delete')) {
            return;
        }

        if ($confirmed) {
            try {
                $permission = Permission::findOrFail($permissionId)->first();

                $permission->delete();

                Notification::make()
                    ->title(__('adminmodule::permissions.delete_permission.notifications.permission_deleted'))
                    ->success()
                    ->send();

                $this->redirect(route('admin.permissions'), navigate: true);
            } catch (Exception $e) {
                Notification::make()
                    ->title(__('messages.notifications.something_went_wrong'))
                    ->danger()
                    ->send();

                $this->dispatch('logger', ['type' => 'error', 'message' => $e]);
            }

            return;
        }

        $this->dialog()
            ->error(__('adminmodule::permissions.delete_permission.title'),
                __('adminmodule::permissions.delete_permission.description'))
            ->confirm(__('adminmodule::permissions.delete_permission.buttons.delete_permission'), 'deletePermission', [$permissionId])
            ->cancel()
            ->send();

    }

    public function actions(Permission $row): array
    {
        return [
            Button::add('update')
                ->slot('<x-button color="blue" x-on:click="$slideOpen(`update-permission-slide`)" wire:click="$dispatch(`updatePermissionParams`, { permissionId: `' . $row->id . '` })" sm><i class="icon-pen"></i></x-button>')
                ->id(),

            Button::add('delete')
                ->slot('<x-button color="red" wire:click="deletePermission(`' . $row->id . '`, false)" sm><i class="icon-trash"></i></x-button>')
                ->id(),
        ];
    }

    public function actionRules(Permission $row): array
    {
        return [
            Rule::button('delete')
                ->when(fn ($row) => Auth::user()->cannot('adminmodule.permissions.delete'))
                ->hide(),

            Rule::button('update')
                ->when(fn ($row) => Auth::user()->cannot('adminmodule.permissions.update'))
                ->hide(),

            Rule::button('bulkDelete')
                ->when(fn ($row) => Auth::user()->cannot('adminmodule.permissions.delete'))
                ->hide(),

            Rule::button('create')
                ->when(fn ($row) => Auth::user()->cannot('adminmodule.permissions.create'))
                ->hide(),
        ];
    }
}
