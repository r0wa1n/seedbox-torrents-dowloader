<?php
$file = $_GET['file'];

include('../src/constants.php');
include('../src/utils.php');
if (empty($file) || !file_exists(FILES_TO_DOWNLOAD_SERVER_DIRECTORY . SEEDBOX_NAME . '/' . $file)) {
    http_response_code(400);
} else {
    $size = shell_exec('du -s '. FILES_TO_DOWNLOAD_SERVER_DIRECTORY . SEEDBOX_NAME .'/' . urldecode($file) . ' | awk \'{print$1}\'') * 512;
    $data = array(
        'h' => octetsToSize($size),
        's' => $size
    );
    echo json_encode($data);
}
