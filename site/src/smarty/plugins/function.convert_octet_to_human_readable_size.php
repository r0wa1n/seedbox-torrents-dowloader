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
    $size = $params['size'];
    $precision = empty($params['precision']) ? 2 : $params['precision'];
    if (empty($size)) {
        return '-';
    }

    if ($size >= 1099511627776) return round(($size / 1099511627776 * 100) / 100, $precision) . ' To';
    if ($size >= 1073741824) return round(($size / 1073741824 * 100) / 100, $precision) . ' Go';
    if ($size >= 1048576) return round(($size / 1048576 * 100) / 100, $precision) . ' Mo';
    if ($size >= 1024) return round(($size / 1024 * 100) / 100, $precision) . ' Ko';
    if ($size > 0) return $size . ' o';
    return '-';
}
