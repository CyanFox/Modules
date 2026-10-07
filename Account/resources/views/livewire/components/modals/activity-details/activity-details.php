<?php

use RealZone22\PenguBlade\ModalComponent;
use Spatie\Activitylog\Models\Activity;

new class extends ModalComponent {
    public $activityId;

    public $attributeChanges;

    public $newValues;

    public $oldValues;

    public function mount()
    {
        $activityLog = Activity::where('id', $this->activityId)
            ->where('causer_id', auth()->id())
            ->first();

        if (!$activityLog) {
            $this->closeModal();
            return;
        }

        $attributeChanges = json_decode($activityLog->attribute_changes, true) ?? [];
        $this->attributeChanges = $attributeChanges;

        $this->oldValues = $attributeChanges['old'] ?? [];
        $this->newValues = $attributeChanges['attributes'] ?? [];

    }
};
