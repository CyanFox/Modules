<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthProvider extends Model
{
    protected $table = 'oauth_providers';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'color',
        'icon',
        'discovery_url',
        'client_id',
        'client_secret',
        'account_linking',
        'fields',
    ];

    protected $casts = [
        'account_linking' => 'boolean',
        'fields' => 'array',
        'client_secret' => 'encrypted',
    ];
}
