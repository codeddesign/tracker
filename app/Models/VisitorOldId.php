<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitorOldId extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['new_id', 'old_id'];
}
