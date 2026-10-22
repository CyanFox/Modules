<?php

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
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
        return userModel()->query();
    }

    public function header(): array
    {
        if (auth()->user()->cannot('admin.users.create')) {
            return [];
        }

        return [
            Header::make('<x-button link="' . route('admin.users.create') . '" wire:navigate>' . __('admin::users.buttons.create') . '</x-button>')
        ];
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.tables.id'), 'id')
                ->sortable(),
            Column::make(__('admin::users.avatar'))
                ->format(fn($col, $row) => '<img src="' . e($row->getAvatar()) . '" class="rounded-full size-8">')
                ->html(),
            Column::make(__('admin::users.first_name'), 'first_name')
                ->sortable()
                ->searchable(),
            Column::make(__('admin::users.last_name'), 'last_name')
                ->sortable()
                ->searchable(),
            Column::make(__('admin::users.username'), 'username')
                ->sortable()
                ->searchable(),
            Column::make(__('admin::users.email'), 'email')
                ->sortable()
                ->searchable(),
            Column::make(__('admin::users.mfa_enabled'))
                ->format(fn($col, $row) => userSettings('auth.mfa.enabled', $row->id) ? '<i class="icon-check text-success"></i>' : '<i class="icon-x text-danger"></i>')
                ->html(),
            Column::make(__('admin::users.disabled'), 'disabled')
                ->format(fn($col) => $col ? '<i class="icon-check text-success"></i>' : '<i class="icon-x text-danger"></i>')
                ->sortable()
                ->html(),
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

                if (auth()->user()->can('admin.users.update')) {
                    $actions[] = Action::make('<x-button.floating size="sm" link="' . route('admin.users.update', ['userId' => $row->id]) . '" tooltip="' . __('admin::users.tooltips.update') . '" wire:navigate><i class="icon-pen"></i></x-button.floating>');
                }

                if (auth()->user()->can('admin.users.delete') && $row->id !== auth()->id()) {
                    $actions[] = Action::make('<x-button.floating color="danger" size="sm" wire:click="deleteUser(`' . $row->id . '`)" loading="deleteUser(`' . $row->id . '`)" tooltip="' . __('admin::users.tooltips.delete') . '"><i class="icon-trash"></i></x-button.floating>');
                }

                return $actions;
            })
        ];
    }

    public function deleteUser($userId, $confirmed = false)
    {
        if (auth()->user()->cannot('admin.users.delete')) {
            return;
        }

        if ($userId === auth()->id()) {
            return;
        }

        if ($confirmed) {
            $user = userModel()->find($userId);

            if (!$user?->delete()) {
                Toaster::error(__('messages.notifications.something_went_wrong'));
                return;
            }

            Toaster::success(__('admin::users.delete.notifications.deleted'));

            $this->redirect(url()->previous(), true);

            return;
        }

        $this->dialog()
            ->question(__('admin::users.delete.title'),
                __('admin::users.delete.description'))
            ->icon('icon-triangle-alert')
            ->confirm(__('admin::users.delete.buttons.delete'), 'danger')
            ->method('deleteUser', $userId, true)
            ->send();

    }

    public function render(): View
    {
        return $this->view()
            ->layout('admin::layouts.app', ['breadcrumbs' => [['label' => __('admin::admin.admin'), 'url' => route('admin.dashboard')], ['label' => __('admin::users.tab_title'), 'last' => true]]])
            ->title(__('admin::users.tab_title'));
    }
};
