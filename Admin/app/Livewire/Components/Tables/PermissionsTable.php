<?php

namespace Modules\Admin\Livewire\Components\Tables;

use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Modules\Auth\Actions\Permissions\DeletePermissionAction;
use Modules\Auth\Actions\Permissions\UpdatePermissionAction;
use Modules\Auth\Models\Permission;
use Modules\Auth\Traits\WithConfirmation;
use RealZone22\PenguTables\Livewire\PenguTable;
use RealZone22\PenguTables\Table\Action;
use RealZone22\PenguTables\Table\Column;
use RealZone22\PenguTables\Table\Header;
use RealZone22\PenguTables\Traits\WithExport;

final class PermissionsTable extends PenguTable
{
    use WithConfirmation, WithCustomLivewireException, WithExport;

    public function header(): array
    {
        if (auth()->user()->cannot('admin.permissions.create')) {
            return [];
        }

        return [
            Header::make('<x-button class="flex" wire:navigate link="'.route('admin.permissions.create').'">'.__('admin::permissions.buttons.create_permission').'</x-button>'),
        ];
    }

    public function query(): Builder
    {
        return Permission::query();
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

            Column::make(__('messages.tables.created_at'), 'created_at')
                ->searchable()
                ->sortable(),

            Column::make(__('messages.tables.updated_at'), 'updated_at')
                ->searchable()
                ->sortable(),

            Column::actions(__('messages.tables.actions'), function ($row) {
                $actions = [];

                if (auth()->user()->can('admin.permissions.update')) {
                    $actions[] = Action::make('<x-button.floating wire:navigate size="sm" link="'.route('admin.permissions.update', ['permissionId' => $row->id]).'"><i class="icon-pen"></i></x-button.floating>');
                }

                if (auth()->user()->can('admin.permissions.delete')) {
                    $actions[] = Action::make('<x-button.floating color="danger" size="sm" wire:click="deletePermission(`'.$row->id.'`, false)"><i class="icon-trash"></i></x-button.floating>');
                }

                return $actions;
            }),
        ];
    }

    public function deletePermission($permissionId, $confirmed = true)
    {
        if (auth()->user()->cannot('admin.permissions.delete')) {
            return;
        }

        if ($confirmed) {
            $permission = Permission::find($permissionId);

            UpdatePermissionAction::run($permission, [
                'guard_name' => 'web',
            ]);

            if (! DeletePermissionAction::run($permission)) {
                Notification::make()
                    ->title(__('messages.notifications.something_went_wrong'))
                    ->danger()
                    ->send();

                return;
            }

            Notification::make()
                ->title(__('admin::permissions.delete_permission.notifications.permission_deleted'))
                ->success()
                ->send();

            $this->redirect(url()->previous(), true);

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
}
