<?php

namespace App\Geolite;

/**
 * Helper trait for Geolite.
 */
trait Network
{
    /**
     * @param string $ip
     *
     * @return string|int
     */
    public static function ipAton($ip)
    {
        $ip_aton = sprintf('%u', ip2long($ip));
        $ip_aton = (substr($ip, 0, 3) > 127) ? ((ip2long($ip) & 0x7FFFFFFF) + 0x80000000) : ip2long($ip);

        return $ip_aton;
    }

    /**
     * @param string $network
     *
     * @return array
     */
    public static function cidrToIpRange($network)
    {
        $start = strtok($network, '/');
        $n = 3 - substr_count($network, '.');
        if ($n > 0) {
            for ($i = $n; $i > 0; --$i) {
                $start .= '.0';
            }
        }
        $bits1 = str_pad(decbin(ip2long($start)), 32, '0', STR_PAD_LEFT);
        $network = pow(2, (32 - substr(strstr($network, '/'), 1))) - 1;
        $bits2 = str_pad(decbin($network), 32, '0', STR_PAD_LEFT);

        $final = '';
        for ($i = 0; $i < 32; ++$i) {
            if ($bits1[$i] == $bits2[$i]) {
                $final .= $bits1[$i];
            }

            if ($bits1[$i] == 1 and $bits2[$i] == 0) {
                $final .= $bits1[$i];
            }

            if ($bits1[$i] == 0 and $bits2[$i] == 1) {
                $final .= $bits2[$i];
            }
        }

        $end = long2ip(bindec($final));

        return compact('start', 'end');
    }
}
