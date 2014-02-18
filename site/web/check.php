<?php
require_once('../vendor/Smarty/Smarty.class.php');

$smarty = new Smarty();

$smarty->setTemplateDir('../src/smarty/templates');
$smarty->setCompileDir('../src/smarty/templates_c');
$smarty->setCacheDir('../src/smarty/cache');
$smarty->setConfigDir('../src/smarty/configs');
$smarty->addPluginsDir('../src/smarty/plugins');

$smarty->testInstall();