<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['visitor_id', 'link_id', 'referer_id'];
}
