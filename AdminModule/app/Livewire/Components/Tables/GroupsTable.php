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
use Spatie\Permission\Models\Role;
use TallStackUi\Traits\Interactions;

final class GroupsTable extends PowerGridComponent
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
        return Role::query();
    }

    public function header(): array
    {
        if (Auth::user()->cannot('adminmodule.groups.create')) {
            return [];
        }
        return [
            Button::add('create')
                ->slot('<x-button wire:navigate href="' . route('admin.groups.create') . '">{{ __("adminmodule::groups.buttons.create_group") }}</x-button>'),
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
            ->add('created_at_formatted', fn (Role $model) => Carbon::parse($model->created_at)->format('d.m.Y H:i'))
            ->add('updated_at')
            ->add('updated_at_formatted', fn (Role $model) => Carbon::parse($model->updated_at)->format('d.m.Y H:i'));
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.table.id'), 'id')
                ->searchable()
                ->sortable(),

            Column::make(__('adminmodule::groups.name'), 'name')
                ->searchable()
                ->sortable(),

            Column::make(__('adminmodule::groups.guard_name'), 'guard_name')
                ->searchable()
                ->sortable(),

            Column::make(__('adminmodule::groups.module'), 'module')
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

    public function deleteGroup($groupId, $confirmed = true)
    {
        if (Auth::user()->cannot('adminmodule.groups.delete')) {
            return;
        }

        if ($confirmed) {
            try {
                $group = Role::findOrFail($groupId)->first();
                $group->update([
                    'guard_name' => 'web',
                ]);

                if ($group->name === 'Super Admin') {
                    return;
                }

                $group->delete();

                Notification::make()
                    ->title(__('adminmodule::groups.delete_group.notifications.group_deleted'))
                    ->success()
                    ->send();

                $this->redirect(route('admin.groups'), navigate: true);
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
            ->error(__('adminmodule::groups.delete_group.title'),
                __('adminmodule::groups.delete_group.description'))
            ->confirm(__('messages.buttons.delete'), 'deleteGroup', [$groupId])
            ->cancel()
            ->send();

    }

    public function actions(Role $row): array
    {
        return [
            Button::add('update')
                ->slot('<x-button color="blue" wire:navigate href="' . route('admin.groups.update', ['groupId' => $row->id]) . '" sm><i class="icon-pen"></i></x-button>')
                ->id(),

            Button::add('delete')
                ->slot('<x-button color="red" wire:click="deleteGroup(`' . $row->id . '`, false)" sm><i class="icon-trash"></i></x-button>')
                ->id(),
        ];
    }

    public function actionRules(Role $row): array
    {
        return [
            Rule::button('delete')
                ->when(fn ($row) => $row->name === 'Super Admin')
                ->slot('<x-button color="red" disabled sm><i class="icon-trash"></i></x-button>'),

            Rule::button('update')
                ->when(fn ($row) => $row->name === 'Super Admin')
                ->slot('<x-button color="blue" disabled sm><i class="icon-pen"></i></x-button>'),

            Rule::button('delete')
                ->when(fn ($row) => Auth::user()->cannot('adminmodule.groups.delete'))
                ->hide(),

            Rule::button('update')
                ->when(fn ($row) => Auth::user()->cannot('adminmodule.groups.update'))
                ->hide(),
        ];
    }
}
