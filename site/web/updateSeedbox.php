<?php
$seedboxHost = $_POST['inputSeedboxHost'];
$seedboxUsername = $_POST['inputSeedboxUsername'];
$seedboxPassword = $_POST['inputSeedboxPassword'];

if (empty($seedboxHost) || empty($seedboxUsername) || empty($seedboxPassword)) {
    header('Location: settings.php?error=true');
} else {
    require_once('../src/constants.php');
    file_put_contents(TEMP_DIR . SEEDBOX_DETAILS_FILE, json_encode(array(
        'seedboxHost' => $seedboxHost,
        'seedboxUsername' => $seedboxUsername,
        'seedboxPassword' => $seedboxPassword
    )));

    header('Location: settings.php?success=true');
}