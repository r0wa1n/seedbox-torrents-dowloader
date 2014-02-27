<?php
require_once('../src/constants.php');
require_once('../src/utils.php');

function deleteFile($ftp, $file, $dir = '')
{
    if ($file['type'] === 'directory') {
        foreach ($file['children'] as $child) {
            deleteFile($ftp, $child, $dir . $file['name'] . '/');
        }
        if (!ftp_rmdir($ftp, $dir . $file['name'])) {
            addLog('ERROR', 'Unable to delete directory ' . $dir . $file['name'], 'delete');
        } else {
            addLog('SUCCESS', 'Directory ' . $dir . $file['name'] . ' has been successfully deleted', 'delete');
        }
    } else {
        if (!ftp_delete($ftp, $dir . $file['name'])) {
            addLog('ERROR', 'Unable to delete file ' . $dir . $file['name'], 'delete');
        } else {
            addLog('SUCCESS', 'File ' . $dir . $file['name'] . ' has been successfully deleted', 'delete');
        }
    }
}

$file = $_POST['file'];
if (!empty($file)) {
    $decodedFile = urldecode($file);
    $filesDetails = json_decode(file_get_contents(TEMP_DIR . SEEDBOX_DETAILS_FILE), true);
    $ftp = createFTPConnection();
    if ($ftp) {
        $pathFile = explode('/', $decodedFile);
        $file = searchFile($pathFile, 0, $filesDetails);
        array_pop($pathFile);
        // Delete file recursively
        deleteFile($ftp, $file, implode($pathFile, '/') . '/');

        ftp_close($ftp);
    }
} else {
    addLog('ERROR', 'Unable to delete empty file', 'delete');
}