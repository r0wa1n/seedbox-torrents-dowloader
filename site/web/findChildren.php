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
            $downloaded = shell_exec('find ' . DOWNLOAD_DIRECTORY . ' -name "' . $parent . $fileDetail['name'] . '" | wc -l') >= 1;
            $fileSize = $fileDetail['size'];
            $fileNameEncoded = urlencode($fileDetail['name']);
            $downloadingStatus = file_exists(TEMP_DIR . SEEDBOX_NAME . '/' . $parent . $fileDetail['name']);
            $downloading = array(
                'status' => $downloadingStatus
            );
            if ($downloadingStatus) {
                $downloading['currentSize'] = shell_exec('du -sk ' . TEMP_DIR . SEEDBOX_NAME . '/' . $parent . $fileDetail['name'] . ' | awk \'{print$1}\'') * 1024;
                $downloading['currentPercent'] = 100 * $downloading['currentSize'] / $fileSize;
            }

            $torrents[] = array(
                'downloaded' => $downloaded,
                'size' => $fileSize,
                'name' => $parent . $fileDetail['name'],
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
$encodedFile = urldecode($_GET['file']);
$pathFile = explode('/', $encodedFile);
$torrents = computeChildren(searchChildren($pathFile, 0, $filesDetails));
//var_dump($torrents);
$smarty->assign('torrents', $torrents);
$smarty->assign('parent', $encodedFile);
$smarty->assign('level', $_GET['level'] + 1);

$smarty->display('torrents-list.tpl');