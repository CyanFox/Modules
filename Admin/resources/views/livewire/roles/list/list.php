<?php

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Models\Role;
use Modules\Core\Traits\WithConfirmation;
use RealZone22\PenguTables\Livewire\PenguTable;
use RealZone22\PenguTables\Table\Action;
use RealZone22\PenguTables\Table\Column;
use RealZone22\PenguTables\Table\ExportTypes;
use RealZone22\PenguTables\Table\Header;
use RealZone22\PenguTables\Table\Options;
use RealZone22\PenguTables\Traits\WithExport;

new class extends PenguTable {
    use WithExport, WithConfirmation;

    protected function setupOptions(): Options
    {
        return parent::setupOptions()->withSearch()->withExport(true, ExportTypes::CSV_ALL, ExportTypes::XLSX_ALL, ExportTypes::CSV_SELECTED, ExportTypes::XLSX_SELECTED);
    }

    public function query(): Builder
    {
        return Role::query();
    }

    public function header(): array
    {
        if (auth()->user()->cannot('admin.roles.create')) {
            return [];
        }

        return [
            Header::make('<x-button link="' . route('admin.roles.create') . '" wire:navigate>' . __('admin::roles.buttons.create') . '</x-button>')
        ];
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.tables.id'), 'id')
                ->sortable(),
            Column::make(__('admin::roles.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('admin::roles.guard_name'), 'guard_name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.tables.created_at'), 'created_at')
                ->format(fn($col) => Blade::render('<x-core::human-date date="' . $col . '"/>'))
                ->html()
                ->sortable(),
            Column::make(__('messages.tables.updated_at'), 'updated_at')
                ->format(fn($col) => Blade::render('<x-core::human-date date="' . $col . '"/>'))
                ->html()
                ->sortable(),
            Column::actions(__('messages.tables.actions'), function ($row) {
                $actions = [];

                if (auth()->user()->can('admin.roles.update')) {
                    $actions[] = Action::make('<x-button.floating size="sm" link="' . route('admin.roles.update', ['roleId' => $row->id]) . '" tooltip="' . __('admin::roles.tooltips.update') . '" wire:navigate><i class="icon-pen"></i></x-button.floating>');
                }

                if (auth()->user()->can('admin.roles.delete') && $row->id !== auth()->id()) {
                    $actions[] = Action::make('<x-button.floating color="danger" size="sm" wire:click="deleteRole(`' . $row->id . '`)" loading="deleteRole(`' . $row->id . '`)" tooltip="' . __('admin::roles.tooltips.delete') . '"><i class="icon-trash"></i></x-button.floating>');
                }

                return $actions;
            })
        ];
    }

    public function deleteRole($roleId, $confirmed = false)
    {
        if (auth()->user()->cannot('admin.roles.delete')) {
            return;
        }

        if ($confirmed) {
            $role = Role::find($roleId);

            if (!$role?->delete()) {
                Toaster::error(__('messages.notifications.something_went_wrong'));
                return;
            }

            Toaster::success(__('admin::roles.delete.notifications.deleted'));

            $this->redirect(url()->previous(), true);

            return;
        }

        $this->dialog()
            ->question(__('admin::roles.delete.title'),
                __('admin::roles.delete.description'))
            ->icon('icon-triangle-alert')
            ->confirm(__('admin::roles.delete.buttons.delete'), 'danger')
            ->method('deleteRole', $roleId, true)
            ->send();

    }

    public function render(): View
    {
        return $this->view()
            ->layout('admin::layouts.app', ['breadcrumbs' => [['label' => __('admin::admin.admin'), 'url' => route('admin.dashboard')], ['label' => __('admin::roles.tab_title'), 'last' => true]]])
            ->title(__('admin::roles.tab_title'));
    }
};
