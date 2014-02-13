<?php

/**
 * Convert bytes to human readable format
 *
 * @param $octets
 * @param int $precision
 * @return string
 */
function octetsToSize($octets, $precision = 2)
{
    $kilooctet = 1024;
    $megaoctet = $kilooctet * 1024;
    $gigaoctet = $megaoctet * 1024;
    $teraoctet = $gigaoctet * 1024;

    if (($octets >= 0) && ($octets < $kilooctet)) {
        return $octets . ' o';

    } elseif (($octets >= $kilooctet) && ($octets < $megaoctet)) {
        return round($octets / $kilooctet, $precision) . ' Ko';

    } elseif (($octets >= $megaoctet) && ($octets < $gigaoctet)) {
        return round($octets / $megaoctet, $precision) . ' Mo';

    } elseif (($octets >= $gigaoctet) && ($octets < $teraoctet)) {
        return round($octets / $gigaoctet, $precision) . ' Go';

    } elseif ($octets >= $teraoctet) {
        return round($octets / $teraoctet, $precision) . ' To';
    } else {
        return $octets . ' O';
    }
}