<?php
require_once('../vendor/Smarty/Smarty.class.php');
require_once('../src/constants.php');
require_once('../src/utils.php');

$smarty = new Smarty();
initSmarty($smarty, 'HOME');

$filesDetails = json_decode(file_get_contents(TEMP_DIR . SEEDBOX_DETAILS_FILE), true);
$torrents = computeChildren($filesDetails, getDownloadDirectory());

$smarty->assign('torrents', $torrents);
$smarty->assign('level', 0);

$smarty->display('index.tpl');