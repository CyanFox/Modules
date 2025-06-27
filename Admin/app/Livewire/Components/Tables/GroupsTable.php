<?php

namespace Modules\Admin\Livewire\Components\Tables;

use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Modules\Auth\Traits\WithConfirmation;
use RealZone22\PenguTables\Livewire\PenguTable;
use RealZone22\PenguTables\Table\Action;
use RealZone22\PenguTables\Table\Column;
use RealZone22\PenguTables\Table\Header;
use RealZone22\PenguTables\Traits\WithExport;
use Spatie\Permission\Models\Role;

final class GroupsTable extends PenguTable
{
    use WithConfirmation, WithCustomLivewireException, WithExport;

    public function header(): array
    {
        if (auth()->user()->cannot('admin.groups.create')) {
            return [];
        }

        return [
            Header::make('<x-button class="flex" wire:navigate link="'.route('admin.groups.create').'">'.__('admin::groups.buttons.create_group').'</x-button>'),
        ];
    }

    public function query(): Builder
    {
        return Role::query();
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

            Column::make(__('messages.tables.created_at'), 'created_at')
                ->searchable()
                ->sortable(),

            Column::make(__('messages.tables.updated_at'), 'updated_at')
                ->searchable()
                ->sortable(),

            Column::actions(__('messages.tables.actions'), function ($row) {
                $actions = [];

                if (auth()->user()->can('admin.groups.update') && $row->id !== Role::findByName('Super Admin')->id) {
                    $actions[] = Action::make('<x-button.floating size="sm" wire:navigate link="'.route('admin.groups.update', ['groupId' => $row->id]).'"><i class="icon-pen"></i></x-button.floating>');
                }

                if (auth()->user()->can('admin.groups.delete') && $row->id !== Role::findByName('Super Admin')->id) {
                    $actions[] = Action::make('<x-button.floating color="danger" size="sm" wire:click="deleteGroup(`'.$row->id.'`, false)"><i class="icon-trash"></i></x-button.floating>');
                }

                return $actions;
            }),
        ];
    }

    public function deleteGroup($groupId, $confirmed = true)
    {
        if (auth()->user()->cannot('admin.groups.delete')) {
            return;
        }

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

            $this->redirect(url()->previous(), true);

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
}
