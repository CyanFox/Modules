<?php

namespace Modules\Admin\Livewire\Components\Tables;

use App\Traits\WithCustomLivewireException;
use Illuminate\Database\Eloquent\Builder;
use RealZone22\PenguTables\Livewire\PenguTable;
use RealZone22\PenguTables\Table\Action;
use RealZone22\PenguTables\Table\Column;
use RealZone22\PenguTables\Traits\WithExport;
use Spatie\Activitylog\Models\Activity;

class ActivityLogTable extends PenguTable
{
    use WithCustomLivewireException, WithExport;

    public function query(): Builder
    {
        return Activity::query()->orderByDesc('id');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.tables.id'), 'id')
                ->searchable()
                ->sortable(),

            Column::make(__('admin::activity.description'), 'description')
                ->searchable()
                ->sortable(),

            Column::make(__('admin::activity.caused_by'), 'causer')
                ->format(fn ($col) => $col ? $col->displayName() : __('admin::activity.unknown_causer'))
                ->searchable()
                ->sortable(),

            Column::make(__('admin::activity.subject'), 'subject')
                ->format(fn ($col) => $col ? $col->displayName() : __('admin::activity.unknown_causer'))
                ->searchable()
                ->sortable(),

            Column::make(__('admin::activity.performed_at'), 'created_at')
                ->format(fn ($col) => $col->format('d.m.Y H:i:s'))
                ->searchable()
                ->sortable(),

            Column::actions(__('messages.tables.actions'), function ($row) {
                if (blank($row->properties)) {
                    return [];
                }

                return [
                    Action::make('<x-button.floating wire:click="$dispatch(`openModal`, {component: `auth::components.modals.activity-details`, arguments: {activityLogId: '.$row->id.' }})" size="sm" color="info"><i class="icon-eye"></i></x-button.floating>'),
                ];
            }),
        ];
    }
}
