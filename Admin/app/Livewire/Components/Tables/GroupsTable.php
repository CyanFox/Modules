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
use Spatie\Permission\Models\Role;

final class GroupsTable extends PowerGridComponent
{
    use WithCustomLivewireException, WithConfirmation;

    public string $tableName = 'admin-groups-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('groups')
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
        return [
            Button::add('create')
                ->slot(Blade::render('<x-button class="flex" wire:navigate link="' . route('admin.groups.create') . '">' . __('admin::groups.buttons.create_group') . '</x-button>')),
        ];
    }

    public function datasource(): Builder
    {
        return Role::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('guard_name')
            ->add('created_at_formatted', fn($row) => $row->created_at->format('d.m.Y H:i'))
            ->add('updated_at_formatted', fn($row) => $row->updated_at->format('d.m.Y H:i'));
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.tables.id'), 'id')
                ->searchable()
                ->sortable(),

            Column::make(__('admin::groups.name'), 'name')
                ->searchable()
                ->sortable(),

            Column::make(__('admin::groups.guard_name'), 'guard_name')
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

    public function deleteGroup($groupId, $confirmed = true)
    {
        if ($groupId === Role::findByName('Super Admin')->id) {
            return;
        }
        if ($confirmed) {
            $group = Role::find($groupId);

            $group->update([
                'guard_name' => 'web',
            ]);

            $group->delete();

            Notification::make()
                ->title(__('admin::groups.delete_group.notifications.group_deleted'))
                ->success()
                ->send();

            $this->redirect(route('admin.groups'), navigate: true);

            return;
        }

        $this->dialog()
            ->question(__('admin::groups.delete_group.title'),
                __('admin::groups.delete_group.description'))
            ->icon('icon-triangle-alert')
            ->confirm(__('admin::groups.delete_group.buttons.delete_group'), 'danger')
            ->method('deleteGroup', $groupId)
            ->send();

    }

    public function actions(Role $row): array
    {
        return [
            Button::add('update')
                ->slot(Blade::render('<x-button class="px-2 py-1 flex" wire:navigate link="' . route('admin.groups.update', ['groupId' => $row->id]) . '"><i class="icon-pen"></i></x-button>')),

            Button::add('delete')
                ->slot(Blade::render('<x-button color="danger" class="px-2 py-1 flex" wire:click="deleteGroup(`' . $row->id . '`, false)"><i class="icon-trash"></i></x-button>')),
        ];
    }

    public function actionRules(): array
    {
        return [
            Rule::button('delete')
                ->when(fn($row) => $row->id == Role::findByName('Super Admin')->id)
                ->hide(),

            Rule::button('update')
                ->when(fn($row) => $row->id == Role::findByName('Super Admin')->id)
                ->hide(),
        ];
    }
}
