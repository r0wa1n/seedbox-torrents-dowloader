<?php
require_once('../src/constants.php');
require_once('../src/utils.php');

if (isset($argv)) {
    $file = $argv[1];

    if (empty($file)) {
        addLog('ERROR', 'Empty file cannot be downloaded', 'download');
        http_response_code(400);
    } else {
        $file = urldecode($file);

        $filesDetails = json_decode(file_get_contents(TEMP_DIR . SEEDBOX_DETAILS_FILE), true);
        $begin = round(microtime(true));

        $pendingFiles = buildPendingFiles($filesDetails[$file]);
        $ftp = createFTPConnection();
        if ($ftp) {
            downloadFile($ftp, $pendingFiles);
        }
        ftp_close($ftp);
        $end = round(microtime(true));

        $sizeOctet = $filesDetails[$file]['size'];
        $duration = $end - $begin;
        $average = $sizeOctet / ($duration / 1000);
        $size = octetsToSize($sizeOctet);

//        sendCompleteMail(array(
//            'file' => $file,
//            'size' => $size,
//            'begin' => date(DATE_PATTERN, $begin),
//            'end' => date(DATE_PATTERN, $end),
//            'duration' => $duration,
//            'average' => octetsToSize($average)
//        ));
        addLog('SUCCESS', 'File ' . $file . ' downloaded (' . $size . ')', 'download');
    }
} else {
    addLog('ERROR', 'No param found to download a file', 'download');
}

function buildPendingFiles($fileDetails, $dir = '')
{
    if ($fileDetails['type'] === 'directory') {
        // Create pending directory
        mkdir(TEMP_DIR . 'pending/' . $dir . '/' . $fileDetails['name'], 0755, true);
        $children = array();
        foreach ($fileDetails['children'] as $child) {
            $children[] = buildPendingFiles($child, $dir . '/' . $fileDetails['name']);
        }
        return array(
            'type' => 'directory',
            'name' => $dir . '/' . $fileDetails['name'],
            'children' => $children
        );
    } else {
        touch(TEMP_DIR . 'pending/' . $dir . '/' . $fileDetails['name']);
        return array(
            'type' => 'file',
            'name' => $dir . '/' . $fileDetails['name']
        );
    }
}

function downloadFile($ftp, $pendingFiles)
{
    if ($pendingFiles['type'] === 'directory') {
        mkdir(DOWNLOAD_DIRECTORY . $pendingFiles['name'], 0755, true);
        foreach ($pendingFiles['children'] as $child) {
            downloadFile($ftp, $child);
        }
        // Delete pending directory
        rmdir(TEMP_DIR . 'pending/' . $pendingFiles['name']);
    } else {
        ftp_get($ftp, DOWNLOAD_DIRECTORY . $pendingFiles['name'], $pendingFiles['name'], FTP_BINARY);
        chmod(DOWNLOAD_DIRECTORY . $pendingFiles['name'], 0644);
        // Delete pending file
        unlink(TEMP_DIR . 'pending/' . $pendingFiles['name']);
    }
}