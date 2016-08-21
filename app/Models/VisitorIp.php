<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorIp extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['ip', 'visitor_id', 'geoname_id'];
}
