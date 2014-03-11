<?php
$downloadDirectory = $_POST['inputDownloadDirectory'];

if (empty($downloadDirectory)) {
    header('Location: settings.php?errorDownloadDir=true#download-directory');
} else {
    require_once('../src/constants.php');

    $settings = array();
    if (file_exists(TEMP_DIR . SETTINGS_FILE)) {
        $settings = json_decode(file_get_contents(TEMP_DIR . SETTINGS_FILE), true);
    }
    $settings['downloadDirectory'] = $downloadDirectory;

    file_put_contents(TEMP_DIR . SETTINGS_FILE, json_encode($settings));

    header('Location: settings.php?successDownloadDir=true#download-directory');
}