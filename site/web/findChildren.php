<?php
require_once('../vendor/Smarty/Smarty.class.php');
require_once('../src/constants.php');
require_once('../src/utils.php');

function computeChildren($children, $parent = '')
{
    $torrents = array();
    foreach ($children as $fileDetail) {
        if ($fileDetail['name'] != 'recycle_bin') {
            // Check if file has already been downloaded
            $downloaded = file_exists(DOWNLOAD_DIRECTORY . $parent . $fileDetail['name']);
            $fileSize = $fileDetail['size'];
            $fileNameEncoded = urlencode($fileDetail['name']);
            $downloadingStatus = file_exists(TEMP_DIR . SEEDBOX_NAME . '/' . $parent . $fileDetail['name']);
            $downloading = array(
                'status' => $downloadingStatus
            );
            if ($downloadingStatus) {
                $downloading['currentSize'] = getFileSize(TEMP_DIR . SEEDBOX_NAME . '/' . $parent . $fileDetail['name']);
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

function searchChildren($filePath, $currentPathKey, $files)
{
    // Check if currentPath is the last one of $filePath
    if ($currentPathKey == (count($filePath) - 1)) {
        return $files[$filePath[$currentPathKey]]['children'];
    } else {
        return searchChildren($filePath, ($currentPathKey + 1), $files[$filePath[$currentPathKey]]['children']);
    }
}

$smarty = new Smarty();
initSmarty($smarty, 'HOME');

$filesDetails = json_decode(file_get_contents(TEMP_DIR . SEEDBOX_DETAILS_FILE), true);
// Search children for this fileName
$file = $_GET['file'];
$decodedFile = urldecode($file);
$pathFile = explode('/', $decodedFile);
$torrents = computeChildren(searchChildren($pathFile, 0, $filesDetails), $decodedFile . '/');

$smarty->assign('torrents', $torrents);
$smarty->assign('parent', $file);
$smarty->assign('level', $_GET['level'] + 1);

$smarty->display('torrents-list.tpl');