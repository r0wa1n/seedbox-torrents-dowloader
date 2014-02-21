<?php
require_once('../vendor/Smarty/Smarty.class.php');
require_once('../src/constants.php');
require_once('../src/utils.php');

function computeChildren($children)
{
    $torrents = array();
    foreach ($children as $fileDetail) {
        if ($fileDetail['name'] != 'recycle_bin') {
            // Check if file has already been downloaded
            $downloaded = file_exists(DOWNLOAD_DIRECTORY . $fileDetail['name']);
            $fileSize = $fileDetail['size'];
            $fileNameEncoded = urlencode($fileDetail['name']);
            $downloadingStatus = file_exists(TEMP_DIR . SEEDBOX_NAME . '/' . $fileDetail['name']);
            $downloading = array(
                'status' => $downloadingStatus
            );
            if ($downloadingStatus) {
                $downloading['currentSize'] = getFileSize(TEMP_DIR . SEEDBOX_NAME . '/' . $fileDetail['name']);
                $downloading['currentPercent'] = 100 * $downloading['currentSize'] / $fileSize;
            }

            $torrents[] = array(
                'downloaded' => $downloaded,
                'size' => $fileSize,
                'name' => $fileDetail['name'],
                'encodedName' => $fileNameEncoded,
                'isDirectory' => $fileDetail['type'] === 'directory'
            );
        }
    }

    return $torrents;
}

$smarty = new Smarty();
initSmarty($smarty, 'HOME');

$filesDetails = json_decode(file_get_contents(TEMP_DIR . SEEDBOX_DETAILS_FILE), true);
$torrents = computeChildren($filesDetails);

$smarty->assign('torrents', $torrents);
$smarty->assign('level', 0);

$smarty->display('index.tpl');