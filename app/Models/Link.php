<?php

namespace App\Models;

use App\Utilities\Url;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['domain_id', 'is_secure', 'is_www', 'path'];

    /**
     * Get a URL instance of the given link.
     *
     * @param string $url
     *
     * @return Url
     */
    public static function fromUrl($url)
    {
        return new Url($url);
    }
}
