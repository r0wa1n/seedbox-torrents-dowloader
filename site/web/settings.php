<?php
require_once('../libs/Smarty.class.php');
require_once('../src/constants.php');
require_once('../src/utils.php');

$smarty = new Smarty();
initSmarty($smarty, 'SETTINGS', false);

if (file_exists(TEMP_DIR . SEEDBOX_DETAILS_FILE)) {
    $seedboxFileDetails = json_decode(file_get_contents(TEMP_DIR . SEEDBOX_DETAILS_FILE), true);

    if (!empty($seedboxFileDetails['seedboxHost']) && !empty($seedboxFileDetails['seedboxUsername'])
        && !empty($seedboxFileDetails['seedboxPassword'])
    ) {
        $smarty->assign('seedbox', array(
            'host' => $seedboxFileDetails['seedboxHost'],
            'username' => $seedboxFileDetails['seedboxUsername']
        ));
    }
}

if(!empty($_GET['error'])) {
    $smarty->assign('error', true);
}

if(!empty($_GET['success'])) {
    $smarty->assign('success', true);
}

$smarty->display('settings.tpl');

