<?php
$file = $_GET['file'];

if (empty($file)) {
    header('Location: index.php');
}

include('../src/constants.php');
$file = urldecode($file);
if (!file_exists(FILES_TO_DOWNLOAD_SERVER_DIRECTORY . $file)) {
    if(!touch(FILES_TO_DOWNLOAD_SERVER_DIRECTORY . $file)) {
        echo 'Unable to create file ' . $file;
    }
}