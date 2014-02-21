<?php
require_once('../vendor/Smarty/Smarty.class.php');
require_once('../src/constants.php');
require_once('../src/utils.php');

$smarty = new Smarty();
initSmarty($smarty, 'SETTINGS', false);

if (file_exists(TEMP_DIR . SETTINGS_FILE)) {
    $settings = json_decode(file_get_contents(TEMP_DIR . SETTINGS_FILE), true);

    if (!empty($settings['seedbox']) && !empty($settings['seedbox']['host']) && !empty($settings['seedbox']['username'])
        && !empty($settings['seedbox']['password']) && !empty($settings['seedbox']['port'])) {
        $smarty->assign('seedbox', array(
            'host' => $settings['seedbox']['host'],
            'username' => $settings['seedbox']['username'],
            'port' => $settings['seedbox']['port']
        ));
    }
    if (!empty($settings['mailing']) && !empty($settings['mailing']['smtpHost'])
        && !empty($settings['mailing']['username']) && !empty($settings['mailing']['password'])
    ) {
        $smarty->assign('mailing', array(
            'smtpHost' => $settings['mailing']['smtpHost'],
            'smtpPort' => $settings['mailing']['smtpPort'],
            'ssl' => $settings['mailing']['ssl'],
            'username' => $settings['mailing']['username']
        ));
    }
}

if (!empty($_GET['errorSeedbox'])) {
    $smarty->assign('errorSeedbox', true);
}

if (!empty($_GET['successSeedbox'])) {
    $smarty->assign('successSeedbox', true);
}

if (!empty($_GET['errorMailing'])) {
    $smarty->assign('errorMailing', true);
}

if (!empty($_GET['successMailing'])) {
    $smarty->assign('successMailing', true);
}

$smarty->display('settings.tpl');

