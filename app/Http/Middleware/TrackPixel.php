<?php

namespace App\Http\Middleware;

use App\Http\Traits\TrackerCookie;
use Closure;

class TrackPixel
{
    /**
     * One pixel .png
     */
    const PNG_ENCODED = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=';

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->header('Content-type', 'image/png');

        $response->setContent(
            base64_decode(self::PNG_ENCODED)
        );

        return $response;
    }
}
