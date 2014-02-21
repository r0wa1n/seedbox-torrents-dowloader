<?php
$file = $_GET['file'];
$file = urldecode($file);

include('../src/constants.php');
include('../src/utils.php');
if (empty($file)) {
    http_response_code(400);
} else if (!file_exists(TEMP_DIR . SEEDBOX_NAME . '/' . $file)) {
    // Check if file is not in download dir
    if(file_exists(DOWNLOAD_DIRECTORY . $file)) {
        echo '-1';
    }
} else {
    $decodedFile = urldecode($file);
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