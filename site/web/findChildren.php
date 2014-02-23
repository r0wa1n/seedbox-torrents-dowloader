<?php
require_once('../vendor/Smarty/Smarty.class.php');
require_once('../src/constants.php');
require_once('../src/utils.php');

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