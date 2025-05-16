<?php

namespace Modules\Announcements\Livewire\Components\Tables;

use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Announcements\Models\Announcement;
use Modules\Auth\Traits\WithConfirmation;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class AnnouncementsTable extends PowerGridComponent
{
    use WithConfirmation, WithCustomLivewireException, WithExport;

    public string $tableName = 'admin-announcements-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::exportable('announcements')
                ->striped()
                ->type(Exportable::TYPE_CSV, Exportable::TYPE_XLS),
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function header(): array
    {
        if (auth()->user()->cannot('admin.announcements.create')) {
            return [];
        }

        return [
            Button::add('create')
                ->slot(Blade::render('<x-button class="flex" wire:navigate link="'.route('admin.announcements.create').'">'.__('announcements::announcements.buttons.create_announcement').'</x-button>')),
        ];
    }

    public function datasource(): Builder
    {
        return Announcement::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('title')
            ->add('icon_formatted', fn($row) => Blade::render('<i class="icon-' . $row->icon . '"></i>'))
            ->add('color_formatted', fn($row) => Blade::render('<x-badge color="'.$row->color.'">'.__('announcements::announcements.colors.'.$row->color).'</x-badge>'))
            ->add('description', fn($row) => Str::limit($row->description, 30, preserveWords: true))
            ->add('dismissible_formatted', fn($row) => $row->dismissible ? '<i class="icon-check text-success"></i>' : '<i class="icon-x text-danger"></i>')
            ->add('disabled_formatted', fn($row) => $row->disabled ? '<i class="icon-check text-success"></i>' : '<i class="icon-x text-danger"></i>')
            ->add('created_at_formatted', fn ($row) => $row->created_at->format('d.m.Y H:i'))
            ->add('updated_at_formatted', fn ($row) => $row->updated_at->format('d.m.Y H:i'));
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

            Column::make(__('announcements::announcements.icon'), 'icon_formatted', 'icon'),

            Column::make(__('announcements::announcements.color'), 'color_formatted', 'color')
                ->searchable()
                ->sortable(),

            Column::make(__('announcements::announcements.description'), 'description')
                ->searchable(),

            Column::make(__('announcements::announcements.dismissible'), 'dismissible_formatted', 'dismissible')
                ->sortable(),

            Column::make(__('announcements::announcements.disabled'), 'disabled_formatted', 'disabled')
                ->sortable(),

            Column::make(__('messages.tables.created_at'), 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable(),

            Column::make(__('messages.tables.updated_at'), 'updated_at_formatted', 'updated_at')
                ->searchable()
                ->sortable(),

            Column::action(__('messages.tables.actions')),
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

            $announcement->delete();

            Notification::make()
                ->title(__('announcements::announcements.delete_announcement.notifications.announcement_deleted'))
                ->success()
                ->send();

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

    public function actions(Announcement $row): array
    {
        return [
            Button::add('update')
                ->slot(Blade::render('<x-button.floating size="sm" wire:navigate link="'.route('admin.announcements.update', ['announcementId' => $row->id]).'"><i class="icon-pen"></i></x-button.floating>')),

            Button::add('delete')
                ->slot(Blade::render('<x-button.floating color="danger" size="sm" wire:click="deleteAnnouncement(`'.$row->id.'`, false)"><i class="icon-trash"></i></x-button.floating>')),
        ];
    }

    public function actionRules(): array
    {
        return [
            Rule::button('delete')
                ->when(fn ($row) => auth()->user()->cannot('admin.announcements.delete'))
                ->hide(),

            Rule::button('update')
                ->when(fn ($row) => auth()->user()->cannot('admin.announcements.update'))
                ->hide(),
        ];
    }
}
