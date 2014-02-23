<?php
$file = $_GET['file'];
$file = urldecode($file);

include('../src/constants.php');
include('../src/utils.php');
$decodedFile = urldecode($file);
// 4 cases :
//  - File/Dir can be downloaded
//  - File/Dir is already downloaded
//  - File/Dir is pending to be downloaded
//  - File/Dir is pending and is being downloaded
if (empty($decodedFile)) {
    http_response_code(400);
} else if (file_exists(TEMP_DIR . 'pending/' . $decodedFile)) {
    // File is pending, check if download is started or not
    if (file_exists(DOWNLOAD_DIRECTORY . $decodedFile)) {
        $currentSize = getFileSize(DOWNLOAD_DIRECTORY . $decodedFile);
        $filesDetails = json_decode(file_get_contents(TEMP_DIR . SEEDBOX_DETAILS_FILE), true);
        $pathFile = explode('/', $decodedFile);
        $size = searchSize($pathFile, 0, $filesDetails);

        echo json_encode(array(
            'h' => octetsToSize($currentSize), // Human Readable size
            's' => $currentSize, // current size
            't' => $size // Total file size
        ));
    } else {
        echo 'PENDING';
    }
} else if (file_exists(DOWNLOAD_DIRECTORY . $decodedFile)) {
    // File is downloaded
    echo 'DOWNLOADED';
} else {
    http_response_code(400);
}

function searchSize($filePath, $currentPathKey, $files)
{
    // Check if currentPath is the last one of $filePath
    if ($currentPathKey == (count($filePath) - 1)) {
        return $files[$filePath[$currentPathKey]]['size'];
    } else {
        return searchSize($filePath, ($currentPathKey + 1), $files[$filePath[$currentPathKey]]['children']);
    }
}