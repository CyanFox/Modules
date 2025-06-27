<?php

namespace Modules\Admin\Livewire\Components\Tables;

use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Modules\Auth\Models\User;
use Modules\Auth\Traits\WithConfirmation;
use RealZone22\PenguTables\Livewire\PenguTable;
use RealZone22\PenguTables\Table\Action;
use RealZone22\PenguTables\Table\Column;
use RealZone22\PenguTables\Table\Header;
use RealZone22\PenguTables\Traits\WithExport;

final class UsersTable extends PenguTable
{
    use WithConfirmation, WithCustomLivewireException, WithExport;

    public function header(): array
    {
        if (auth()->user()->cannot('admin.users.create')) {
            return [];
        }

        return [
            Header::make('<x-button class="flex" wire:navigate link="'.route('admin.users.create').'">'.__('admin::users.buttons.create_user').'</x-button>'),
        ];
    }

    public function query(): Builder
    {
        return User::query();
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.tables.id'), 'id')
                ->searchable()
                ->sortable(),

            Column::make(__('admin::users.avatar'))
                ->format(fn ($col, $row) => '<img src="'.$row->avatar().'" alt="avatar" class="h-8 w-8 rounded-full">')
                ->html(),

            Column::make(__('admin::users.first_name'), 'first_name')
                ->searchable()
                ->sortable(),

            Column::make(__('admin::users.last_name'), 'last_name')
                ->searchable()
                ->sortable(),

            Column::make(__('admin::users.username'), 'username')
                ->searchable()
                ->sortable(),

            Column::make(__('admin::users.email'), 'email')
                ->searchable()
                ->sortable(),

            Column::make(__('admin::users.two_factor_enabled'), 'two_factor_enabled')
                ->format(fn ($col) => $col ? '<i class="icon-check text-success"></i>' : '<i class="icon-x text-danger"></i>')
                ->html()
                ->sortable(),

            Column::make(__('admin::users.force_change_password'), 'force_change_password')
                ->format(fn ($col) => $col ? '<i class="icon-check text-success"></i>' : '<i class="icon-x text-danger"></i>')
                ->html()
                ->sortable(),

            Column::make(__('admin::users.force_activate_two_factor'), 'force_activate_two_factor')
                ->format(fn ($col) => $col ? '<i class="icon-check text-success"></i>' : '<i class="icon-x text-danger"></i>')
                ->html()
                ->sortable(),

            Column::make(__('admin::users.disabled'), 'disabled')
                ->format(fn ($col) => $col ? '<i class="icon-check text-success"></i>' : '<i class="icon-x text-danger"></i>')
                ->html()
                ->sortable(),

            Column::make(__('messages.tables.created_at'), 'created_at')
                ->searchable()
                ->sortable(),

            Column::make(__('messages.tables.updated_at'), 'updated_at')
                ->searchable()
                ->sortable(),

            Column::actions(__('messages.tables.actions'), function ($row) {
                $actions = [];

                if (auth()->user()->can('admin.users.update')) {
                    $actions[] = Action::make('<x-button.floating size="sm" wire:navigate link="'.route('admin.users.update', ['userId' => $row->id]).'"><i class="icon-pen"></i></x-button.floating>');
                }

                if (auth()->user()->can('admin.users.delete') && $row->id !== auth()->id()) {
                    $actions[] = Action::make('<x-button.floating color="danger" size="sm" wire:click="deleteUser(`'.$row->id.'`, false)"><i class="icon-trash"></i></x-button.floating>');
                }

                return $actions;
            }),
        ];
    }

    public function deleteUser($userId, $confirmed = true)
    {
        if (auth()->user()->cannot('admin.users.delete')) {
            return;
        }

        if ($userId === auth()->id()) {
            return;
        }
        if ($confirmed) {
            $user = User::findOrFail($userId);

            $user->delete();

            Notification::make()
                ->title(__('admin::users.delete_user.notifications.user_deleted'))
                ->success()
                ->send();

            $this->redirect(route('admin.users'), true);

            return;
        }

        $this->dialog()
            ->question(__('admin::users.delete_user.title'),
                __('admin::users.delete_user.description'))
            ->icon('icon-triangle-alert')
            ->confirm(__('admin::users.delete_user.buttons.delete_user'), 'danger')
            ->method('deleteUser', $userId)
            ->send();

    }
}
