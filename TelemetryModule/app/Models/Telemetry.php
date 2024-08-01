<?php

namespace Modules\TelemetryModule\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\TelemetryModule\Database\Factories\TelemetryFactory;

class Telemetry extends Model
{

    public $table = 'telemetry';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'instance',
        'ip',
        'modules',
        'os',
        'php',
        'laravel',
        'db',
        'timezone',
        'lang',
        'template_version',
        'project_version',
    ];
}
