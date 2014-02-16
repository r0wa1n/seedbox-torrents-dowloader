<?php
require_once('../libs/Smarty.class.php');
require_once('../src/constants.php');
require_once('../src/utils.php');

$smarty = new Smarty();
initSmarty($smarty, 'LOGS');

if ($handle = opendir(LOGS_DIRECTORY)) {
    $files = array();
    while (false !== ($entry = readdir($handle))) {
        if ($entry != '.' && $entry != '..') {
            $files[] = $entry;
        }
    }
    closedir($handle);

    // Sort files
    arsort($files);
    $logFiles = array();
    foreach($files as $file) {
        $logFiles[] = array(
            'name' => $file,
            'encodedName' => urlencode($file)
        );
    }
    $smarty->assign('files', $logFiles);
}

$smarty->display('logs.tpl');