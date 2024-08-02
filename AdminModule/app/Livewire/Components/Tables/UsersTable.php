<?php

namespace Modules\AdminModule\Livewire\Components\Tables;

use Exception;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Models\User;
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

final class UsersTable extends PowerGridComponent
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
        return User::query();
    }

    public function header(): array
    {
        if (Auth::user()->cannot('adminmodule.users.create')) {
            return [];
        }
        return [
            Button::add('create')
                ->slot('<x-button wire:navigate href="' . route('admin.users.create') . '">{{ __("adminmodule::users.buttons.create_user") }}</x-button>'),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('avatar', function ($row) {
                return '<img src="' . UserManager::getUser($row)->getAvatarURL() . '" class="rounded-full h-7 w-7">';
            })
            ->add('first_name')
            ->add('last_name')
            ->add('username')
            ->add('email')
            ->add('two_factor_enabled', fn ($row) => $row->two_factor_enabled ? '<i class="icon-check text-green-600"></i>' : '<i class="icon-x text-red-600"></i>')
            ->add('force_change_password', fn ($row) => $row->force_change_password ? '<i class="icon-check text-green-600"></i>' : '<i class="icon-x text-red-600"></i>')
            ->add('force_activate_two_factor', fn ($row) => $row->force_activate_two_factor ? '<i class="icon-check text-green-600"></i>' : '<i class="icon-x text-red-600"></i>')
            ->add('disabled', fn ($row) => $row->disabled ? '<i class="icon-check text-green-600"></i>' : '<i class="icon-x text-red-600"></i>')
            ->add('created_at')
            ->add('created_at_formatted', fn ($row) => $row->created_at->format('d.m.Y H:i'))
            ->add('updated_at')
            ->add('updated_at_formatted', fn ($row) => $row->updated_at->format('d.m.Y H:i'));
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.table.id'), 'id')
                ->searchable()
                ->sortable(),

            Column::make(__('adminmodule::users.avatar'), 'avatar'),

            Column::make(__('adminmodule::users.first_name'), 'first_name')
                ->searchable()
                ->sortable(),

            Column::make(__('adminmodule::users.last_name'), 'last_name')
                ->searchable()
                ->sortable(),

            Column::make(__('adminmodule::users.username'), 'username')
                ->searchable()
                ->sortable(),

            Column::make(__('adminmodule::users.email'), 'email')
                ->searchable()
                ->sortable(),

            Column::make(__('adminmodule::users.two_factor_enabled'), 'two_factor_enabled')
                ->sortable(),

            Column::make(__('adminmodule::users.force_change_password'), 'force_change_password')
                ->sortable(),

            Column::make(__('adminmodule::users.force_activate_two_factor'), 'force_activate_two_factor')
                ->sortable(),

            Column::make(__('adminmodule::users.disabled'), 'disabled')
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

    public function deleteUser($userId, $confirmed = true)
    {
        if (Auth::user()->cannot('adminmodule.users.delete')) {
            return;
        }

        if ($userId === Auth::user()->id) {
            return;
        }
        if ($confirmed) {
            try {
                $user = User::findOrFail($userId)->first();

                $user->delete();

                Notification::make()
                    ->title(__('adminmodule::users.delete_user.notifications.user_deleted'))
                    ->success()
                    ->send();

                $this->redirect(route('admin.users'), navigate: true);
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
            ->error(__('adminmodule::users.delete_user.title'),
                __('adminmodule::users.delete_user.description'))
            ->confirm(__('messages.buttons.delete'), 'deleteUser', [$userId])
            ->cancel()
            ->send();

    }

    public function actions(User $row): array
    {
        return [
            Button::add('update')
                ->slot('<x-button color="blue" wire:navigate href="' . route('admin.users.update', ['userId' => $row->id]) . '" sm><i class="icon-pen"></i></x-button>')
                ->id(),

            Button::add('delete')
                ->slot('<x-button color="red" wire:click="deleteUser(`' . $row->id . '`, false)" sm><i class="icon-trash"></i></x-button>')
                ->id(),
        ];
    }

    public function actionRules(User $row): array
    {
        return [
            Rule::button('delete')
                ->when(fn ($row) => $row->id === Auth::user()->id && Auth::user()->hasPermissionTo('adminmodule.users.delete'))
                ->slot('<x-button color="red" disabled sm><i class="icon-trash"></i></x-button>'),

            Rule::button('delete')
                ->when(fn ($row) => Auth::user()->cannot('adminmodule.users.delete'))
                ->hide(),

            Rule::button('update')
                ->when(fn ($row) => Auth::user()->cannot('adminmodule.users.update'))
                ->hide(),
        ];
    }
}
