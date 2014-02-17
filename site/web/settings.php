<?php
require_once('../libs/Smarty.class.php');
require_once('../src/constants.php');
require_once('../src/utils.php');

$smarty = new Smarty();
initSmarty($smarty, 'SETTINGS', false);

$smarty->display('settings.tpl');

