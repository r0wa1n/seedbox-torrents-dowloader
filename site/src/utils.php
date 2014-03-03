<?php
require_once('../src/constants.php');
require_once('../vendor/PHPMailer/PHPMailerAutoload.php');
require_once('../vendor/Smarty/Smarty.class.php');

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
        $sizeTotal = disk_total_space(ROOT_SERVER_DIRECTORY);
        $sizeLeft = disk_free_space(ROOT_SERVER_DIRECTORY);
        $sizeUsed = $sizeTotal - $sizeLeft;

        $percent = 100 * $sizeUsed / $sizeTotal;
        if ($percent > 90) {
            $progressClass = 'danger';
        } else if ($percent > 70) {
            $progressClass = 'warning';
        } else {
            $progressClass = 'success';
        }

        $header['lastUpdate'] = date(DATE_PATTERN, file_get_contents(TEMP_DIR . LAST_UPDATE_FILE));
        $header['diskInfo'] = array(
            'totalSize' => $sizeTotal,
            'totalSizeUsed' => $sizeUsed,
            'totalPercentSizeUsed' => $percent,
            'totalSizeLeft' => $sizeLeft,
            'progressClass' => $progressClass
        );
    }

    $smarty->assign('header', $header);

    $footer = array(
        'title' => sprintf(WEBSITE_FOOTER, date('Y'))
    );

    $smarty->assign('footer', $footer);

    initSettingsSmarty($smarty);
}

/**
 * Function initialize settings for smarty
 *
 * @param $smarty
 */
function initSettingsSmarty($smarty)
{
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
            addLog('ERROR', 'No setting file found.', 'ftp');
            return false;
        } else {
            // Connect to seedbox with SSL
            $ftp = ftp_ssl_connect($settings['seedbox']['host'], intval($settings['seedbox']['port']));
            if (!$ftp) {
                addLog('ERROR', 'Wrong FTP host.', 'ftp');
                return false;
            }
            // Log with information set on settings screen
            if (!ftp_login($ftp, $settings['seedbox']['username'], $settings['seedbox']['password'])) {
                addLog('ERROR', 'Wrong FTP login or password.', 'ftp');
                return false;
            };

            // Enter on passive mode
            if (ftp_pasv($ftp, true)) {
                return $ftp;
            } else {
                addLog('ERROR', 'Unable to switch to passive mode.', 'ftp');
                return false;
            }
        }
    } else {
        addLog('ERROR', 'No setting file found to create ftp connection.', 'ftp');
        return false;
    }
}

/**
 * Function used to send an email when a download is completed
 *
 * @param $parameters array with possibles values are
 * <ul>
 *  <li>file</li>
 *  <li>size</li>
 *  <li>begin</li>
 *  <li>end</li>
 *  <li>duration</li>
 *  <li>average</li>
 * </ul>
 */
function sendCompleteMail($parameters)
{
    $smarty = new Smarty();
    initSettingsSmarty($smarty);

    foreach ($parameters as $key => $value) {
        $smarty->assign($key, $value);
    }
    $subject = 'Your download is complete';
    $smarty->assign('title', $subject);
    $smarty->assign('footer', sprintf(WEBSITE_FOOTER, date('Y')));
    $output = $smarty->fetch('mail-download-complete.tpl');

    return sendMail($output, $subject);
}

/**
 * Abstract function used to send an email
 * @param $text
 * @param $subject
 * @return bool
 */
