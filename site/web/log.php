<?php
require_once('../vendor/Smarty/Smarty.class.php');
require_once('../src/constants.php');
require_once('../src/utils.php');

$file = $_GET['file'];

if (empty($file) || !file_exists(LOGS_DIRECTORY . $file)) {
    header('Location: logs.php');
}

$smarty = new Smarty();
initSmarty($smarty, 'LOGS');

$smarty->assign('title', $file);
$smarty->assign('logDetails', file_get_contents(LOGS_DIRECTORY . $file));

$smarty->display('log.tpl');