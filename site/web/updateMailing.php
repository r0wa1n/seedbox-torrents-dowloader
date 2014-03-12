<?php
$mailingEnabled = $_POST['inputMailingEnableMailing'];
$mailingSmtpHost = $_POST['inputMailingSmtpHost'];
$mailingUsername = $_POST['inputMailingUsername'];
$mailingPassword = $_POST['inputMailingPassword'];
$mailingRecipient = $_POST['inputMailingRecipient'];

if ($mailingEnabled && (empty($mailingSmtpHost) || empty($mailingUsername) || empty($mailingPassword) || empty($mailingRecipient))
) {
    header('Location: settings.php?errorMailing=true#mailing');
} else {
    require_once('../src/constants.php');
    require_once('../src/utils.php');

    $settings = getSettings();

    $settings['mailing'] = array();
    if ($mailingEnabled) {
        $settings['mailing'] = array(
            'smtpHost' => $mailingSmtpHost,
            'username' => $mailingUsername,
            'password' => $mailingPassword,
            'recipient' => $mailingRecipient
        );
    }

    file_put_contents(TEMP_DIR . SETTINGS_FILE, json_encode($settings));
    chmod(TEMP_DIR . SETTINGS_FILE, 0600);

    header('Location: settings.php?successMailing=true#mailing');
}