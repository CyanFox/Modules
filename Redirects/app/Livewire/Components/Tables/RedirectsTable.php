<?php

namespace Modules\Redirects\Livewire\Components\Tables;

use App\Traits\WithCustomLivewireException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Masmerise\Toaster\Toaster;
use Modules\Auth\Traits\WithConfirmation;
use Modules\Redirects\Actions\DeleteRedirectAction;
use Modules\Redirects\Models\Redirect;
use RealZone22\PenguTables\Livewire\PenguTable;
use RealZone22\PenguTables\Table\Action;
use RealZone22\PenguTables\Table\Column;
use RealZone22\PenguTables\Table\Header;
use RealZone22\PenguTables\Traits\WithExport;

final class RedirectsTable extends PenguTable
{
    use WithConfirmation, WithCustomLivewireException, WithExport;

    public function header(): array
    {
        if (auth()->user()->cannot('redirects.create')) {
            return [];
        }

        return [
            Header::make('<x-button class="flex" wire:navigate link="' . route('redirects.create') . '">' . __('redirects::redirects.buttons.create_redirect') . '</x-button>'),
        ];
    }

    public function query(): Builder
    {
        $user = auth()->user();

        if ($user->can('redirects.view.all')) {
            return Redirect::query();
        }

        return Redirect::whereHas('access', function ($q) use ($user) {
            $q->where(function ($q2) use ($user) {
                $q2->where('user_id', $user->id)
                    ->orWhereIn('role_id', $user->roles->pluck('id')->toArray());
            })
                ->where('can_update', true);
        })
            ->orWhere('created_by', $user->id);
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.tables.id'), 'id')
                ->searchable()
                ->sortable(),

            Column::make(__('redirects::redirects.user'), 'created_by')
                ->format(fn($col, $row) => $row->createdBy?->username ?? '')
                ->searchable()
                ->sortable(),

            Column::make(__('redirects::redirects.from'), 'from')
                ->format(fn($col) => Blade::render('<x-link href="' . $col . '" target="_blank" rel="noopener">' . $col . '</x-link>'))
                ->html()
                ->searchable()
                ->sortable(),

            Column::make(__('redirects::redirects.to'), 'to')
                ->format(fn($col) => Blade::render('<x-link href="' . $col . '" target="_blank" rel="noopener">' . $col . '</x-link>'))
                ->html()
                ->searchable()
                ->sortable(),

            Column::make(__('redirects::redirects.status_code'), 'status_code')
                ->searchable()
                ->sortable(),

            Column::make(__('redirects::redirects.active'), 'active')
                ->format(fn($col) => $col ? '<i class="icon-check text-success"></i>' : '<i class="icon-x text-danger"></i>')
                ->html()
                ->searchable()
                ->sortable(),

            Column::make(__('redirects::redirects.hits'), 'hits')
                ->searchable()
                ->sortable(),

            Column::make(__('redirects::redirects.last_accessed_at'), 'last_accessed_at')
                ->format(fn($col) => formatDateTime($col))
                ->searchable()
                ->sortable(),

            Column::make(__('messages.tables.created_at'), 'created_at')
                ->format(fn($col) => formatDateTime($col))
                ->searchable()
                ->sortable(),

            Column::make(__('messages.tables.updated_at'), 'updated_at')
                ->format(fn($col) => formatDateTime($col))
                ->searchable()
                ->sortable(),

            Column::actions(__('messages.tables.actions'), function ($row) {
                $actions = [];

                if (auth()->user()->can('redirects.update')) {
                    $actions[] = Action::make('<x-button.floating size="sm" wire:navigate link="' . route('redirects.update', ['redirectId' => $row->id]) . '"><i class="icon-pen"></i></x-button.floating>');
                }

                if (auth()->user()->can('redirects.delete')) {
                    $actions[] = Action::make('<x-button.floating color="danger" size="sm" wire:click="deleteRedirect(`' . $row->id . '`, false)"><i class="icon-trash"></i></x-button.floating>');
                }

                return $actions;
            }),
        ];
    }

    public function deleteRedirect($redirectId, $confirmed = true)
    {
        if (auth()->user()->cannot('redirects.delete')) {
            return;
        }

        if ($confirmed) {
            $redirect = Redirect::find($redirectId);

            if (!DeleteRedirectAction::run($redirect)) {
                Toaster::error(__('messages.notifications.something_went_wrong'));

                return;
            }

            Toaster::success(__('redirects::redirects.delete_redirect.notifications.redirect_deleted'));

            $this->redirect(route('redirects'), true);

            return;
        }

        $this->dialog()
            ->question(__('redirects::redirects.delete_redirect.title'),
                __('redirects::redirects.delete_redirect.description'))
            ->icon('icon-triangle-alert')
            ->confirm(__('redirects::redirects.delete_redirect.buttons.delete_redirect'), 'danger')
            ->method('deleteRedirect', $redirectId)
            ->send();

    }
}
