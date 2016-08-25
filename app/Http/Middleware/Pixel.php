<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use App\Utilities\UniqueCode;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class Pixel
{
    /**
     * Name of the cookie.
     */
    const COOKIE_NAME = 'sttk_u';

    /**
     * Expiration for cookie and cache.
     * e.g.: 2628000 = 5 years.
     */
    const COOKIE_EXPIRATION = 2628000;

    /**
     * @var array
     */
    protected $defaultHeaders = [
        'Content-type' => 'image/png',
        'Access-Control-Allow-Credentials' => 'true',
    ];

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var \Illuminate\Http\Response
     */
    protected $response;

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
        $this->setRequest($request)
            ->addUniqueId();

        $this->setResponse($next)
            ->addDefaultHeaders()
            ->addOrigin()
            ->addCache()
            ->addImage()
            ->addCookie();

        return $this->response;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return $this
     */
    protected function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Set value of the uniqueId and
     * associate it with $request.
     *
     * @return $this
     */
    protected function addUniqueId()
    {
        if ($cookie = $this->cookie()) {
            $this->request->uniqueId = $cookie;

            return $this;
        }

        $u = $this->request->get('u');
        if ((new UniqueCode($u))->isValid() && Visitor::byUniqueId($u)) {
            $this->request->uniqueId = $u;

            return $this;
        }

        $this->request->uniqueId = Visitor::add();

        return $this;
    }

    /**
     * @param Closure $next
     *
     * @return $this
     */
    protected function setResponse(Closure $next)
    {
        $this->response = $next($this->request);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addDefaultHeaders()
    {
        foreach ($this->defaultHeaders as $key => $value) {
            $this->response->header($key, $value);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function addOrigin()
    {
        $this->response->header('Access-Control-Allow-Origin', $this->origin());

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCache()
    {
        if (!trim($this->request->get('u'))) {
            $this->response->header('Cache-Control', 'max-age='.self::COOKIE_EXPIRATION);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function addImage()
    {
        $this->response->setContent($this->image());

        return $this;
    }

    /**
     * @return this
     */
    protected function addCookie()
    {
        $this->response->withCookie($this->cookie($this->request->uniqueId));

        return $this;
    }

    /**
     * Retrieve or set the cookie value.
     * If $value is missing it will try to retrieve the value.
     *
     * @param null|string $value
     *
     * @return mixed
     */
    private function cookie($value = null)
    {
        if (is_null($value)) {
            return $this->request->cookie(self::COOKIE_NAME);
        }

        return Cookie::make($name = self::COOKIE_NAME, $value, self::COOKIE_EXPIRATION);
    }

    /**
     * Returns right path for the site
     * that's making the request.
     *
     * @return string
     */
    private function origin()
    {
        $referer = $this->request->server('HTTP_REFERER');
        if (!$referer) {
            return '*';
        }

        $parts = parse_url($referer);

        return sprintf('%s://%s', $parts['scheme'], $parts['host'].(isset($parts['port']) ? ':'.$parts['port'] : ''));
    }

    /**
     * Returns an encoded .png containg the value of the cookie.
     *
     * @return mixed
     */
    private function image()
    {
        $image = imagecreatetruecolor(strlen($this->request->uniqueId), 1);
        $data = str_split($this->request->uniqueId);

        $i = $x = $y = 0;
        for ($i = 0; $i < count($data); $i += 3) {
            $color = imagecolorallocate(
                $image,
                isset($data[$i]) ? ord($data[$i]) : 0,
                isset($data[$i + 1]) ? ord($data[$i + 1]) : 0,
                isset($data[$i + 2]) ? ord($data[$i + 2]) : 0
            );

            imagesetpixel($image, $x++, $y, $color);
        }

        ob_start();
        imagepng($image);
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }
}
