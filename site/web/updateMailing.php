<?php
$mailingEnabled = $_POST['inputMailingEnableMailing'];
$mailingSmtpHost = $_POST['inputMailingSmtpHost'];
$mailingSmtpPort = $_POST['inputMailingSmtpPort'];
$mailingSSL = $_POST['inputMailingSSL'];
$mailingUsername = $_POST['inputMailingUsername'];
$mailingPassword = $_POST['inputMailingPassword'];

if ($mailingEnabled && (empty($mailingSmtpHost) || empty($mailingSmtpPort) || empty($mailingUsername) || empty($mailingPassword))
) {
    header('Location: settings.php?errorMailing=true#mailing');
} else {
    require_once('../src/constants.php');

    $settings = array();
    if (file_exists(TEMP_DIR . SETTINGS_FILE)) {
        $settings = json_decode(file_get_contents(TEMP_DIR . SETTINGS_FILE), true);
    }

    $settings['mailing'] = array();
    if($mailingEnabled) {
        $settings['mailing'] = array(
            'smtpHost' => $mailingSmtpHost,
            'smtpPort' => $mailingSmtpPort,
            'ssl' => !empty($mailingSSL),
            'username' => $mailingUsername,
            'password' => $mailingPassword
        );
    }

    file_put_contents(TEMP_DIR . SETTINGS_FILE, json_encode($settings));

    header('Location: settings.php?successMailing=true#mailing');
}