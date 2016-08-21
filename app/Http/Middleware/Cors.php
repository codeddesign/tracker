<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * List of headers that are sent for CORS routes.
     *
     * @var array
     */
    protected $headers = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'POST, GET',
        'Access-Control-Allow-Headers' => 'Content-Type, X-Requested-With, Accept',
    ];

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
        foreach ($this->headers as $key => $value) {
            $response->header($key, $value);
        }

        return $response;
    }
}
