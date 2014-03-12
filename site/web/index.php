<?php
require_once('../vendor/Smarty/Smarty.class.php');
require_once('../src/constants.php');
require_once('../src/utils.php');

$smarty = new Smarty();
initSmarty($smarty, 'HOME');

$filesDetails = getSeedboxDetails();
$torrents = computeChildren($filesDetails, getDownloadDirectory());

$smarty->assign('torrents', $torrents);
$smarty->assign('level', 0);

$smarty->display('index.tpl');