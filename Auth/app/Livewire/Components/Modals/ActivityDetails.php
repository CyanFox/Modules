<?php

namespace Modules\Auth\Livewire\Components\Modals;

use App\Livewire\CFModalComponent;
use App\Traits\WithCustomLivewireException;
use Spatie\Activitylog\Models\Activity;

class ActivityDetails extends CFModalComponent
{
    use WithCustomLivewireException;

    public $activityLogId;

    public $properties;

    public $newValues;

    public $oldValues;

    public function mount()
    {
        if (auth()->user()->can('auth.activity.view_all')) {
            $activityLog = Activity::findOrFail($this->activityLogId);
        } else {
            $activityLog = Activity::where('id', $this->activityLogId)
                ->where('causer_id', auth()->id())
                ->first();
        }

        if (! $activityLog) {
            return;
        }

        $properties = json_decode($activityLog->properties, true) ?? [];
        $this->properties = $properties;

        $this->oldValues = $properties['old'] ?? [];
        $this->newValues = $properties['attributes'] ?? [];
    }

    public function render()
    {
        return view('auth::livewire.components.modals.activity-details');
    }
}
