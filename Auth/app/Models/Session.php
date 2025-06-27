<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;

// use Modules\Auth\Database\Factories\SessionFactory;

class Session extends Model
{
    public $table = 'sessions';

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
    ];
}
