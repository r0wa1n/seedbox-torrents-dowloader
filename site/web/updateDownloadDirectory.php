<?php
$downloadDirectory = $_POST['inputDownloadDirectory'];

if (empty($downloadDirectory)) {
    header('Location: settings.php?errorDownloadDir=true#download-directory');
} else {
    require_once('../src/constants.php');
    require_once('../src/utils.php');

    $settings = getSettings();
    $settings['downloadDirectory'] = $downloadDirectory;

    file_put_contents(TEMP_DIR . SETTINGS_FILE, json_encode($settings));
    chmod(TEMP_DIR . SETTINGS_FILE, 0600);

    header('Location: settings.php?successDownloadDir=true#download-directory');
}