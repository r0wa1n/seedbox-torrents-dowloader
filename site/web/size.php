<?php
$file = $_GET['file'];
$file = urldecode($file);

include('../src/constants.php');
include('../src/utils.php');
$decodedFile = urldecode($file);
if (empty($decodedFile)) {
    http_response_code(400);
} else if (!file_exists(TEMP_DIR . SEEDBOX_NAME . '/' . $decodedFile)) {
    // Check if file is not in download dir
    if(file_exists(DOWNLOAD_DIRECTORY . $decodedFile)) {
        echo '-1';
    }
} else {
    $size = getFileSize(TEMP_DIR . SEEDBOX_NAME . '/' . $decodedFile);
    $filesDetails = json_decode(file_get_contents(TEMP_DIR . SEEDBOX_DETAILS_FILE), true);
    foreach ($filesDetails as $fileDetail) {
        if ($fileDetail['file'] == $decodedFile) {
            $data = array(
                'h' => octetsToSize($size), // Human Readable size
                's' => $size, // current size
                't' => $fileDetail['size'] // Total file size
            );
            echo json_encode($data);
        }
    }
}