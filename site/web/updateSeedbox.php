<?php
$seedboxHost = $_POST['inputSeedboxHost'];
$seedboxUsername = $_POST['inputSeedboxUsername'];
$seedboxPassword = $_POST['inputSeedboxPassword'];

if (empty($seedboxHost) || empty($seedboxUsername) || empty($seedboxPassword)) {
    header('Location: settings.php?errorSeedbox=true#seedbox');
} else {
    require_once('../src/constants.php');

    $settings = array();
    if(file_exists(TEMP_DIR . SETTINGS_FILE)) {
        $settings = json_decode(file_get_contents(TEMP_DIR . SETTINGS_FILE), true);
    }

    $settings['seedbox'] = array(
        'host' => $seedboxHost,
        'username' => $seedboxUsername,
        'password' => $seedboxPassword
    );

    file_put_contents(TEMP_DIR . SETTINGS_FILE, json_encode($settings));

    header('Location: settings.php?successSeedbox=true#seedbox');
}