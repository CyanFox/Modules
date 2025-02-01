<?php

namespace Modules\Admin\Livewire\Components\Tables;

use App\Traits\WithConfirmation;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Modules\Auth\Models\User;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class UsersTable extends PowerGridComponent
{
    use WithCustomLivewireException, WithConfirmation;

    public string $tableName = 'admin-users-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('users')
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
        if (auth()->user()->cannot('admin.users.create')) {
            return [];
        }

        return [
            Button::add('create')
                ->slot(Blade::render('<x-button class="flex" wire:navigate link="' . route('admin.users.create') . '">' . __('admin::users.buttons.create_user') . '</x-button>')),
        ];
    }

    public function datasource(): Builder
    {
        return User::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('avatar', fn($row) => '<img src="' . $row->avatar() . '" alt="avatar" class="h-8 w-8 rounded-full">')
            ->add('first_name')
            ->add('last_name')
            ->add('username')
            ->add('email')
            ->add('two_factor_enabled_formatted', fn($row) => $row->two_factor_enabled ? '<i class="icon-check text-green-600"></i>' : '<i class="icon-x text-red-600"></i>')
            ->add('force_change_password_formatted', fn($row) => $row->force_change_password ? '<i class="icon-check text-green-600"></i>' : '<i class="icon-x text-red-600"></i>')
            ->add('force_activate_two_factor_formatted', fn($row) => $row->force_activate_two_factor ? '<i class="icon-check text-green-600"></i>' : '<i class="icon-x text-red-600"></i>')
            ->add('disabled_formatted', fn($row) => $row->disabled ? '<i class="icon-check text-green-600"></i>' : '<i class="icon-x text-red-600"></i>')
            ->add('created_at_formatted', fn($row) => $row->created_at->format('d.m.Y H:i'))
            ->add('updated_at_formatted', fn($row) => $row->updated_at->format('d.m.Y H:i'));
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.tables.id'), 'id')
                ->searchable()
                ->sortable(),

            Column::make(__('admin::users.avatar'), 'avatar'),

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

            Column::make(__('admin::users.two_factor_enabled'), 'two_factor_enabled_formatted', 'two_factor_enabled')
                ->sortable(),

            Column::make(__('admin::users.force_change_password'), 'force_change_password_formatted', 'force_change_password')
                ->sortable(),

            Column::make(__('admin::users.force_activate_two_factor'), 'force_activate_two_factor_formatted', 'force_activate_two_factor')
                ->sortable(),

            Column::make(__('admin::users.disabled'), 'disabled_formatted', 'disabled')
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

    public function deleteUser($userId, $confirmed = true)
    {
        if (auth()->user()->cannot('admin.users.delete')) {
            return;
        }

        if ($userId === auth()->id()) {
            return;
        }
        if ($confirmed) {
            $user = User::findOrFail($userId)->first();

            $user->delete();

            Notification::make()
                ->title(__('admin::users.delete_user.notifications.user_deleted'))
                ->success()
                ->send();

            $this->redirect(route('admin.users'), navigate: true);

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

    public function actions(User $row): array
    {
        return [
            Button::add('update')
                ->slot(Blade::render('<x-button class="px-2 py-1 flex" wire:navigate link="' . route('admin.users.update', ['userId' => $row->id]) . '"><i class="icon-pen"></i></x-button>')),

            Button::add('delete')
                ->slot(Blade::render('<x-button color="danger" class="px-2 py-1 flex" wire:click="deleteUser(`' . $row->id . '`, false)"><i class="icon-trash"></i></x-button>')),
        ];
    }

    public function actionRules(): array
    {
        return [
            Rule::button('delete')
                ->when(fn($row) => $row->id == auth()->id())
                ->hide(),

            Rule::button('delete')
                ->when(fn($row) => auth()->user()->cannot('admin.users.delete'))
                ->hide(),

            Rule::button('update')
                ->when(fn($row) => auth()->user()->cannot('admin.users.update'))
                ->hide(),
        ];
    }
}
