<?php

namespace App\Models;

use App\Utilities\Url;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = ['domain_id', 'is_secure', 'is_www', 'path'];

    public static function fromUrl($url)
    {
        return new Url($url);
    }
}
