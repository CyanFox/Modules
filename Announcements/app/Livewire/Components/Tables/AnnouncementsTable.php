<?php

namespace Modules\Announcements\Livewire\Components\Tables;

use App\Traits\WithCustomLivewireException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Masmerise\Toaster\Toaster;
use Modules\Announcements\app\Actions\DeleteAnnouncementAction;
use Modules\Announcements\Models\Announcement;
use Modules\Auth\Traits\WithConfirmation;
use RealZone22\PenguTables\Livewire\PenguTable;
use RealZone22\PenguTables\Table\Action;
use RealZone22\PenguTables\Table\Column;
use RealZone22\PenguTables\Table\Header;
use RealZone22\PenguTables\Traits\WithExport;

final class AnnouncementsTable extends PenguTable
{
    use WithConfirmation, WithCustomLivewireException, WithExport;

    public function header(): array
    {
        if (auth()->user()->cannot('admin.announcements.create')) {
            return [];
        }

        return [
            Header::make('<x-button class="flex" wire:navigate link="'.route('admin.announcements.create').'">'.__('announcements::announcements.buttons.create_announcement').'</x-button>'),
        ];
    }

    public function query(): Builder
    {
        return Announcement::query();
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.tables.id'), 'id')
                ->searchable()
                ->sortable(),

            Column::make(__('announcements::announcements.title'), 'title')
                ->searchable()
                ->sortable(),

            Column::make(__('announcements::announcements.icon'), 'icon')
                ->format(fn ($col) => '<i class="icon-'.$col.'"></i>')
                ->html(),

            Column::make(__('announcements::announcements.color'), 'color')
                ->format(fn ($col) => Blade::render('<x-badge color="'.$col.'">'.__('announcements::announcements.colors.'.$col).'</x-badge>'))
                ->html()
                ->searchable()
                ->sortable(),

            Column::make(__('announcements::announcements.description'), 'description')
                ->format(fn ($col) => Str::limit($col, 30, preserveWords: true))
                ->html()
                ->searchable(),

            Column::make(__('announcements::announcements.dismissible'), 'dismissible')
                ->format(fn ($col) => $col ? '<i class="icon-check text-success"></i>' : '<i class="icon-x text-danger"></i>')
                ->html()
                ->sortable(),

            Column::make(__('announcements::announcements.disabled'), 'disabled')
                ->format(fn ($col) => $col ? '<i class="icon-check text-success"></i>' : '<i class="icon-x text-danger"></i>')
                ->html()
                ->sortable(),

            Column::make(__('messages.tables.created_at'), 'created_at')
                ->searchable()
                ->sortable(),

            Column::make(__('messages.tables.updated_at'), 'updated_at')
                ->searchable()
                ->sortable(),

            Column::actions(__('messages.tables.actions'), function ($row) {
                $actions = [];

                if (auth()->user()->can('admin.announcements.update')) {
                    $actions[] = Action::make('<x-button.floating size="sm" wire:navigate link="'.route('admin.announcements.update', ['announcementId' => $row->id]).'"><i class="icon-pen"></i></x-button.floating>');
                }

                if (auth()->user()->can('admin.announcements.delete')) {
                    $actions[] = Action::make('<x-button.floating color="danger" size="sm" wire:click="deleteAnnouncement(`'.$row->id.'`, false)"><i class="icon-trash"></i></x-button.floating>');
                }

                return $actions;
            }),
        ];
    }

    public function deleteAnnouncement($announcementId, $confirmed = true)
    {
        if (auth()->user()->cannot('admin.announcements.delete')) {
            return;
        }

        if ($confirmed) {
            $announcement = Announcement::find($announcementId);

            Storage::disk('local')->deleteDirectory('announcements/'.$announcement->id);

            if (! DeleteAnnouncementAction::run($announcement)) {
                Toaster::error(__('messages.notifications.something_went_wrong'));

                return;
            }

            Toaster::success(__('announcements::announcements.delete_announcement.notifications.announcement_deleted'));

            $this->redirect(route('admin.announcements'), true);

            return;
        }

        $this->dialog()
            ->question(__('announcements::announcements.delete_announcement.title'),
                __('announcements::announcements.delete_announcement.description'))
            ->icon('icon-triangle-alert')
            ->confirm(__('announcements::announcements.delete_announcement.buttons.delete_announcement'), 'danger')
            ->method('deleteAnnouncement', $announcementId)
            ->send();

    }
}
