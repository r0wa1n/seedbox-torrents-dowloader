<?php
require_once('../vendor/Smarty/Smarty.class.php');
require_once('../src/constants.php');
require_once('../src/utils.php');

$smarty = new Smarty();
initSmarty($smarty, 'HOME');

$filesDetails = json_decode(file_get_contents(TEMP_DIR . SEEDBOX_DETAILS_FILE), true);
$torrents = array();
foreach ($filesDetails as $fileDetail) {
    if ($fileDetail['name'] != 'recycle_bin') {
        // Check if file has already been downloaded
        $downloaded = shell_exec('find ' . DOWNLOAD_DIRECTORY . ' -name "' . $fileDetail['name'] . '" | wc -l') >= 1;
        $fileSize = $fileDetail['size'];
        $fileNameEncoded = urlencode($fileDetail['name']);
        $downloadingStatus = file_exists(TEMP_DIR . SEEDBOX_NAME . '/' . $fileDetail['name']);
        $downloading = array(
            'status' => $downloadingStatus
        );
        if($downloadingStatus) {
            $downloading['currentSize'] = shell_exec('du -sk ' . TEMP_DIR . SEEDBOX_NAME . '/' . $fileDetail['name'] . ' | awk \'{print$1}\'') * 1024;
            $downloading['currentPercent'] = 100 * $size / $fileSize;
        }

        $torrents[] = array(
            'downloaded' => $downloaded,
            'size' => $fileSize,
            'name' => $fileDetail['name'],
            'encodedName' => $fileNameEncoded
        );
    }
}
$smarty->assign('torrents', $torrents);

$smarty->display('index.tpl');