function sendMail($text, $subject)
{
    if (file_exists(TEMP_DIR . SETTINGS_FILE)) {
        $settings = json_decode(file_get_contents(TEMP_DIR . SETTINGS_FILE), true);

        if (empty($settings['mailing']) || empty($settings['mailing']['smtpHost']) ||
            empty($settings['mailing']['username']) || empty($settings['mailing']['password'])
        ) {
            addLog('WARNING', 'Mailing configuration is not set', 'mailing');
            return false;
        } else {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = $settings['mailing']['smtpHost'];
            $mail->SMTPAuth = true;
            $mail->Username = $settings['mailing']['username'];
            $mail->Password = $settings['mailing']['password'];
            $mail->SMTPSecure = 'tls';

            $mail->From = $settings['mailing']['username'];
            $mail->FromName = $settings['mailing']['username'];
            $mail->addAddress($settings['mailing']['recipient'], $settings['mailing']['recipient']);
            $mail->addReplyTo($settings['mailing']['username'], 'No-Reply');
            $mail->isHTML(true);

            $mail->Subject = $subject;
            $mail->Body = $text;

            try {
                if (!$mail->send()) {
                    addLog('ERROR', 'Unable to send mail.', 'mailing');
                    return false;
                } else {
                    addLog('SUCCESS', 'Mail has been sent', 'mailing');
                    return true;
                }
            } catch (phpmailerException $e) {
                addLog('ERROR', 'Unable to send mail. Details : ' . $e->getMessage(), 'mailing');
                return false;
            } catch (Exception $e) {
                addLog('ERROR', 'Unable to send mail. Details : ' . $e->getMessage(), 'mailing');
                return false;
            }
        }
    } else {
        addLog('WARNING', 'Mailing configuration is not set', 'mailing');
        return false;
    }
}

/**
 * Function used to know file size (octet)
 * @param $file
 * @return string
 */
function getFileSize($file)
{
    return shell_exec('du -sk "' . $file . '" | awk \'{print$1}\'') * 1024;
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

function searchFile($filePath, $currentPathKey, $files)
{
    // Check if currentPath is the last one of $filePath
    if ($currentPathKey == (count($filePath) - 1)) {
        return $files[$filePath[$currentPathKey]];
    } else {
        return searchFile($filePath, ($currentPathKey + 1), $files[$filePath[$currentPathKey]]['children']);
    }
}

function computeChildren($children, $dir = '')
{
    $torrents = array();
    foreach ($children as $fileDetail) {
        if ($fileDetail['name'] != 'recycle_bin') {
            // 4 cases :
            //  - File/Dir can be downloaded
            //  - File/Dir is already downloaded
            //  - File/Dir is pending to be downloaded
            //  - File/Dir is pending and is being downloaded
            // status can be (DOWNLOADED, PENDING, DOWNLOADING, NONE)
            $status = 'NONE';
            $detailsStatus = array();
            $fileSize = $fileDetail['size'];
            if (file_exists(TEMP_DIR . 'pending/' . $dir . $fileDetail['name'])) {
                // File is pending, check if download is started or not
                if (file_exists(DOWNLOAD_DIRECTORY . $dir . $fileDetail['name'])) {
                    $status = 'DOWNLOADING';
                    $currentSize = getFileSize(DOWNLOAD_DIRECTORY . $dir . $fileDetail['name']);
                    $detailsStatus = array(
                        'currentSize' => $currentSize,
                        'currentPercent' => 100 * $currentSize / $fileSize
                    );
                } else {
                    $status = 'PENDING';
                }
            } else if (file_exists(DOWNLOAD_DIRECTORY . $dir . $fileDetail['name'])) {
                // File is downloaded
                $status = 'DOWNLOADED';
            }
            $fileNameEncoded = urlencode($fileDetail['name']);

            $torrents[] = array(
                'status' => $status,
                'detailsStatus' => $detailsStatus,
                'size' => $fileSize,
                'name' => $fileDetail['name'],
                'encodedName' => $fileNameEncoded,
                'isDirectory' => $fileDetail['type'] === 'directory'
            );
        }
    }

    return $torrents;
}

function addLog($lvl, $text, $file)
{
    $logFile = date('Y-m-d') . '-' . $file . '.log';
    $text = '[' . $lvl . '] ' . date(DATE_PATTERN) . ' : ' . $text;
    file_put_contents(LOGS_DIRECTORY . $logFile, $text . "\n", FILE_APPEND);
}