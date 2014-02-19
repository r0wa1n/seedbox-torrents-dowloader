<?php
require_once('../src/constants.php');

/**
 * Function which initialise smarty with header and footer information
 *
 * @param $smarty
 * @param $currentPage
 * @param $diskInfo
 */
function initSmarty($smarty, $currentPage, $diskInfo = true)
{
    $header = array(
        'title' => WEBSITE_TITLE,
        'currentPage' => $currentPage,
        'isSeedboxInitialized' => isSeedboxInitialized()
    );

    if ($diskInfo) {
        /* DISK SIZE INFO */
        $sizeTotal = shell_exec('df ' . ROOT_SERVER_DIRECTORY . ' | awk \'NR==2{print$2}\'') * 512;
        $sizeUsed = shell_exec('df ' . ROOT_SERVER_DIRECTORY . ' | awk \'NR==2{print$3}\'') * 512;
        $percent = 100 * $sizeUsed / $sizeTotal;
        $sizeLeft = $sizeTotal - $sizeUsed;

        $header['lastUpdate'] = date(DATE_PATTERN, file_get_contents(TEMP_DIR . LAST_UPDATE_FILE));
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

    $smarty->setTemplateDir('../src/smarty/templates');
    $smarty->setCompileDir('../src/smarty/templates_c');
    $smarty->setCacheDir('../src/smarty/cache');
    $smarty->setConfigDir('../src/smarty/configs');
    $smarty->addPluginsDir('../src/smarty/plugins');
}

/**
 * Function return true if seedbox information have been set
 */
function isSeedboxInitialized()
{
    if (file_exists(TEMP_DIR . SETTINGS_FILE)) {
        $settings = json_decode(file_get_contents(TEMP_DIR . SETTINGS_FILE), true);

        return !(empty($settings['seedbox']) || empty($settings['seedbox']['host']) || empty($settings['seedbox']['username'])
            || empty($settings['seedbox']['password']));
    } else {
        return false;
    }
}

/**
 * Function used to initialize ftp with seedbox information locate in settings file.
 *
 * Return false if settings are not set or invalids
 */
function createFTPConnection()
{
    if (file_exists(TEMP_DIR . SETTINGS_FILE)) {
        $settings = json_decode(file_get_contents(TEMP_DIR . SETTINGS_FILE), true);

        if (empty($settings['seedbox']) || empty($settings['seedbox']['host']) || empty($settings['seedbox']['username'])
            || empty($settings['seedbox']['password'])
        ) {
            return false;
        } else {
            // Connect to seedbox with SSL
            $ftp = ftp_ssl_connect($settings['seedbox']['host'], intval($settings['seedbox']['port']));
            if (!$ftp) {
                return false;
            }
            // Log with information set on settings screen
            if (!ftp_login($ftp, $settings['seedbox']['username'], $settings['seedbox']['password'])) {
                return false;
            };

            // Enter on passive mode
            if (ftp_pasv($ftp, true)) {
                return $ftp;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
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