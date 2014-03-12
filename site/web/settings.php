<?php
require_once('../vendor/Smarty/Smarty.class.php');
require_once('../src/constants.php');
require_once('../src/utils.php');

$smarty = new Smarty();
initSmarty($smarty, 'SETTINGS', false);

$settings = getSettings();
if (!empty($settings['seedbox']) && !empty($settings['seedbox']['host']) && !empty($settings['seedbox']['username'])
    && !empty($settings['seedbox']['password']) && !empty($settings['seedbox']['port'])) {
    $smarty->assign('seedbox', array(
        'host' => $settings['seedbox']['host'],
        'username' => $settings['seedbox']['username'],
        'port' => $settings['seedbox']['port']
    ));
}
if (!empty($settings['mailing']) && !empty($settings['mailing']['smtpHost'])
    && !empty($settings['mailing']['username']) && !empty($settings['mailing']['password']) && !empty($settings['mailing']['recipient'])
) {
    $smarty->assign('mailing', array(
        'smtpHost' => $settings['mailing']['smtpHost'],
        'smtpPort' => $settings['mailing']['smtpPort'],
        'ssl' => $settings['mailing']['ssl'],
        'username' => $settings['mailing']['username'],
        'recipient' => $settings['mailing']['recipient']
    ));
}

if(empty($settings['downloadDirectory'])) {
    initDownloadDirectory();
}


$smarty->assign('downloadDirectory', getDownloadDirectory());

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

if (!empty($_GET['errorDownloadDir'])) {
    $smarty->assign('errorDownloadDir', true);
}

if (!empty($_GET['successDownloadDir'])) {
    $smarty->assign('successDownloadDir', true);
}

$smarty->display('settings.tpl');

