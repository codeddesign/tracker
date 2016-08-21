<?php

namespace App\Geolite;

use Illuminate\Database\Eloquent\Model;

class Range extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Specific table.
     *
     * @var string
     */
    protected $table = 'geolite_ranges';

    /**
     * @var array
     */
    protected $fillable = [
        'geoname_id',
        'ip_network',
        'ip_start',
        'ip_end',
    ];
}
