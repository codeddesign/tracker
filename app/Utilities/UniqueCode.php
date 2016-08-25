<?php

namespace App\Utilities;

class UniqueCode
{
    const UNIQUE_PATTERN = '%s-%s-%s-%s';

    const RANDOM_START = 100000000;

    /**
     * @var string|null
     */
    protected $code;

    /**
     * @param string|null $code
     */
    public function __construct($code = null)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->code;
    }

    /**
     * @return $this
     */
    public function generate()
    {
        $time = time();
        $u_id = uniqid();
        $random = mt_rand(self::RANDOM_START, (self::RANDOM_START * 10) - 1);
        $check = $this->checkKey($u_id, $time, $random);

        $this->code = sprintf(self::UNIQUE_PATTERN, $time, $u_id, $random, $check);

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if (!trim($this->code)) {
            return false;
        }

        $parts = explode('-', $this->code);
        if (count($parts) != 4) {
            return false;
        }

        list($time, $u_id, $random, $check) = $parts;

        if ($check != $this->checkKey($u_id, $time, $random)) {
            return false;
        }

        return true;
    }

    /**
     * Generate a check key.
     *
     * @param string $u_id
     * @param int    $time
     * @param string $random
     *
     * @return string
     */
    protected function checkKey($u_id, $time, $random)
    {
        $salt = md5(implode('', [$time, $random]));

        return crypt($u_id, $salt);
    }
}
