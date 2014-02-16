<?php
require_once('../libs/Smarty.class.php');
require_once('../src/constants.php');
require_once('../src/utils.php');

$smarty = new Smarty();
initSmarty($smarty, 'HOME');

$filesDetails = json_decode(file_get_contents(TEMP_DIR . FILES_DETAILS_MIRROR_SEEDBOX), true);

$torrents = array();
foreach ($filesDetails as $fileDetail) {
    if ($fileDetail['file'] != 'recycle_bin') {
        // Check if file has already been downloaded
        $downloaded = shell_exec('find ' . DOWNLOAD_DIRECTORY . ' -name "' . $fileDetail['file'] . '" | wc -l') >= 1;
        $fileSize = $fileDetail['size'] * 1024;
        $fileNameEncoded = urlencode($fileDetail['file']);
        $downloadingStatus = file_exists(TEMP_DIR . SEEDBOX_NAME . '/' . $fileDetail['file']);
        $downloading = array(
            'status' => $downloadingStatus
        );
        if($downloadingStatus) {
            $downloading['currentSize'] = shell_exec('du -sk ' . TEMP_DIR . SEEDBOX_NAME . '/' . $fileDetail['file'] . ' | awk \'{print$1}\'') * 1024;
            $downloading['currentPercent'] = 100 * $size / $fileSize;
        }

        $torrents[] = array(
            'downloaded' => $downloaded,
            'size' => $fileSize,
            'name' => $fileDetail['file'],
            'encodedName' => urlencode($fileDetail['file'])
        );
    }
}
$smarty->assign('torrents', $torrents);

$smarty->display('index.tpl');