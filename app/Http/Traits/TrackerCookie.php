<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Cookie;

trait TrackerCookie
{
    /**
     * Retrieve or set the cookie value.
     * If value is missing it will try to retrieve the value.
     *
     * @param null|string $value
     *
     * @return mixed
     */
    public function cookie($value = null)
    {
        $name = env('TRACKER_COOKIE_NAME');
        $domain = env('TRACKER_COOKIE_DOMAIN');

        if (is_null($value)) {
            return Cookie::get($name);
        }

        return Cookie::forever($name, $value, '/', $domain, false, false);
    }
}
