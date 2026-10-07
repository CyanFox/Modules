<?php

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use RealZone22\PenguTables\Livewire\PenguTable;
use RealZone22\PenguTables\Table\Action;
use RealZone22\PenguTables\Table\Column;
use RealZone22\PenguTables\Table\Options;
use Spatie\Activitylog\Models\Activity;

new class extends PenguTable {
    protected function setupOptions(): Options
    {
        return parent::setupOptions()->withSearch(false)->withExport(false)->withBulkActions(false);
    }

    public function query(): Builder
    {
        return Activity::query()->where([
            'subject_id' => auth()->id(),
            'subject_type' => userModel()::class,
        ])->orderByDesc('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.tables.id'), 'id'),
            Column::make(__('account::activity.description'), 'description'),
            Column::make(__('account::activity.caused_by'), 'causer_id')
                ->format(fn($col, $row) => $row->causer ? $row->causer->getDisplayName() : __('account::activity.unknown')),
            Column::make(__('account::activity.subject'), 'subject_id')
                ->format(fn($col, $row) => $row->subject ? $row->subject->getDisplayName() : __('account::activity.unknown')),
            Column::make(__('account::activity.performed_at'), 'created_at')
                ->format(fn($value) => Blade::render('<x-core::human-date date="' . $value . '"/>'))
                ->html(),
            Column::actions(__('messages.tables.actions'), function ($row) {
                return [
                    Action::make('<x-button.floating size="sm" wire:click="$dispatch(`openModal`, { modalComponent: `account::components.modals.activity-details`, arguments: { activityId: `' . $row->id . '` }})" loading="openModal" tooltip="' . __('account::activity.tooltips.show_details') . '"><i class="icon-eye"></i></x-button.floating>')
                ];
            })
        ];
    }


    public function render(): View
    {
        return $this->view()
            ->layout('dashboard::layouts.app', ['breadcrumbs' => [['label' => __('account::account.account'), 'url' => route('account.profile')], ['label' => __('account::account.activity'), 'last' => true]]])
            ->title(__('account::account.activity'));
    }
};
