<?php
$file = $_POST['file'];

if (empty($file)) {
    http_response_code(400);
} else {
    $file = urldecode($file);
    // Start download
    shell_exec('../../scripts/download-files-server.sh "' . $file . '" > /dev/null 2>/dev/null &');
}
