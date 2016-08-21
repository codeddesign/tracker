<?php

namespace App\Utilities;

class Url
{
    /**
     * @var string
     */
    protected $link;

    /**
     * @var bool|string
     */
    protected $domain = false;

    /**
     * @var bool
     */
    protected $isSecure = false;

    /**
     * @var bool
     */
    protected $isWWW = false;

    /**
     * @var array
     */
    protected $parsed = [
        'scheme' => false,
        'host' => false,
        'path' => false,
    ];

    /**
     * @param string $link
     */
    public function __construct($link)
    {
        $this->link = $link;

        $this->setParsed()
            ->setDomain()
            ->setIsSecure()
            ->setIsWWW()
            ->setPath();
    }

    /**
     * @return string|bool
     */
    public function domain()
    {
        return $this->domain;
    }

    /**
     * @return string|bool
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * @return bool
     */
    public function isSecure()
    {
        return $this->isSecure;
    }

    /**
     * @return string|bool
     */
    public function isWWW()
    {
        return $this->isWWW;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'domain' => $this->domain,
            'path' => $this->path,
            'is_secure' => $this->isSecure,
            'is_www' => $this->isWWW,
        ];
    }

    /**
     * @return $this
     */
    protected function setParsed()
    {
        $parsed = parse_url($this->link);
        if (count($parsed) >= 3) {
            $this->parsed = $parsed;
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function setDomain()
    {
        $domain = str_replace('www.', '', strtolower($this->parsed['host']));

        if (!trim($domain)) {
            $domain = false;
        }

        $this->domain = $domain;

        return $this;
    }

    /**
     * @return $this
     */
    protected function setIsSecure()
    {
        $this->isSecure = strtolower($this->parsed['scheme']) == 'https' ? true : false;

        return $this;
    }

    /**
     * @return $this
     */
    protected function setIsWWW()
    {
        $this->isWWW = stristr(strtolower($this->parsed['host']), 'www.') !== false;

        return $this;
    }

    /**
     * @return $this
     */
    protected function setPath()
    {
        if (!isset($this->parsed['path'])) {
            $this->parsed['path'] = false;
        }

        $path = !$this->parsed['path'] ? '/' : $this->parsed['path'];

        if (isset($this->parsed['query']) and $this->parsed['query']) {
            $path .= '?'.$this->parsed['query'];
        }

        $this->path = $path;

        return $this;
    }
}
