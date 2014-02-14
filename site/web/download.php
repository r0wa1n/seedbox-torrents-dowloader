<?php
$file = $_POST['file'];

if (empty($file)) {
    http_response_code(400);
} else {
    include('../src/constants.php');
    $file = urldecode($file);
//    if (!file_exists(FILES_TO_DOWNLOAD_SERVER_DIRECTORY . $file)) {
        // Start download
//        shell_exec('nohup TODO &');
//        if(!touch(FILES_TO_DOWNLOAD_SERVER_DIRECTORY . $file)) {
//            echo 'Unable to create file ' . $file;
//        }
//    }
}
