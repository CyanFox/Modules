<?php

namespace Modules\TelemetryModule\Livewire\Components\Tables;

use Exception;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Models\User;
use Modules\TelemetryModule\Models\Telemetry;
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

final class TelemetryTable extends PowerGridComponent
{
    use Interactions, WithExport;

    public function setUp(): array
    {
        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Telemetry::query();
    }

    public function header(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('modules')
            ->add('os')
            ->add('php')
            ->add('laravel')
            ->add('db')
            ->add('timezone')
            ->add('lang')
            ->add('template_version')
            ->add('project_version')
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

            Column::make(__('telemetrymodule::telemetry.modules'), 'modules')
                ->searchable()
                ->sortable(),

            Column::make(__('telemetrymodule::telemetry.os'), 'os')
                ->searchable()
                ->sortable(),

            Column::make(__('telemetrymodule::telemetry.php'), 'php')
                ->searchable()
                ->sortable(),

            Column::make(__('telemetrymodule::telemetry.laravel'), 'laravel')
                ->searchable()
                ->sortable(),

            Column::make(__('telemetrymodule::telemetry.db'), 'db')
                ->searchable()
                ->sortable(),

            Column::make(__('telemetrymodule::telemetry.timezone'), 'timezone')
                ->searchable()
                ->sortable(),

            Column::make(__('telemetrymodule::telemetry.lang'), 'lang')
                ->searchable()
                ->sortable(),

            Column::make(__('telemetrymodule::telemetry.template_version'), 'template_version')
                ->searchable()
                ->sortable(),

            Column::make(__('telemetrymodule::telemetry.project_version'), 'project_version')
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
        ];
    }

    public function filters(): array
    {
        return [];
    }
}
