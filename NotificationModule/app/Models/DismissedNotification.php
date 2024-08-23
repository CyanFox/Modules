<?php

namespace Modules\NotificationModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\AuthModule\Models\User;

class DismissedNotification extends Model
{
    protected $table = 'dismissed_notifications';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'notification_id',
    ];

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
