<?php
$file = $_GET['file'];

include('../src/constants.php');
include('../src/utils.php');
if (empty($file)) {
    http_response_code(400);
} else {
    $decodedFile = urldecode($file);
    $size = shell_exec('du -sk ' . DOWNLOAD_DIRECTORY . SEEDBOX_NAME . '/' . $decodedFile . ' | awk \'{print$1}\'') * 1024;
    $filesDetails = json_decode(file_get_contents(FILES_DETAILS_MIRROR_SEEDBOX), true);
    foreach($filesDetails as $fileDetail) {
        if($fileDetail['file'] == $decodedFile) {
            $data = array(
                'h' => octetsToSize($size),       // Human Readable size
                's' => $size,                     // current size
                't' => $fileDetail['size'] * 1024 // Total file size
            );
            echo json_encode($data);
        }
    }
}