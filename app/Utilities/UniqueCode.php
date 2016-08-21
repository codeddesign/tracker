<?php

namespace App\Utilities;

class UniqueCode
{
    const UNIQUE_PATTERN = '%s-%s-%s-%s';

    const RANDOM_START = 100000000;

    /**
     * @param Visitor $visitor
     */
    public function __construct()
    {
        $this->code = $this->generate();
    }

    public function __toString()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    protected function generate()
    {
        $time = time();
        $u_id = uniqid();
        $random = mt_rand(self::RANDOM_START, (self::RANDOM_START * 10) - 1);
        $check = crypt($u_id, $time);

        return sprintf(self::UNIQUE_PATTERN, $time, $u_id, $random, $check);
    }
}
