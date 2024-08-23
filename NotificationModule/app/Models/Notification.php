<?php

namespace Modules\NotificationModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'notifications';

    public $timestamps = true;

    protected $fillable = [
        'title',
        'message',
        'type',
        'icon',
        'dismissible',
        'location',
    ];

    public function dismissedNotification(): BelongsTo
    {
        return $this->belongsTo(DismissedNotification::class, 'id', 'notification_id');
    }
}
