<?php

namespace Modules\AuthModule\Livewire\Components\Tables;

use App\Models\Session;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use Modules\AuthModule\Facades\UserManager;
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

final class SessionsTable extends PowerGridComponent
{
    use Interactions, WithExport;

    public function setUp(): array
    {
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

    public function header(): array
    {
        return [
            Button::add('logoutAll')
                ->slot('<x-button wire:click="logoutOtherDevices" color="red">{{ __("authmodule::account.sessions.buttons.logout_other_devices") }}</x-button>'),
        ];
    }

    public function datasource(): Builder
    {
        return Session::query()->where('user_id', Auth::user()->id);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('device', function (Session $model) {
                $agent = new Agent();
                $agent->setUserAgent($model->user_agent);

                if ($agent->isDesktop()) {
                    return '<i class="icon-monitor"></i> ' . __('authmodule::account.sessions.device_types.desktop');
                } elseif ($agent->isPhone()) {
                    return $agent->isPhone() ? '<i class="icon-smartphone"></i> ' . __('authmodule::account.sessions.device_types.phone') :
                        '<i class="icon-tablet"></i> ' . __('authmodule::account.sessions.device_types.tablet');
                } else {
                    return '<i class="icon-monitor-smartphone text-lg"></i> ' . __('authmodule::account.sessions.device_types.unknown');
                }
            })
            ->add('last_activity')
            ->add('last_activity', fn (Session $model) => Carbon::parse($model->last_activity)->format('d.m.Y H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make(__('authmodule::account.sessions.table.ip_address'), 'ip_address')
                ->searchable(),

            Column::make(__('authmodule::account.sessions.table.user_agent'), 'user_agent')
                ->searchable(),

            Column::make(__('authmodule::account.sessions.table.device'), 'device'),

            Column::make(__('authmodule::account.sessions.table.last_activity'), 'last_activity')
                ->searchable(),

            Column::action(__('messages.table.actions')),
        ];
    }

    public function logout($sessionId): void
    {
        UserManager::getUser(Auth::user())->getSessionManager()->deleteSession($sessionId);

        Notification::make()
            ->title(__('authmodule::account.sessions.notifications.session_logged_out'))
            ->success()
            ->send();

        $this->redirect(route('account.profile', ['tab' => 'Sessions']), navigate: true);
    }

    public function logoutOtherDevices($confirmed = false)
    {
        if ($confirmed) {
            UserManager::getUser(Auth::user())->getSessionManager()->revokeOtherSessions();

            Notification::make()
                ->title(__('authmodule::account.sessions.notifications.other_devices_logged_out'))
                ->success()
                ->send();

            $this->redirect(route('account.profile', ['tab' => 'Sessions']), navigate: true);

            return;
        }

        $this->dialog()
            ->warning(__('authmodule::account.sessions.dialogs.logout_other_devices.title'),
                __('authmodule::account.sessions.dialogs.logout_other_devices.description'))
            ->confirm(__('authmodule::account.sessions.dialogs.logout_other_devices.buttons.logout_other_devices'), 'logoutOtherDevices', [true])
            ->cancel()
            ->send();

    }

    public function actions(Session $row): array
    {
        return [
            Button::add('logout')
                ->slot('<x-button color="red" wire:click="logout(`' . $row->id . '`)" sm><i class="icon-log-out"></i></x-button>')
                ->id(),
        ];
    }

    public function actionRules(Session $row): array
    {
        return [
            Rule::button('logout')
                ->when(fn ($row) => $row->id === session()->getId())
                ->slot('<x-badge text="'. __('authmodule::account.sessions.current_session') .'" sm color="green" />'),
        ];
    }
}
