<?php

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use RealZone22\PenguTables\Livewire\PenguTable;
use RealZone22\PenguTables\Table\Action;
use RealZone22\PenguTables\Table\Column;
use RealZone22\PenguTables\Table\ExportTypes;
use RealZone22\PenguTables\Table\Options;
use RealZone22\PenguTables\Traits\WithExport;
use Spatie\Activitylog\Models\Activity;

new class extends PenguTable {
    use WithExport;

    protected function setupOptions(): Options
    {
        return parent::setupOptions()->withSearch()->withExport(true, ExportTypes::CSV_ALL, ExportTypes::XLSX_ALL, ExportTypes::CSV_SELECTED, ExportTypes::XLSX_SELECTED);
    }

    public function query(): Builder
    {
        return Activity::query()->orderByDesc('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.tables.id'), 'id')
                ->sortable(),
            Column::make(__('admin::activity.description'), 'description')
                ->searchable(),
            Column::make(__('admin::activity.caused_by'), 'causer_id')
                ->format(fn($col, $row) => $row->causer ? $row->causer->getDisplayName() : __('admin::activity.unknown')),
            Column::make(__('admin::activity.subject'), 'subject_id')
                ->format(fn($col, $row) => $row->subject ? $row->subject->getDisplayName() : __('admin::activity.unknown')),
            Column::make(__('admin::activity.performed_at'), 'created_at')
                ->format(fn($value) => Blade::render('<x-core::human-date date="' . $value . '"/>'))
                ->sortable()
                ->html(),
            Column::actions(__('messages.tables.actions'), function ($row) {
                return [
                    Action::make('<x-button.floating size="sm" wire:click="$dispatch(`openModal`, { modalComponent: `admin::components.modals.activity-details`, arguments: { activityId: `' . $row->id . '` }})" loading="openModal" tooltip="' . __('admin::activity.tooltips.show_details') . '"><i class="icon-eye"></i></x-button.floating>')
                ];
            })
        ];
    }

    public function render(): View
    {
        return $this->view()
            ->layout('admin::layouts.app', ['breadcrumbs' => [['label' => __('admin::admin.admin'), 'url' => route('admin.dashboard')], ['label' => __('admin::activity.tab_title'), 'last' => true]]])
            ->title(__('admin::activity.tab_title'));
    }
};
