<?php
require_once('../src/constants.php');
require_once('../src/utils.php');

if (isset($argv)) {
    $file = $argv[1];

    if (empty($file)) {
        addLog('ERROR', 'Empty file cannot be downloaded', 'download');
        http_response_code(400);
    } else {
        $decodedFile = urldecode($file);

        $filesDetails = json_decode(file_get_contents(TEMP_DIR . SEEDBOX_DETAILS_FILE), true);
        $begin = round(microtime(true));

        $pathFile = explode('/', $decodedFile);
        $fileDetails = searchFile($pathFile, 0, $filesDetails);
        array_pop($pathFile);

        // Prepare pending directories
        $previous = TEMP_DIR . 'pending/';
        foreach ($pathFile as $dirToCreate) {
            mkdir($previous . $dirToCreate, 0755, true);
            $previous .= $dirToCreate . '/';
        }
        $pendingFiles = buildPendingFiles($fileDetails, implode($pathFile, '/'));

        $ftp = createFTPConnection();
        if ($ftp) {
            downloadFile($ftp, $pendingFiles);
            // Delete pending directories
            $pathFile = array_reverse($pathFile);
            foreach ($pathFile as $dirToDelete) {
                rmdir(TEMP_DIR . 'pending/' . $dirToDelete);
                $previous .= $dirToDelete . '/';
            }
        }
        ftp_close($ftp);

        $end = round(microtime(true));

        $sizeOctet = $fileDetails['size'];
        $beginDateTime = new DateTime();
        $beginDateTime->setTimestamp($begin);
        $endDateTime = new DateTime();
        $endDateTime->setTimestamp($end);
        $interval = $beginDateTime->diff($endDateTime);
        $duration = $end - $begin;
        $average = $sizeOctet / $duration;
        $size = octetsToSize($sizeOctet);

        if(sendCompleteMail(array(
            'file' => $file,
            'size' => $size,
            'begin' => date(DATE_PATTERN, $begin),
            'end' => date(DATE_PATTERN, $end),
            'duration' => ($interval->h < 10 ? ('0' . $interval->h) : $interval->h) . 'h' . ($interval->m < 10 ? ('0' . $interval->m) : $interval->m) . 'm' . ($interval->s < 10 ? ('0' . $interval->s) : $interval->s) . 's',
            'average' => octetsToSize($average)
        ))) {
            addLog('SUCCESS', 'File ' . $file . ' downloaded (' . $size . ')', 'download');
        }
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
    $downloadDirectory = getDownloadDirectory();
    if ($pendingFiles['type'] === 'directory') {
        mkdir($downloadDirectory . $pendingFiles['name'], 0755, true);
        foreach ($pendingFiles['children'] as $child) {
            downloadFile($ftp, $child);
        }
        // Delete pending directory
        rmdir(TEMP_DIR . 'pending/' . $pendingFiles['name']);
    } else {
        ftp_get($ftp, $downloadDirectory . $pendingFiles['name'], $pendingFiles['name'], FTP_BINARY);
        chmod($downloadDirectory . $pendingFiles['name'], 0644);
        // Delete pending file
        unlink(TEMP_DIR . 'pending/' . $pendingFiles['name']);
    }
}