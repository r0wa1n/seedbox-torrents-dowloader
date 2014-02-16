<?php

/**
 * Convert size in octet to human readable size
 *
 * @param array $params parameters
 * @param Smarty_Internal_Template $smarty template object
 * @return string|null
 */
function smarty_function_convert_octet_to_human_readable_size($params, &$smarty)
{
    $octets = $params['size'];
    $precision = empty($params['precision']) ? 2 : $params['precision'];
    if (empty($octets)) {
        return;
    }

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
