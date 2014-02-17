<?php
require_once('../src/constants.php');

/**
 * Function which initialise smarty with header and footer information
 *
 * @param $smarty
 * @param $currentPage
 */
function initSmarty($smarty, $currentPage, $diskInfo = true)
{
    $header = array(
        'title' => WEBSITE_TITLE,
        'currentPage' => $currentPage
    );

    if($diskInfo) {
        /* DISK SIZE INFO */
        $sizeTotal = shell_exec('df ' . ROOT_SERVER_DIRECTORY . ' | awk \'NR==2{print$2}\'') * 512;
        $sizeUsed = shell_exec('df ' . ROOT_SERVER_DIRECTORY . ' | awk \'NR==2{print$3}\'') * 512;
        $percent = 100 * $sizeUsed / $sizeTotal;
        $sizeLeft = $sizeTotal - $sizeUsed;

        $header['lastUpdate'] = date(DATE_PATTERN, file_get_contents('../src/last-update'));
        $header['diskInfo'] = array(
            'totalSize' => $sizeTotal,
            'totalSizeUsed' => $sizeUsed,
            'totalPercentSizeUsed' => $percent,
            'totalSizeLeft' => $sizeLeft
        );
    }

    $smarty->assign('header', $header);

    $footer = array(
        'title' => sprintf(WEBSITE_FOOTER, date('Y'))
    );

    $smarty->assign('footer', $footer);
}

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