<?php
if (isset($argv)) {
    $file = $argv[1];

    if (empty($file)) {
        http_response_code(400);
    } else {
        $file = urldecode($file);

        require_once('../src/constants.php');
        require_once('../src/utils.php');
        $filesDetails = json_decode(file_get_contents(TEMP_DIR . SEEDBOX_DETAILS_FILE), true);
        $begin = round(microtime(true));

        $ftp = createFTPConnection();
        if ($ftp) {
            downloadFile($ftp, $filesDetails[$file]);
        }
        ftp_close($ftp);
        $end = round(microtime(true));

        $sizeOctet = $filesDetails[$file]['size'];
        $duration = $end - $begin;
        $average = $sizeOctet / ($duration / 1000);
//    sendCompleteMail(array(
//        'file' => $file,
//        'size' => octetsToSize($sizeOctet),
//        'begin' => date(DATE_PATTERN, $begin),
//        'end' => date(DATE_PATTERN, $end),
//        'duration' => $duration,
//        'average' => $average
//    ));
    }
}

function downloadFile($ftp, $fileDetails, $dir = '')
{
    if ($fileDetails['type'] === 'directory') {
        mkdir(DOWNLOAD_DIRECTORY . $dir . '/' . $fileDetails['name'], 0755, true);
        foreach ($fileDetails['children'] as $child) {
            downloadFile($ftp, $child, $dir . '/' . $fileDetails['name']);
        }
    } else {
        ftp_get($ftp, DOWNLOAD_DIRECTORY . $dir . '/' . $fileDetails['name'], $dir . '/' . $fileDetails['name'], FTP_BINARY);
        chmod(DOWNLOAD_DIRECTORY . $dir . '/' . $fileDetails['name'], 0644);
    }
}